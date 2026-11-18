<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;

class IncidenciaController extends Controller
{
    // Mostrar todas las incidencias
    public function index()
    {
        $incidencias = Incidencia::latest()->get();

        return view('vochito.incidencias', compact('incidencias'));
    }

    // Registrar una incidencia
    public function store(Request $request)
    {
        $datos = $request->validate([
            'orden_compra' => [
                'required',
                'regex:/^[Pp][Oo]-[0-9]{4}-[0-9]{4}$/'
            ],
            'tipo_incidencia' => [
                'required',
                'in:Retraso,Falla de maquinaria,Falta de materia prima'
            ],
            'causa' => 'required|string|min:5',
            'nueva_fecha_estimada' => 'required|date|after:now',
        ]);

        Incidencia::create($datos);

        return redirect()
            ->route('vochito.incidencias')
            ->with('success', 'Incidencia registrada correctamente.');
    }

    // Actualizar una incidencia
    public function update(Request $request, $id)
    {
        $incidencia = Incidencia::findOrFail($id);

        $datos = $request->validate([
            'orden_compra' => [
                'required',
                'regex:/^[Pp][Oo]-[0-9]{4}-[0-9]{4}$/'
            ],
            'tipo_incidencia' => [
                'required',
                'in:Retraso,Falla de maquinaria,Falta de materia prima'
            ],
            'causa' => 'required|string|min:5',
            'nueva_fecha_estimada' => 'required|date|after:now',
        ]);

        $incidencia->update($datos);

        return redirect()
            ->route('vochito.incidencias')
            ->with('success', 'Incidencia actualizada correctamente.');
    }

    // Eliminar una incidencia
    public function destroy($id)
    {
        $incidencia = Incidencia::findOrFail($id);

        $incidencia->delete();

        return redirect()
            ->route('vochito.incidencias')
            ->with('success', 'Incidencia eliminada correctamente.');
    }
}
