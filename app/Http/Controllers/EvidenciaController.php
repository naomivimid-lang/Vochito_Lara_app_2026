<?php

namespace App\Http\Controllers;

use App\Models\Evidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenciaController extends Controller
{
    // Mostrar todas las evidencias
    public function index()
    {
        $evidencias = Evidencia::latest()->get();

        return view('vochito.evidencias', compact('evidencias'));
    }

    // Registrar evidencia
    public function store(Request $request)
    {
        $request->validate([
            'orden_compra' => ['required', 'regex:/^[Pp][Oo]-[0-9]{4}-[0-9]{4}$/'],
            'recibe' => 'required|string|min:3|max:150',
            'fecha_entrega' => 'required|date',
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'comentarios' => 'nullable|string|max:1000',
        ]);

        $rutaFoto = $request->file('foto')->store('evidencias', 'public');

        Evidencia::create([
            'orden_compra' => strtoupper($request->orden_compra),
            'recibe' => $request->recibe,
            'fecha_entrega' => $request->fecha_entrega,
            'foto' => $rutaFoto,
            'comentarios' => $request->comentarios,
        ]);

        return redirect()->route('vochito.evidencias')
            ->with('success', 'Evidencia registrada correctamente.');
    }

    // Actualizar evidencia
    public function update(Request $request, $id)
    {
        $evidencia = Evidencia::findOrFail($id);

        $request->validate([
            'orden_compra' => ['required', 'regex:/^[Pp][Oo]-[0-9]{4}-[0-9]{4}$/'],
            'recibe' => 'required|string|min:3|max:150',
            'fecha_entrega' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'comentarios' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete($evidencia->foto);

            $rutaFoto = $request->file('foto')->store('evidencias', 'public');
            $evidencia->foto = $rutaFoto;
        }

        $evidencia->orden_compra = strtoupper($request->orden_compra);
        $evidencia->recibe = $request->recibe;
        $evidencia->fecha_entrega = $request->fecha_entrega;
        $evidencia->comentarios = $request->comentarios;

        $evidencia->save();

        return redirect()->route('vochito.evidencias')
            ->with('success', 'Evidencia actualizada correctamente.');
    }

    // Eliminar evidencia
    public function destroy($id)
    {
        $evidencia = Evidencia::findOrFail($id);

        Storage::disk('public')->delete($evidencia->foto);
        $evidencia->delete();

        return redirect()->route('vochito.evidencias')
            ->with('success', 'Evidencia eliminada correctamente.');
    }
}
