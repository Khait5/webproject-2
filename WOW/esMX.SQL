-- Script SQL de alta calidad (Regla de Oro) para el regalo de Sheila
-- Contiene los capítulos iniciales de los principales puntos de inicio para mostrar cómo aplicar el contexto a la mecánica de quests.
-- Para procesar el resto de los miles de misiones, utiliza el script 'WOW/generar_lore.py'.

-- Misión ID 7
UPDATE quest_template_locale
SET Title = 'La Limpieza del Campamento del Aburrimiento',
    Details = '$N, lo primero que tienes que hacer para el cumpleaños de Sheila es limpiar. Lord Antonio ha enviado a su General Leo y su clan del aburrimiento a los bosques del norte. Ve y liquida a todos los esbirros que encuentres. Si reducimos sus filas podremos poner reguetón en Umbrete de nuevo.',
    Objectives = 'Mata a 8 Alimañas del Aburrimiento (Kóbolds) y ve a hablar con el Guardián de Benacazón.',
    ObjectiveText1 = 'Alimaña del Aburrimiento matada'
WHERE ID = 7 AND locale = 'esMX';

UPDATE quest_offer_reward_locale
SET RewardText = '¡Bien hecho! Estamos un paso más cerca de recuperar los libros de estudio y las academias de vuelo.'
WHERE ID = 7 AND locale = 'esMX';

UPDATE quest_request_items_locale
SET CompletionText = '¿Has terminado de limpiarlos?'
WHERE ID = 7 AND locale = 'esMX';

-- Misión ID 15
UPDATE quest_template_locale
SET Title = 'Investigar la Mina Sin Reguetón',
    Details = '$n, los exploradores dicen que hay más secuaces de Lord Antonio de lo que creíamos. Un grupo ha acampado cerca de la Mina, al norte de aquí.$B$BVe a la mina y liquídalos; sabemos que hay al menos ocho. Comprueba que no quede ninguno más y vuelve, para que Sheila pueda bailar tranquilamente.',
    Objectives = 'Mata a 8 trabajadores del Aburrimiento y ve a hablar con el Guardián de Benacazón.',
    ObjectiveText1 = 'Trabajador del Aburrimiento eliminado'
WHERE ID = 15 AND locale = 'esMX';

UPDATE quest_offer_reward_locale
SET RewardText = 'Excelente trabajo. Lord Antonio no se saldrá con la suya.'
WHERE ID = 15 AND locale = 'esMX';

UPDATE quest_request_items_locale
SET CompletionText = '¿Has terminado de investigar?'
WHERE ID = 15 AND locale = 'esMX';

-- Misión ID 21
UPDATE quest_template_locale
SET Title = 'La Amenaza de Lord Antonio',
    Details = 'Los esbirros de Lord Antonio siguen llegando. $N, debes seguir luchar por Sheila, para que vuelva a ver sus girasoles.',
    Objectives = 'Sigue derrotando a los secuaces de Antonio.',
    ObjectiveText1 = 'Secuaz de Antonio derrotado'
WHERE ID = 21 AND locale = 'esMX';

UPDATE quest_offer_reward_locale
SET RewardText = 'La fiesta de cumpleaños será un éxito.'
WHERE ID = 21 AND locale = 'esMX';

UPDATE quest_request_items_locale
SET CompletionText = '¿Acabaste con los esbirros?'
WHERE ID = 21 AND locale = 'esMX';

-- Misión ID 4641
UPDATE quest_template_locale
SET Title = 'Tu Lugar en el Bastión del Sancocho',
    Details = 'Por fin ha llegado el momento, $n... el momento de que combatas en nombre de nuestra resistencia. Conquistarás por la gloria de Mami, nuestra líder.$B$BVe a hablar con Gornek el Reguetonero. Él te asignará tu primera tarea para defender nuestra comida tradicional y el reguetón.',
    Objectives = 'Habla con Gornek el Reguetonero en el Cubil.',
    ObjectiveText1 = ''
WHERE ID = 4641 AND locale = 'esMX';

UPDATE quest_offer_reward_locale
SET RewardText = '¡Bienvenido a las costas de Colombia! Tenemos mucho trabajo si queremos celebrar el cumpleaños de Sheila.'
WHERE ID = 4641 AND locale = 'esMX';

UPDATE quest_request_items_locale
SET CompletionText = '¿Has hablado con Gornek?'
WHERE ID = 4641 AND locale = 'esMX';

-- Misión ID 363
UPDATE quest_template_locale
SET Title = 'Desvelo Musical',
    Details = 'Me alegra que hayas despertado, recluta. Estábamos a punto de arrojarte al fuego, pero parece que el reguetón sigue fluyendo en ti. Ya no eres un esclavo de Lord Antonio.$B$BVe a hablar con Sebas, del Escuadrón Sombra. Él te guiará.',
    Objectives = 'Habla con Sebas.',
    ObjectiveText1 = ''
WHERE ID = 363 AND locale = 'esMX';

UPDATE quest_offer_reward_locale
SET RewardText = 'Bienvenido a La Resistencia. Prepárate para luchar por los girasoles de Sheila.'
WHERE ID = 363 AND locale = 'esMX';

UPDATE quest_request_items_locale
SET CompletionText = '¿Has encontrado a Sebas?'
WHERE ID = 363 AND locale = 'esMX';


-- Actualizaciones de NPCs clave
UPDATE creature_template_locale SET Name = 'Reina Lorena', Title = 'Reina de Gines y Umbrete' WHERE entry = 11699 AND locale = 'esMX';
UPDATE creature_template_locale SET Name = 'El Guardián de Benacazón', Title = 'El Novio de Sheila' WHERE entry = 197 AND locale = 'esMX';
UPDATE creature_template_locale SET Name = 'Papá', Title = 'Guardián de la Azafata' WHERE entry = 895 AND locale = 'esMX';
UPDATE creature_template_locale SET Name = 'Mami', Title = 'Líder del Bastión del Sancocho' WHERE entry = 4949 AND locale = 'esMX';
UPDATE creature_template_locale SET Name = 'Gornek el Reguetonero', Title = 'Tío de Sheila' WHERE entry = 3143 AND locale = 'esMX';
UPDATE creature_template_locale SET Name = 'Escuadrón Sombra', Title = 'Primos de Sheila' WHERE entry = 10181 AND locale = 'esMX';
UPDATE creature_template_locale SET Name = 'Sebas', Title = 'Miembro del Escuadrón Sombra' WHERE entry = 1568 AND locale = 'esMX';
UPDATE creature_template_locale SET Name = 'General Leo', Title = 'General del Aburrimiento' WHERE entry = 299 AND locale = 'esMX';
