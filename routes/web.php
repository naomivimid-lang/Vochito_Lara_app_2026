
<?php

use Illuminate\Support\Facades\Route;

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
