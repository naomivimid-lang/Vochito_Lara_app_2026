# Bochito – Envíos y Ventas

Aplicación web CRUD hecha con **Laravel 12** y **PostgreSQL** para gestionar los **productos** de tiendas locales y los **pedidos** de los clientes. Incluye validación del lado del servidor, mensajes en español, búsqueda, filtros, paginación y modo oscuro.

## Funcionalidades

**Productos** (`/productos`)
- Alta, edición y eliminación, con búsqueda por nombre y paginación de 10 registros.
- No permite nombres repetidos.
- Un producto con pedidos no se puede eliminar. La app muestra un mensaje controlado en vez de un error.
- Los productos inactivos no aparecen en el catálogo ni al registrar pedidos.

**Pedidos** (`/pedidos`)
- Alta, edición y cancelación (eliminación), con confirmación antes de borrar.
- Filtros por cliente, estatus y rango de fechas de entrega.
- Cambio rápido de estatus desde la tabla: Orden recibida → En fabricación → Control de calidad → Empaquetado → En tránsito.
- Validación de campos obligatorios, de cantidades (de 1 a 1000) y de la fecha de entrega, que debe ser futura al registrar.

**Catálogo** (`/catalogo`): tarjetas con los productos activos, leídos de la base de datos.

**Otros módulos del equipo**
- **Incidencias** (`/incidencias`): registro y seguimiento de problemas en los pedidos.
- **Notificaciones** (`/notificaciones`): avisos del sistema.
- **Evidencias** (`/evidencias`): evidencias de entrega.

## Tecnologías

| Herramienta | Versión |
|---|---|
| PHP | 8.2 o superior |
| Laravel | 12 |
| PostgreSQL | 17 |
| Bootstrap | 5.3 |
| Font Awesome | 6.5 |

## Instalación

1. Clona el repositorio y entra a la carpeta:

   ```bash
   git clone https://github.com/naomivimid-lang/Vochito_Lara_app_2026.git
   cd Vochito_Lara_app_2026
   ```

2. Instala las dependencias de PHP:

   ```bash
   composer install
   ```

3. Copia el archivo de entorno y genera la llave de la aplicación:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   En Windows PowerShell, usa `Copy-Item .env.example .env` en lugar de `cp`.

4. Crea la base de datos en PostgreSQL:

   ```sql
   CREATE DATABASE laravel;
   ```

   Después, en `.env`, ajusta `DB_USERNAME` y `DB_PASSWORD` con tu usuario de PostgreSQL. El archivo `.env` no se sube al repositorio.

5. Crea las tablas y carga los datos de ejemplo (10 productos del catálogo, con un pedido cada uno):

   ```bash
   php artisan migrate --seed
   ```

6. Inicia el servidor y abre `http://127.0.0.1:8000`:

   ```bash
   php artisan serve
   ```

Para reiniciar la base con los datos de ejemplo, corre `php artisan migrate:fresh --seed`. Este comando borra todos los registros.

## Estructura (MVC)

```
app/
├── Http/Controllers/
│   ├── ProductoController.php    CRUD de productos y catálogo
│   └── PedidoController.php      CRUD de pedidos y cambio de estatus
├── Http/Requests/                Validaciones con mensajes en español
│   ├── StoreProductoRequest.php / UpdateProductoRequest.php
│   ├── StorePedidoRequest.php   / UpdatePedidoRequest.php
│   └── ActualizarEstatusRequest.php
└── Models/
    ├── Producto.php              hasMany pedidos
    └── Pedido.php                belongsTo producto, lista de estatus
database/
├── migrations/                   tablas productos y pedidos (llave foránea)
├── factories/ProductoFactory.php
└── seeders/ProductoSeeder.php
resources/views/
├── layouts/app.blade.php         menú, encabezado y pie comunes
├── partials/flash.blade.php      mensajes de éxito y error
├── productos/                    index, create, edit, _form
├── pedidos/                      index, create, edit, _form
└── vochito/                      páginas informativas y catálogo
routes/web.php
```

## Base de datos

| Tabla | Campos principales |
|---|---|
| `productos` | `nombre` (único), `descripcion`, `precio` decimal(10,2), `stock`, `activo` |
| `pedidos` | `cliente`, `producto_id` (llave foránea que impide borrar el producto), `cantidad`, `entrega`, `estatus` |

## Rutas principales

| Método | URL | Acción |
|---|---|---|
| GET | `/productos` | Lista y búsqueda de productos |
| GET / POST | `/productos/create`, `/productos` | Registrar producto |
| GET / PUT | `/productos/{id}/edit`, `/productos/{id}` | Editar producto |
| DELETE | `/productos/{id}` | Eliminar producto |
| GET | `/pedidos` | Lista y filtros de pedidos |
| GET / POST | `/pedidos/create`, `/pedidos` | Registrar pedido |
| GET / PUT | `/pedidos/{id}/edit`, `/pedidos/{id}` | Editar pedido |
| PATCH | `/pedidos/{id}/estatus` | Cambiar estatus |
| DELETE | `/pedidos/{id}` | Cancelar pedido |
| GET | `/catalogo` | Catálogo de productos activos |
| GET / POST / PUT / DELETE | `/incidencias` | CRUD de incidencias |
| GET / POST / PUT / DELETE | `/notificaciones` | CRUD de notificaciones |
| GET / POST / PUT / DELETE | `/evidencias` | CRUD de evidencias |

Puedes ver la lista completa con `php artisan route:list`.

## Seguridad

- Todos los formularios llevan `@csrf`, y los métodos PUT, PATCH y DELETE se envían con `@method`.
- La validación ocurre en el servidor, con FormRequests.
- Las consultas usan Eloquent con parámetros enlazados, sin SQL concatenado.
- Las vistas imprimen con `{{ }}`, que escapa el HTML.
- Las credenciales solo viven en `.env`, que está en `.gitignore`.

## Equipo

| Integrante | Responsabilidad |
|---|---|
| Miguel Angel Guerrero Lopez(MAngelGL777) 2531126234 | Migraciones, modelos, validaciones y CRUD de productos y pedidos |
| Vital Millán Dayra Naomi | |
| Carpio Campos Rodrigo | |
| Ortiz Salguero Geazul Guadalupe | |
| Lorenzana Hernández Aarón Dahir | |

## Evidencia

![Imagen del proyecto](public/img/fff1.png)

![Imagen del proyecto](<Captura de pantalla 2026-11-04 145333.png>)

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

Crear una base de datos y configurar las credenciales correspondientes en el archivo `.env`.

Ejemplo:

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

```
http://127.0.0.1:8000
```

---

## Aportaciones de Guerrero López Miguel Ángel

### Issue #3 (Miguel): Pedidos y productos (CRUD principal)

**Interfaces trabajadas:**

- `pedidos/` (`index`, `create`, `edit` y `_form`), que reemplaza a `pedidos.blade.php`
- `productos/` (`index`, `create`, `edit` y `_form`), que reemplaza a `productos.blade.php`
- `catalogo.blade.php`

**Actividades realizadas:**

- Registro de pedidos con el producto elegido de una lista de productos activos. Todo pedido inicia con el estatus "Orden recibida".
- Consulta de los pedidos registrados con número de orden (`PO-0001`) y filtros por cliente, estatus y rango de fechas de entrega.
- Modificación de pedidos con el formulario precargado, incluida la fecha de entrega y el estatus.
- Cancelación (eliminación) de pedidos con confirmación antes de borrar.
- Registro y consulta de productos, con búsqueda por nombre.
- Modificación y eliminación de productos. Regla de negocio: no se puede eliminar un producto que tiene pedidos; se muestra un mensaje controlado en lugar de un error.
- Actualización rápida del estatus del pedido desde la tabla, con un color por cada estatus.
- Validación de cantidades (de 1 a 1000), fechas (la entrega debe ser futura al registrar) y datos obligatorios. Los errores aparecen bajo cada campo y se conservan los datos escritos.
- Catálogo en tarjetas con los productos activos leídos de PostgreSQL.

**Controladores:** `PedidoController.php` y `ProductoController.php`.

### Issue #15 (Miguel2): Base de datos, validaciones y paginación

- **Migraciones:** tablas `productos` (`2026_11_17_001831_create_productos_table.php`) y `pedidos` (`2026_11_17_001838_create_pedidos_table.php`). El nombre del producto es único, el precio es decimal y el stock no puede ser negativo.
- **Modelos y relaciones:** un `Producto` tiene muchos `Pedidos` (`hasMany`) y cada `Pedido` pertenece a un `Producto` (`belongsTo` por `producto_id`). La llave foránea usa `restrictOnDelete()` para no dejar pedidos sin producto.
- **Validaciones de formularios** con Form Requests y mensajes en español:
  `StoreProductoRequest`, `UpdateProductoRequest`, `StorePedidoRequest`, `UpdatePedidoRequest` y `ActualizarEstatusRequest`.
- **Paginación** de 10 registros con estilo Bootstrap 5 en productos y pedidos, que conserva la búsqueda y los filtros al cambiar de página.
- **Rutas RESTful** con `Route::resource()` para `productos` y `pedidos`, y una ruta `PATCH` para el estatus.
- **Layout y formularios reutilizables:**
  - `layouts/app.blade.php` (menú, encabezado y pie comunes) y `partials/flash.blade.php` (mensajes de éxito y error).
  - `productos/_form.blade.php` y `pedidos/_form.blade.php` (el mismo formulario para crear y editar).
- **Seeder** con datos de prueba (`ProductoSeeder.php`, que crea los 10 productos del catálogo con un pedido cada uno, integrado en `DatabaseSeeder.php`).
- **Configuración:** `.env.example` para PostgreSQL sin contraseña y corrección del modo oscuro (`public/css/tema.css`).

### Integración de ramas del equipo

Se resolvieron los conflictos para poder integrar en `main` las ramas de varios integrantes:

- **PR #20 (`Vochito_Laravel_MAGL`):** CRUD de productos y pedidos, junto con incidencias, notificaciones y evidencias.
- **PR #18 (`ActuRodrigo`):** se completaron el inicio de sesión, el registro, el cierre de sesión, la columna `is_admin`, el middleware `admin` y la administración de usuarios.
- **PR #21 (`feature/consultas/Geazul`):** el módulo de consultas (búsquedas, historial y tiempo de respuesta) se adaptó a la tabla `pedidos`, sin migraciones duplicadas.

### Ejecución de migraciones y datos de prueba

Después de configurar la base de datos en el archivo `.env`:

```bash
php artisan migrate --seed
```
