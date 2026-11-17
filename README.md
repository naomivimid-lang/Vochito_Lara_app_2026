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

Puedes ver la lista completa con `php artisan route:list`.

## Seguridad

- Todos los formularios llevan `@csrf`, y los métodos PUT, PATCH y DELETE se envían con `@method`.
- La validación ocurre en el servidor, con FormRequests.
- Las consultas usan Eloquent con parámetros enlazados, sin SQL concatenado.
- Las vistas imprimen con `{{ }}`, que escapa el HTML.
- Las credenciales solo viven en `.env`, que está en `.gitignore`.

## Equipo

| Integrante | Responsabilidad |
| Miguel Angel Guerrero Lopez(MAngelGL777) 2531126234 | Migraciones, modelos, validaciones y CRUD de productos y pedidos |

