# VOCHITO – Envíos y Ventas

## Descripción del proyecto

**VOCHITO** es una aplicación web desarrollada con Laravel 12 que adapta el sistema original de Vochito a una estructura basada en el patrón MVC (Modelo-Vista-Controlador).

El sistema está enfocado en la gestión de productos y pedidos de tiendas locales, además de contar con diferentes módulos para el seguimiento y administración de las operaciones.

El proyecto forma parte de una práctica académica orientada al desarrollo web, el uso de rutas, vistas Blade, controladores, peticiones HTTP y operaciones CRUD.

## Objetivo

Desarrollar una aplicación web con Laravel que permita administrar productos y pedidos, conservar las interfaces del proyecto original y aplicar operaciones CRUD mediante una estructura organizada.

## Tecnologías utilizadas

- **PHP:** lenguaje de programación del lado del servidor.
- **Laravel 12:** framework de desarrollo web.
- **Blade:** motor de plantillas para las vistas.
- **PostgreSQL:** sistema de gestión de base de datos.
- **HTML5 y CSS3:** estructura y diseño de las interfaces.
- **JavaScript:** funcionalidades e interactividad.
- **Bootstrap:** componentes y diseño adaptable.
- **Git y GitHub:** control de versiones y colaboración.
- **Visual Studio Code:** entorno de desarrollo.

## Funcionalidades

### Productos

- Registrar productos.
- Consultar el listado de productos.
- Editar información de los productos.
- Eliminar productos.
- Buscar productos por nombre.
- Consultar el catálogo de productos activos.

### Pedidos

- Registrar pedidos.
- Consultar pedidos registrados.
- Editar información de los pedidos.
- Cancelar pedidos.
- Filtrar por cliente, estatus y fechas de entrega.
- Actualizar el estatus de los pedidos.

### Otros módulos del sistema

El proyecto también conserva interfaces para:

- Inicio y navegación.
- Nosotros y servicios.
- Búsquedas e historial.
- Incidencias.
- Notificaciones.
- Evidencias de entrega.
- Cancelaciones y modificaciones.
- Reportes de desempeño.
- Contacto y ayuda.
- Requerimientos no funcionales.

## Estructura del proyecto

```text
Vochito_Lara_app_2026/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Requests/
│   ├── Models/
│   └── Providers/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── css/
│   ├── js/
│   └── img/
├── resources/
│   └── views/
│       ├── layouts/
│       ├── partials/
│       ├── pedidos/
│       ├── productos/
│       └── vochito/
├── routes/
│   └── web.php
├── .env.example
├── composer.json
└── README.md
```

## Operaciones CRUD

El módulo de productos y el módulo de pedidos utilizan las siguientes operaciones:

| Método HTTP | Operación | Descripción                     |
| ----------- | --------- | ------------------------------- |
| GET         | Read      | Consultar registros.            |
| POST        | Create    | Registrar nuevos registros.     |
| PUT         | Update    | Modificar registros existentes. |
| DELETE      | Delete    | Eliminar registros.             |

Para cambiar el estatus de un pedido también se utiliza una petición PATCH.

## Base de datos

La aplicación cuenta con migraciones y modelos para las tablas principales:

| Tabla     | Información                                                          |
| --------- | -------------------------------------------------------------------- |
| productos | Nombre, descripción, precio, stock y estado del producto.            |
| pedidos   | Cliente, producto relacionado, cantidad, fecha de entrega y estatus. |

La relación entre productos y pedidos se establece mediante la llave foránea `producto_id`.

## Instalación y ejecución

### 1. Clonar el repositorio

```bash
git clone https://github.com/naomivimid-lang/Vochito_Lara_app_2026.git
```

### 2. Entrar a la carpeta

```bash
cd Vochito_Lara_app_2026
```

### 3. Instalar dependencias

```bash
composer install
```

### 4. Configurar el archivo de entorno

En PowerShell:

```powershell
Copy-Item .env.example .env
```

### 5. Generar la clave de Laravel

```bash
php artisan key:generate
```

### 6. Configurar PostgreSQL

Crear una base de datos para el proyecto y configurar las credenciales correspondientes en el archivo `.env`.

Ejemplo de configuración:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 7. Ejecutar las migraciones y los datos de prueba

```bash
php artisan migrate --seed
```

### 8. Iniciar el servidor

```bash
php artisan serve
```

Abrir en el navegador:

```text
http://127.0.0.1:8000
```

## Rutas principales

| Método | Ruta                         | Función                         |
| ------ | ---------------------------- | ------------------------------- |
| GET    | `/productos`                 | Consultar productos.            |
| GET    | `/productos/create`          | Mostrar formulario de registro. |
| POST   | `/productos`                 | Registrar producto.             |
| GET    | `/productos/{producto}/edit` | Mostrar formulario de edición.  |
| PUT    | `/productos/{producto}`      | Actualizar producto.            |
| DELETE | `/productos/{producto}`      | Eliminar producto.              |
| GET    | `/pedidos`                   | Consultar pedidos.              |
| GET    | `/pedidos/create`            | Mostrar formulario de registro. |
| POST   | `/pedidos`                   | Registrar pedido.               |
| GET    | `/pedidos/{pedido}/edit`     | Mostrar formulario de edición.  |
| PUT    | `/pedidos/{pedido}`          | Actualizar pedido.              |
| PATCH  | `/pedidos/{pedido}/estatus`  | Actualizar estatus.             |
| DELETE | `/pedidos/{pedido}`          | Cancelar pedido.                |
| GET    | `/catalogo`                  | Consultar catálogo.             |

Para consultar todas las rutas registradas:

```bash
php artisan route:list
```

## Validaciones y seguridad

- Los formularios utilizan protección CSRF mediante `@csrf`.
- Las operaciones PUT, PATCH y DELETE utilizan `@method`.
- Los datos se validan en el servidor.
- Se utilizan controladores y modelos para organizar la aplicación.
- Las credenciales de conexión se configuran en `.env`.
- Las vistas Blade utilizan el mecanismo de escape de Laravel.

## Equipo de trabajo

- Vital Millán Dayra Naomi.
- Guerrero López Miguel Ángel.
- Carpio Campos Rodrigo.
- Ortiz Salguero Geazul Guadalupe.
- Lorenzana Hernández Aarón Dahir.

## Evidencias

Las capturas de pantalla del desarrollo se encuentran en la carpeta `docs/capturas/`.

**VOCHITO – Proyecto académico 2026.**
