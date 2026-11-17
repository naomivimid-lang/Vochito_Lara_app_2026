<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\EvidenciaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ContactoController;

/*
|--------------------------------------------------------------------------
| Web Routes - Vochito Laravel
|--------------------------------------------------------------------------
| Rutas RESTful organizadas mediante Route::resource() y rutas complementarias.
*/

// Página de inicio
Route::get('/', function () {
    return view('vochito.inicio');
})->name('vochito.inicio');

// ==========================================
// RUTAS RESTful (Route::resource)
// ==========================================

// 1. Pedidos (CRUD RESTful completo)
Route::resource('pedidos', PedidoController::class);
Route::get('/pedidos-legacy', function () {
    return redirect()->route('pedidos.index');
})->name('vochito.pedidos');

// 2. Solicitudes (Cancelaciones y Modificaciones)
Route::resource('solicitudes', SolicitudController::class);
Route::patch('/solicitudes/{id}/estado', [SolicitudController::class, 'updateEstado'])->name('vochito.solicitudes.estado');

// Rutas de conveniencia para vistas específicas de solicitudes
Route::get('/cancelacion', [SolicitudController::class, 'cancelacionView'])->name('solicitudes.cancelacion');
Route::get('/cancelacion-alias', function() { return redirect()->route('solicitudes.cancelacion'); })->name('vochito.cancelacion');

Route::get('/modificacion', [SolicitudController::class, 'modificacionView'])->name('solicitudes.modificacion');
Route::get('/modificacion-alias', function() { return redirect()->route('solicitudes.modificacion'); })->name('vochito.modificacion');

// 3. Incidencias
Route::resource('incidencias', IncidenciaController::class);
Route::get('/incidencias-alias', function() { return redirect()->route('incidencias.create'); })->name('vochito.incidencias');

// 4. Evidencias de entrega
Route::resource('evidencias', EvidenciaController::class);
Route::get('/evidencias-alias', function() { return redirect()->route('evidencias.create'); })->name('vochito.evidencias');

// 5. Reporte de Desempeño
Route::resource('reportes', ReporteController::class)->only(['index', 'store']);
Route::get('/reporte-desempeno', [ReporteController::class, 'index'])->name('vochito.reporte_desempeno');
Route::post('/reporte-desempeno/generar', [ReporteController::class, 'generar'])->name('vochito.reporte.generar');

// 6. Contacto
Route::get('/contacto', [ContactoController::class, 'create'])->name('vochito.contacto');
Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');

// ==========================================
// Vistas informativas y complementarias
// ==========================================
Route::view('/nosotros', 'vochito.nosotros')->name('vochito.nosotros');
Route::view('/servicios', 'vochito.servicios')->name('vochito.servicios');
Route::view('/catalogo', 'vochito.catalogo')->name('vochito.catalogo');
Route::view('/busquedas', 'vochito.busquedas')->name('vochito.busquedas');
Route::view('/productos', 'vochito.productos')->name('vochito.productos');
Route::view('/historial', 'vochito.historial')->name('vochito.historial');
Route::view('/notificaciones', 'vochito.notificaciones')->name('vochito.notificaciones');
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
