# ColombianAge - Advanced Web Ecosystem & L2J Interlude Control Panel

Sitio web integral y panel de control (UCP/ACP) de alto rendimiento para el servidor privado **ColombianAge** (Lineage 2 Interlude - L2J Mobius), desarrollado en PHP 8.x nativo, HTML5 semántico, CSS3 moderno (Dark Fantasy theme) y JavaScript asíncrono.

---

## 📂 Estructura del Proyecto

```text
ColombianAge/
├── sql/
│   ├── game/               # Esquemas SQL del GameServer (characters, items, clans, etc.)
│   └── login/              # Esquemas SQL del LoginServer (accounts, gameservers, access)
├── web/
│   ├── assets/
│   │   ├── css/            # Estilos UI (Variables CSS, Flexbox/Grid, Responsive)
│   │   ├── js/             # Lógica asíncrona (Fetch API, sondeo de estado, validación en vivo)
│   │   └── img/            # Recursos gráficos, fondos e iconografía
│   ├── config/             # Configuración central (Credenciales DB, sockets, constantes)
│   ├── core/               # Clases PHP (Database PDO wrapper, Auth, Security, Encriptación L2J)
│   ├── modules/            # Controladores y vistas (Registro, Login, Panel de Cuenta, Rankings)
│   ├── api/                # Endpoints JSON (Server status, player counter, unstuck action)
│   └── index.php           # Punto de entrada principal (Landing Page con widgets en vivo)
└── README.md
⚙️ Configuración de Conexión
Gestor DB: MariaDB / MySQL

Host: 127.0.0.1 (o localhost)

Usuario: root

Contraseña: (vacía)

Base de Datos Unificada (Game & Login): l2jmobiusinterlude

🚀 Funcionalidades Principales
Monitoreo en Tiempo Real (Landing Page):

Verificación de sockets y conexión DB: Login Server (2106), Game Server (7777) y MySQL (3306).

Contador en vivo de jugadores conectados (SELECT count(*) FROM characters WHERE online = 1).

Sistema de Autenticación compatible con L2J Mobius:

Registro con verificación de disponibilidad de usuario vía Fetch API.

Algoritmo de hashing idéntico a L2J Mobius (base64_encode(pack('H*', sha1($password)))).

Panel de Usuario (Account Control Panel):

Resumen de personajes vinculados a la cuenta (Nivel, Clase, Clan, PvP/PK, Karma).

Herramienta de desatasco (Unstuck): Resetea coordenadas a una ciudad inicial (Giran/Talking Island) si el personaje está offline.

Cambio seguro de contraseña con validación de credencial anterior.

Rankings y Estadísticas Dinámicas:

Top PvP / PK, clanes dominantes y estado de castillos con actualización automática.

Seguridad Integrada:

Consultas preparadas exclusivamente con PDO para neutralizar inyecciones SQL.

Protección contra ataques CSRF mediante tokens de un solo uso en formularios.

Sanitización estricta contra XSS en todos los inputs.# webproject-2

