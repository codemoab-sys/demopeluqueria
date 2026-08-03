# TPV Peluquería

**Software de gestión integral para peluquerías, barberías y salones de belleza.**

Aplicación web construida con **Laravel 11**, **MySQL**, **Bootstrap 5** y **Chart.js**, con un diseño moderno en paleta rosa/violeta.

## Funcionalidades incluidas

- **Dashboard** con KPIs en vivo, gráficos estadísticos y resumen del día
- **Agenda y citas** con calendario interactivo (FullCalendar), drag & drop, asignación a profesionales
- **TPV / Punto de venta** con cobro rápido de servicios y productos, métodos de pago múltiples e impresión de ticket
- **Clientes (CRM)** con ficha completa, historial, fotos, alergias, preferencias, puntos de fidelización
- **Bonos** y plantillas de bonos para fidelización con control de sesiones usadas
- **Servicios** organizados por categorías con duración, precio, comisiones y colores
- **Productos y stock** con control de inventario, alertas de stock bajo y movimientos
- **Equipo** (empleados) con horarios, comisiones, foto y color en agenda
- **Caja diaria**: apertura, cierre, movimientos, descuadre y desglose por método de pago
- **Ventas** con histórico, ticket imprimible y anulaciones
- **Informes**: ventas, clientes, empleados y servicios más vendidos
- **Configuración** completa: datos empresa, logo, moneda en soles peruanos (S/.), IGV, horario, mensaje ticket
- **Usuarios y roles** (admin, gerente, empleado, recepcionista)
- **Backup, restauración y reset** del sistema

## Requisitos

- PHP 8.2 o superior
- Composer
- MySQL 5.7+ o MariaDB 10.3+
- Servidor web (Apache, Nginx, o usa `php artisan serve`)
- Extensiones PHP: `mbstring`, `pdo_mysql`, `gd`, `fileinfo`, `openssl`

## Instalación paso a paso

### 1. Crear la base de datos en MySQL

Conéctate a MySQL (con phpMyAdmin, MySQL Workbench o terminal) y ejecuta:

```sql
CREATE DATABASE tpv_peluqueria CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Configuración del servidor MySQL esperada:

```
server   = localhost
user     = root
password = (vacío o tu contraseña)
database = tpv_peluqueria
port     = 3306
```

### 2. Instalar dependencias

Desde la carpeta del proyecto, abre una terminal y ejecuta:

```bash
composer install
```

### 3. Configurar el entorno

Copia el archivo de variables de entorno y genera la clave:

```bash
copy .env.example .env
php artisan key:generate
```

Edita el archivo `.env` y ajusta la conexión a tu MySQL si es necesario:

```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tpv_peluqueria
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Ejecutar migraciones y datos iniciales

```bash
php artisan migrate --seed
```

Esto creará todas las tablas, una empresa demo, un usuario administrador y un catálogo inicial (servicios, empleados, productos, tipos de bonos).

### 5. Crear enlace simbólico para imágenes

```bash
php artisan storage:link
```

### 6. Levantar el servidor

```bash
php artisan serve
```

Abre tu navegador en: **http://localhost:8000**

## Acceso por defecto

Tras ejecutar los seeders, puedes entrar con:

```
Administrador
  Email:    admin@tpv.com
  Pass:     admin1234

Empleado
  Email:    empleado@tpv.com
  Pass:     empleado1234
```

> Cambia estas contraseñas inmediatamente desde **Usuarios** una vez dentro del sistema.

## Primer arranque recomendado

1. Inicia sesión como administrador
2. Ve a **Configuración** y rellena los datos reales de tu empresa, sube tu logo y configura moneda, IGV y horario
3. Ve a **Equipo** y registra a tus empleados (asigna un color a cada uno para verlos en la agenda)
4. Ve a **Servicios** y revisa o adapta el catálogo precargado
5. Ve a **Productos** si quieres vender productos en el TPV
6. Si vas a empezar de cero (sin datos de prueba), entra en **Backup & Reset** y haz un *Reset del sistema*
7. Antes de cualquier acción crítica, **genera una copia de seguridad**

## Estructura de carpetas

```
tpv-peluqueria/
├── app/
│   ├── Http/Controllers/   ← Lógica de los módulos
│   ├── Models/             ← Modelos Eloquent
│   ├── Http/Middleware/
│   └── Providers/
├── bootstrap/
├── config/                 ← Configuración Laravel
├── database/
│   ├── migrations/         ← Estructura de la BD
│   └── seeders/            ← Datos iniciales
├── public/                 ← Punto de entrada y assets
│   └── css/app.css         ← Estilos personalizados (rosa/violeta)
├── resources/
│   └── views/              ← Plantillas Blade
│       ├── layouts/        ← app.blade.php (sidebar + topbar)
│       ├── auth/           ← login
│       ├── dashboard/
│       ├── agenda/
│       ├── tpv/
│       ├── clientes/
│       ├── bonos/
│       ├── servicios/
│       ├── productos/
│       ├── caja/
│       ├── ventas/
│       ├── empleados/
│       ├── configuracion/
│       ├── usuarios/
│       ├── informes/
│       └── sistema/backup/
├── routes/
│   └── web.php             ← Todas las rutas
├── storage/
│   └── app/
│       ├── backups/        ← Copias de seguridad
│       └── public/         ← Logos, fotos, imágenes
├── .env.example
├── artisan
├── composer.json
└── README.md
```

## Backups y restauración

- **Crear copia**: ve a *Sistema → Backup* y pulsa "Generar copia de seguridad". Se guarda en `storage/app/backups/` y se descarga.
- **Restaurar**: sube un archivo `.sql` previamente exportado y escribe `CONFIRMAR`. La restauración sobrescribe todos los datos.
- **Resetear**: borra todos los datos operativos (clientes, citas, ventas, productos…). Útil al empezar a usar el sistema con una nueva empresa. Escribe `RESETEAR` para confirmar.

## Roles y permisos

| Rol            | Permisos                                                            |
|----------------|---------------------------------------------------------------------|
| `admin`        | Acceso total: configuración, usuarios, backup, reset                |
| `gerente`      | Gestión completa salvo administración del sistema                   |
| `empleado`     | Agenda, TPV, clientes, productos                                    |
| `recepcionista`| Agenda, TPV, clientes (sin acceso a inventario ni informes)         |

## Solución de problemas

**Error: "could not find driver"** → Activa la extensión `pdo_mysql` en tu `php.ini`.

**Error: 419 Page Expired** → Limpia caché con:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

**Las imágenes no se ven** → Asegúrate de haber ejecutado `php artisan storage:link`.

**No hay icono en agenda / FullCalendar no carga** → El proyecto usa CDN. Verifica que tienes conexión a internet.

## Personalización de colores

La paleta rosa/violeta está centralizada en `public/css/app.css` mediante variables CSS:

```css
--primary: #a855f7;       /* Violeta principal */
--primary-dark: #7c3aed;
--secondary: #ec4899;     /* Rosa */
--accent: #f59e0b;
```

Cámbialas y todo el sistema se adapta.

## Licencia

MIT — libre para uso comercial.

---

Hecho con cariño para que tu peluquería funcione mejor cada día. 💇‍♀️✨
