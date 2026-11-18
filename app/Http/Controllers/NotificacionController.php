<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    // Mostrar todas las notificaciones
    public function index()
    {
        $notificaciones = Notificacion::latest()->get();

        return view('vochito.notificaciones', compact('notificaciones'));
    }

    // Registrar una notificación
    public function store(Request $request)
    {
        $datos = $request->validate([
            'orden_compra' => ['required', 'regex:/^[Pp][Oo]-[0-9]{4}-[0-9]{4}$/'],
            'titulo' => 'required|string|max:255',
            'mensaje' => 'required|string|min:5',
            'tipo' => 'required|in:Entrega,Cambio de estatus,Incidencia',
        ]);

        Notificacion::create($datos);

        return redirect()->route('vochito.notificaciones')
            ->with('success', 'Notificación registrada correctamente.');
    }

    // Actualizar una notificación
    public function update(Request $request, $id)
    {
        $datos = $request->validate([
            'orden_compra' => ['required', 'regex:/^[Pp][Oo]-[0-9]{4}-[0-9]{4}$/'],
            'titulo' => 'required|string|max:255',
            'mensaje' => 'required|string|min:5',
            'tipo' => 'required|in:Entrega,Cambio de estatus,Incidencia',
            'leida' => 'required|boolean',
        ]);

        $notificacion = Notificacion::findOrFail($id);
        $notificacion->update($datos);

        return redirect()->route('vochito.notificaciones')
            ->with('success', 'Notificación actualizada correctamente.');
    }

    // Eliminar una notificación
    public function destroy($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $notificacion->delete();

        return redirect()->route('vochito.notificaciones')
            ->with('success', 'Notificación eliminada correctamente.');
    }
}
