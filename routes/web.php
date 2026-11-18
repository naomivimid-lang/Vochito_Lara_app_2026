
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\EvidenciaController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UserController;

// Páginas públicas
Route::get('/', function () {
    return view('vochito.inicio');
})->name('vochito.inicio');

Route::view('/nosotros', 'vochito.nosotros')->name('vochito.nosotros');
Route::view('/servicios', 'vochito.servicios')->name('vochito.servicios');
Route::view('/contacto', 'vochito.contacto')->name('vochito.contacto');
Route::view('/ayuda', 'vochito.ayuda')->name('vochito.ayuda');
Route::view('/privacidad', 'vochito.privacidad')->name('vochito.privacidad');

Route::view('/requerimientos', 'vochito.rnf')->name('vochito.rnf');
Route::view('/rnf03', 'vochito.RNF03')->name('vochito.rnf03');
Route::view('/rnf04', 'vochito.RNF04')->name('vochito.rnf04');
Route::view('/rnf05', 'vochito.RNF05')->name('vochito.rnf05');
Route::view('/rnf06', 'vochito.RNF06')->name('vochito.rnf06');

Route::view('/iniciar-sesion', 'vochito.iniciosesion')->name('vochito.iniciosesion');
Route::view('/indice', 'vochito.index')->name('vochito.index');

// Catálogo público
Route::get('/catalogo', [ProductoController::class, 'catalogo'])
    ->name('vochito.catalogo');

// Registro e inicio de sesión
Route::middleware('guest')->group(function () {
    Route::view('/registro', 'vochito.registro')->name('registro');

    Route::post('/registro', [AuthController::class, 'register'])
        ->name('registro.store');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login');
});

// Cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Módulos privados: requieren iniciar sesión
Route::middleware('auth')->group(function () {

    // CRUD de productos y pedidos
    Route::resource('productos', ProductoController::class)->except('show');
    Route::resource('pedidos', PedidoController::class)->except('show');

    Route::patch('pedidos/{pedido}/estatus', [PedidoController::class, 'actualizarEstatus'])
        ->name('pedidos.estatus');

    // CRUD de solicitudes
    Route::resource('solicitudes', SolicitudController::class);

    Route::patch('/solicitudes/{id}/estado', [SolicitudController::class, 'updateEstado'])
        ->name('vochito.solicitudes.estado');

    // Reportes
    Route::resource('reportes', ReporteController::class)->only(['index', 'store']);

    Route::get('/reporte-desempeno', [ReporteController::class, 'index'])
        ->name('vochito.reporte_desempeno');

    Route::post('/reporte-desempeno/generar', [ReporteController::class, 'generar'])
        ->name('vochito.reporte.generar');

    // Consultas
    Route::get('/busquedas', [ConsultaController::class, 'busquedas'])
        ->name('vochito.busquedas');

    Route::get('/historial', [ConsultaController::class, 'historial'])
        ->name('vochito.historial');

    Route::get('/tiempo-respuesta', [ConsultaController::class, 'tiempoRespuesta'])
        ->name('vochito.tiempo_respuesta');

    // CRUD de notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index'])
        ->name('vochito.notificaciones');

    Route::post('/notificaciones', [NotificacionController::class, 'store'])
        ->name('notificaciones.store');

    Route::put('/notificaciones/{id}', [NotificacionController::class, 'update'])
        ->name('notificaciones.update');

    Route::delete('/notificaciones/{id}', [NotificacionController::class, 'destroy'])
        ->name('notificaciones.destroy');

    // CRUD de incidencias
    Route::get('/incidencias', [IncidenciaController::class, 'index'])
        ->name('vochito.incidencias');

    Route::post('/incidencias', [IncidenciaController::class, 'store'])
        ->name('incidencias.store');

    Route::put('/incidencias/{id}', [IncidenciaController::class, 'update'])
        ->name('incidencias.update');

    Route::delete('/incidencias/{id}', [IncidenciaController::class, 'destroy'])
        ->name('incidencias.destroy');

    // CRUD de evidencias
    Route::get('/evidencias', [EvidenciaController::class, 'index'])
        ->name('vochito.evidencias');

    Route::post('/evidencias', [EvidenciaController::class, 'store'])
        ->name('evidencias.store');

    Route::put('/evidencias/{id}', [EvidenciaController::class, 'update'])
        ->name('evidencias.update');

    Route::delete('/evidencias/{id}', [EvidenciaController::class, 'destroy'])
        ->name('evidencias.destroy');

    // Cancelaciones y modificaciones
    Route::get('/cancelacion', [SolicitudController::class, 'cancelacionView'])
        ->name('vochito.cancelacion');

    Route::get('/modificacion', [SolicitudController::class, 'modificacionView'])
        ->name('vochito.modificacion');

    // Administración de usuarios: solo administradores
    Route::middleware('admin')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])
            ->name('usuarios.index');

        Route::get('/usuarios/{usuario}/editar', [UserController::class, 'edit'])
            ->name('usuarios.edit');

        Route::put('/usuarios/{usuario}', [UserController::class, 'update'])
            ->name('usuarios.update');

        Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])
            ->name('usuarios.destroy');
    });
});
