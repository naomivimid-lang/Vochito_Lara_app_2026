
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// ==========================================
// PÁGINA PRINCIPAL
// ==========================================

Route::get('/', function () {
    return view('vochito.inicio');
})->name('vochito.inicio');

// ==========================================
// PÁGINAS DE VOCHITO
// ==========================================

Route::view('/nosotros', 'vochito.nosotros')->name('vochito.nosotros');
Route::view('/servicios', 'vochito.servicios')->name('vochito.servicios');
Route::view('/catalogo', 'vochito.catalogo')->name('vochito.catalogo');
Route::view('/busquedas', 'vochito.busquedas')->name('vochito.busquedas');
Route::view('/pedidos', 'vochito.pedidos')->name('vochito.pedidos');
Route::view('/productos', 'vochito.productos')->name('vochito.productos');
Route::view('/historial', 'vochito.historial')->name('vochito.historial');
Route::view('/notificaciones', 'vochito.notificaciones')->name('vochito.notificaciones');
Route::view('/incidencias', 'vochito.incidencias')->name('vochito.incidencias');
Route::view('/evidencias', 'vochito.evidencias')->name('vochito.evidencias');
Route::view('/cancelacion', 'vochito.cancelacion')->name('vochito.cancelacion');
Route::view('/modificacion', 'vochito.modificacion')->name('vochito.modificacion');
Route::view('/reporte-desempeno', 'vochito.reporte_desempeno')->name('vochito.reporte_desempeno');
Route::view('/contacto', 'vochito.contacto')->name('vochito.contacto');
Route::view('/ayuda', 'vochito.ayuda')->name('vochito.ayuda');
Route::view('/privacidad', 'vochito.privacidad')->name('vochito.privacidad');
Route::view('/requerimientos', 'vochito.rnf')->name('vochito.rnf');
Route::view('/rnf03', 'vochito.RNF03')->name('vochito.rnf03');
Route::view('/rnf04', 'vochito.RNF04')->name('vochito.rnf04');
Route::view('/rnf05', 'vochito.RNF05')->name('vochito.rnf05');
Route::view('/rnf06', 'vochito.RNF06')->name('vochito.rnf06');
Route::view('/tiempo-respuesta', 'vochito.tiempo_respuesta')->name('vochito.tiempo_respuesta');
Route::view('/iniciar-sesion', 'vochito.iniciosesion')->name('vochito.iniciosesion');
Route::view('/indice', 'vochito.index')->name('vochito.index');

// ==========================================
// REGISTRO DE USUARIOS
// ==========================================

// Mostrar formulario de registro
Route::get('/registro', function () {
    return view('vochito.registro');
})->name('registro');

// Guardar nuevo usuario
Route::post('/registro', [AuthController::class, 'register'])
    ->name('registro.store');

// ==========================================
// AUTENTICACIÓN
// ==========================================

// Iniciar sesión
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

// Cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ==========================================
// ADMINISTRACIÓN DE USUARIOS
// SOLO ADMINISTRADORES
// ==========================================

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/usuarios', [UserController::class, 'index'])
        ->name('usuarios.index');

    Route::get('/usuarios/{id}/editar', [UserController::class, 'edit'])
        ->name('usuarios.edit');

    Route::put('/usuarios/{id}', [UserController::class, 'update'])
        ->name('usuarios.update');

    Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])
        ->name('usuarios.destroy');

});
