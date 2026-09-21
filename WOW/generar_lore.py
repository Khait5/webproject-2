"""
Script de utilidad para generar la totalidad de las misiones del juego (3.3.5a)
respetando la Regla de Oro mediante IA y el esquema explícito proporcionado.

Como es imposible generar ~10,000 misiones a mano respetando los objetivos
mecánicos en un solo prompt, este script demuestra cómo podrías conectar la
base de datos a una API de LLM para procesar cada misión automáticamente.

Requisitos: mysql-connector-python, openai
"""

import os
import sys
import argparse
import mysql.connector
from openai import OpenAI
import json

def parse_args():
    parser = argparse.ArgumentParser(description="Generar lore masivo para WoW usando IA.")
    parser.add_argument("--db-host", default="localhost", help="Database host")
    parser.add_argument("--db-user", default="root", help="Database user")
    parser.add_argument("--db-password", default="", help="Database password")
    parser.add_argument("--db-name", default="acore_world", help="Database name")
    parser.add_argument("--api-key", help="OpenAI API Key (or set OPENAI_API_KEY env var)")
    parser.add_argument("--quest-ids", help="Comma separated list of Quest IDs to process (to restrict to main quests).")
    return parser.parse_args()

def main():
    args = parse_args()

    api_key = args.api_key or os.environ.get("OPENAI_API_KEY")
    if not api_key:
        print("Error: Se requiere una API Key de OpenAI (--api-key o variable de entorno OPENAI_API_KEY).")
        sys.exit(1)

    client = OpenAI(api_key=api_key)

    try:
        conn = mysql.connector.connect(
            host=args.db_host,
            user=args.db_user,
            password=args.db_password,
            database=args.db_name
        )
        cursor = conn.cursor(dictionary=True)
    except mysql.connector.Error as err:
        print(f"Error conectando a la base de datos: {err}")
        sys.exit(1)

    query = """
    SELECT ID, locale, Title, Details, Objectives, OfferReward, RequestItems
    FROM quest_template_locale
    WHERE locale IN ('esES', 'esMX')
    """

    if args.quest_ids:
        ids = [str(int(q_id.strip())) for q_id in args.quest_ids.split(",") if q_id.strip().isdigit()]
        if ids:
            query += f" AND ID IN ({','.join(ids)})"
        else:
            print("No valid quest IDs provided.")
            sys.exit(1)
    else:
        print("ADVERTENCIA: No proveíste --quest-ids. Esto procesará TODAS las misiones y puede costar muchos créditos de API.")

    cursor.execute(query)
    quests = cursor.fetchall()

    system_prompt = """
    Eres un asistente diseñador de narrativa para World of Warcraft.
    Tu tarea es reescribir textos de misiones para un regalo de cumpleaños (la cumpleañera es Sheila, colombiana, vive en Umbrete, ama los girasoles, peluches, leer, el reguetón y su sueño es ser azafata).
    El mundo ahora se llama 'El Reino de Umbrete'. El villano es Lord Antonio (su padrastro), quien prohibió el reguetón, quemó los girasoles y prohibió el vuelo.
    Sus generales son Leo e Isabel.
    Alianza = Gines/Umbrete (Reina Lorena, Guardián de Benacazón).
    Horda = Costas de Colombia (Mami, Bastión del Sancocho).
    No-Muertos = La Resistencia (Escuadrón Sombra: Nicolás, Sebas, Nicolle).
    Entrenadores = Papá y Mamá.

    REGLA DE ORO: El texto debe justificar los objetivos mecánicos originales para que los bots no se rompan.
    Si la misión original pide matar 10 lobos, tu nueva historia DEBE pedir matar 10 'Lobos del Aburrimiento'.

    Devuelve estrictamente un JSON válido con las siguientes claves:
    'Title', 'Details', 'Objectives', 'OfferReward', 'RequestItems'.
    No devuelvas NADA MÁS que el JSON.
    """

    file_esES = open('WOW/esES.SQL', 'a', encoding='utf-8')
    file_esMX = open('WOW/esMX.SQL', 'a', encoding='utf-8')

    print(f"Procesando {len(quests)} misiones...")

    for i, quest in enumerate(quests):
        print(f"Procesando misión {i+1}/{len(quests)} (ID: {quest['ID']})")

        # Protect against NULLs coming from DB
        orig_title = quest.get('Title') or ""
        orig_details = quest.get('Details') or ""
        orig_objectives = quest.get('Objectives') or ""
        orig_offer = quest.get('OfferReward') or ""
        orig_request = quest.get('RequestItems') or ""

        user_prompt = f"Misión Original:\nTitle: {orig_title}\nDetails: {orig_details}\nObjectives: {orig_objectives}\nOfferReward: {orig_offer}\nRequestItems: {orig_request}\n"

        try:
            response = client.chat.completions.create(
                model="gpt-4o-mini",
                messages=[
                    {"role": "system", "content": system_prompt},
                    {"role": "user", "content": user_prompt}
                ],
                temperature=0.7
            )

            result_text = response.choices[0].message.content.strip()
            if result_text.startswith("```json"):
                result_text = result_text[7:-3].strip()
            elif result_text.startswith("```"):
                result_text = result_text[3:-3].strip()

            new_quest = json.loads(result_text)

            # Protect against nulls coming from JSON
            title = (new_quest.get('Title') or "").replace("'", "''")
            details = (new_quest.get('Details') or "").replace("'", "''")
            objectives = (new_quest.get('Objectives') or "").replace("'", "''")
            offer = (new_quest.get('OfferReward') or "").replace("'", "''")
            request = (new_quest.get('RequestItems') or "").replace("'", "''")

            sql_q = f"UPDATE quest_template_locale SET Title = '{title}', Details = '{details}', Objectives = '{objectives}', OfferReward = '{offer}', RequestItems = '{request}' WHERE ID = {quest['ID']} AND locale = '{quest['locale']}';\n"

            if quest['locale'] == 'esES':
                file_esES.write(sql_q)
            else:
                file_esMX.write(sql_q)

        except Exception as e:
            print(f"Error procesando misión ID {quest['ID']}: {e}")

    file_esES.close()
    file_esMX.close()
    cursor.close()
    conn.close()
    print("Proceso completado. Archivos SQL generados en la carpeta WOW/")

if __name__ == "__main__":
    main()
