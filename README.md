# Vochito Laravel (2026)

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

## 📋 Descripción del Proyecto

**Vochito Laravel** es una aplicación web integral desarrollada sobre el framework **Laravel 12** y **PHP**, concebida para la gestión operativa, logística y comercial de pedidos, entregas, incidencias, solicitudes de cancelación/modificación y reportes de desempeño de la plataforma **Bochito - Comercio Local**.

El sistema implementa una arquitectura **MVC limpia**, rutas **RESTful estructuradas**, validaciones de negocio en **Form Requests**, base de datos relacional con **migraciones y seeders**, y un diseño visual modular soportado en **layouts y partials reutilizables de Blade**.

---

## 🚀 Tecnologías y Herramientas

- **Backend:** Laravel 12.x, PHP 8.2+.
- **Frontend & Plantillas:** Blade Templating Engine, Bootstrap 5.3, Bootstrap Icons, Font Awesome 6.5.
- **Base de Datos:** SQLite (configuración predeterminada) / MySQL compatible mediante Eloquent ORM.
- **Validaciones:** Form Requests dedicados (`StorePedidoRequest`, `StoreSolicitudRequest`, etc.).
- **Control de Versiones:** Git & GitHub.

---

## 🏗️ Arquitectura del Sistema

### 1. Modelos y Relaciones Eloquent
- **`Pedido`**: Entidad central que administra el flujo de la orden de compra (`PO-YYYY-####`), cliente, producto, cantidad, fechas estimadas/reales y estatus.
  - `hasMany(Solicitud::class)`: Relación uno a muchos con solicitudes de cancelación y modificación.
  - `hasMany(Incidencia::class)`: Historial de incidencias y retrasos.
  - `hasMany(Evidencia::class)` / `hasOne(Evidencia::class)`: Evidencia fotográfica y confirmación de entrega.
- **`Solicitud`**: Control de solicitudes de cancelación y modificación con estados (*Pendiente*, *Aprobada*, *Rechazada*).
  - `belongsTo(Pedido::class)`: Enlace con el pedido evaluado.
- **`Incidencia`**: Registro de retrasos, fallas mecánicas o falta de insumos con ajuste de fecha estimada.
  - `belongsTo(Pedido::class)`.
- **`Evidencia`**: Registro de entrega con nombre del receptor, fecha y foto.
  - `belongsTo(Pedido::class)`.
- **`Contacto`**: Bandeja de mensajes ciudadanos y solicitudes de colaboración.

### 2. Validaciones de Formularios (Form Requests)
Toda entrada de datos es sanitizada y validada mediante clases dedicadas en `app/Http/Requests`:
- `StorePedidoRequest`: Valida cliente (mínimo 3 caracteres), cantidad positiva, fecha futura de entrega y formato de PO.
- `StoreSolicitudRequest`: Valida formato `PO-YYYY-####`, motivos, correo electrónico válido y comentarios descriptivos.
- `StoreIncidenciaRequest`: Valida tipo de incidencia, justificación y nueva fecha.
- `StoreEvidenciaRequest`: Valida receptor, fecha y formato de imagen.
- `StoreContactoRequest`: Valida estructura completa del mensaje de contacto.

### 3. Vistas Modulares (Layouts & Partials Reutilizables)
- **Layout Base:** `resources/views/layouts/app.blade.php` estandariza el encabezado, navegación, contenedor principal, sistema de alertas y pie de página.
- **Partials:**
  - `layouts/partials/navbar.blade.php`: Menú de navegación responsive con control de rutas activas.
  - `layouts/partials/footer.blade.php`: Pie de página global con enlaces institucionales.
  - `layouts/partials/alerts.blade.php`: Componente de notificaciones flash (`success`, `error`, validaciones).
  - `partials/form_solicitud.blade.php`: Formulario reutilizable parametrizable para cancelación o modificación.

---

## 🌐 Tabla de Rutas RESTful (`Route::resource`)

Las operaciones del sistema están organizadas bajo convenciones RESTful estándar en `routes/web.php`:

| Recurso | Método HTTP | URI | Controlador & Acción | Descripción |
| :--- | :--- | :--- | :--- | :--- |
| **Pedidos** | `GET` | `/pedidos` | `PedidoController@index` | Listado y administración de pedidos |
| | `POST` | `/pedidos` | `PedidoController@store` | Registro de nuevo pedido |
| | `GET` | `/pedidos/{id}` | `PedidoController@show` | Consulta detallada de una orden |
| | `PUT/PATCH` | `/pedidos/{id}` | `PedidoController@update` | Actualización de estatus o datos |
| | `DELETE` | `/pedidos/{id}` | `PedidoController@destroy` | Eliminación de un pedido |
| **Solicitudes** | `GET` | `/solicitudes` | `SolicitudController@index` | Listado general de solicitudes |
| | `POST` | `/solicitudes` | `SolicitudController@store` | Registro de cancelación o modificación |
| | `PATCH` | `/solicitudes/{id}/estado` | `SolicitudController@updateEstado` | Aprobar o rechazar solicitud |
| | `DELETE` | `/solicitudes/{id}` | `SolicitudController@destroy` | Eliminar solicitud |
| **Incidencias**| `GET` | `/incidencias` | `IncidenciaController@index` | Consulta de incidencias |
| | `GET` | `/incidencias/create` | `IncidenciaController@create` | Formulario de registro de incidencia |
| | `POST` | `/incidencias` | `IncidenciaController@store` | Guardar incidencia |
| **Evidencias** | `GET` | `/evidencias` | `EvidenciaController@index` | Listado de evidencias |
| | `GET` | `/evidencias/create` | `EvidenciaController@create` | Formulario de captura de entrega |
| | `POST` | `/evidencias` | `EvidenciaController@store` | Guardar comprobante y fotografía |
| **Reportes** | `GET` | `/reporte-desempeno` | `ReporteController@index` | Métricas operativas (KPIs) |
| | `POST` | `/reporte-desempeno/generar` | `ReporteController@generar` | Cálculo por rango de fechas |
| **Contacto** | `GET` | `/contacto` | `ContactoController@create` | Formulario de contacto |
| | `POST` | `/contacto` | `ContactoController@store` | Enviar mensaje |

---

## ⚙️ Guía de Instalación, Configuración y Ejecución

Sigue estos pasos para clonar, configurar y ejecutar el proyecto en tu entorno local:

### 1. Clonar el repositorio y seleccionar la rama
```bash
git clone -b Cancelaciones,modificaciones-y-reportes https://github.com/naomivimid-lang/Vochito_Lara_app_2026.git
cd Vochito_Lara_app_2026
```

### 2. Instalar dependencias de PHP con Composer
```bash
composer install
```

### 3. Configurar el archivo de entorno (`.env`)
En Windows PowerShell:
```powershell
Copy-Item .env.example .env
```
En Bash / macOS / Linux:
```bash
cp .env.example .env
```

### 4. Generar la clave de la aplicación
```bash
php artisan key:generate
```

### 5. Configurar la base de datos y ejecutar migraciones con Seeders
La base de datos predeterminada es SQLite. Asegúrate de que exista el archivo de base de datos (o se creará automáticamente):

En Windows PowerShell:
```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
```

Ejecuta las migraciones y puebla los datos iniciales de prueba:
```bash
php artisan migrate --seed
```

> **Datos precargados por el seeder:**
> - Órdenes de prueba con estatus: *Entregado a tiempo*, *Retrasado*, *Control de calidad*, *Cancelado* y *En tránsito*.
> - Solicitudes de cancelación y modificación en distintos estados.
> - Incidencias de logística y evidencias de entrega.
> - Usuario demo: `test@example.com` / `password123`.

### 6. Iniciar el servidor local
```bash
php artisan serve
```

### 7. Abrir la aplicación en el navegador
Ingresa a:
```
http://localhost:8000
```
o al puerto indicado en la consola.

---

## 👥 Equipo de Desarrollo

- **Vital Millán Dayra Naomi**
- **Guerrero López Miguel Ángel**
- **Carpio Campos Rodrigo**
- **Ortiz Salguero Geazul Guadalupe**
- **Lorenzana Hernández Aarón Dahir**

---

**Vochito Laravel 2026** – *Plataforma de comercio local y logística colaborativa.*
