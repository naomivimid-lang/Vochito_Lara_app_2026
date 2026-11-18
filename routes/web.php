<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\EvidenciaController;

// Página principal
Route::get('/', function () {
    return view('vochito.inicio');
})->name('vochito.inicio');

// Páginas de Vochito
Route::view('/nosotros', 'vochito.nosotros')->name('vochito.nosotros');
Route::view('/servicios', 'vochito.servicios')->name('vochito.servicios');
Route::view('/catalogo', 'vochito.catalogo')->name('vochito.catalogo');
Route::view('/busquedas', 'vochito.busquedas')->name('vochito.busquedas');
Route::view('/pedidos', 'vochito.pedidos')->name('vochito.pedidos');
Route::view('/productos', 'vochito.productos')->name('vochito.productos');
Route::view('/historial', 'vochito.historial')->name('vochito.historial');

// CRUD de Notificaciones
Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('vochito.notificaciones');
Route::post('/notificaciones', [NotificacionController::class, 'store'])->name('notificaciones.store');
Route::put('/notificaciones/{id}', [NotificacionController::class, 'update'])->name('notificaciones.update');
Route::delete('/notificaciones/{id}', [NotificacionController::class, 'destroy'])->name('notificaciones.destroy');
// CRUD de Incidencias
Route::get('/incidencias', [IncidenciaController::class, 'index'])->name('vochito.incidencias');
Route::post('/incidencias', [IncidenciaController::class, 'store'])->name('incidencias.store');
Route::put('/incidencias/{id}', [IncidenciaController::class, 'update'])->name('incidencias.update');
Route::delete('/incidencias/{id}', [IncidenciaController::class, 'destroy'])->name('incidencias.destroy');
// CRUD de Evidencias
Route::get('/evidencias', [EvidenciaController::class, 'index'])->name('vochito.evidencias');
Route::post('/evidencias', [EvidenciaController::class, 'store'])->name('evidencias.store');
Route::put('/evidencias/{id}', [EvidenciaController::class, 'update'])->name('evidencias.update');
Route::delete('/evidencias/{id}', [EvidenciaController::class, 'destroy'])->name('evidencias.destroy');
// Otras páginas
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
