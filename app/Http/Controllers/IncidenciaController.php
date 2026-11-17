<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncidenciaRequest;
use App\Models\Incidencia;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class IncidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $incidencias = [];
        if (Schema::hasTable('incidencias')) {
            $query = Incidencia::with('pedido');

            if ($request->filled('po')) {
                $query->where('po', 'LIKE', '%' . strtoupper(trim($request->query('po'))) . '%');
            }

            $incidencias = $query->orderBy('created_at', 'desc')->get();
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'incidencias' => $incidencias]);
        }

        return view('vochito.incidencias', compact('incidencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vochito.incidencias');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncidenciaRequest $request)
    {
        $validated = $request->validated();
        $po = strtoupper(trim($validated['idPedido']));

        if (Schema::hasTable('incidencias')) {
            $incidencia = Incidencia::create([
                'po' => $po,
                'tipo_incidencia' => $validated['tipoIncidencia'],
                'causa' => $validated['causa'],
                'nueva_fecha_estimada' => $validated['nuevaFecha'],
                'estatus' => 'Abierta',
            ]);

            // Si hay pedido asociado, actualizar la entrega estimada y marcar como potencialmente retrasado
            if (Schema::hasTable('pedidos')) {
                $pedido = Pedido::where('po', $po)->first();
                if ($pedido) {
                    $pedido->entrega_estimada = $validated['nuevaFecha'];
                    $pedido->desempeno = 'retrasado';
                    $pedido->save();
                }
            }

            $mensaje = "Incidencia para la orden {$po} registrada. Se notificará al cliente la nueva fecha estimada.";

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $mensaje,
                    'incidencia' => $incidencia,
                ], 201);
            }

            return redirect()->route('incidencias.create')->with('success', $mensaje);
        }

        return redirect()->route('incidencias.create')->with('success', "Incidencia registrada correctamente para la orden {$po}.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $incidencia = Incidencia::with('pedido')->findOrFail($id);
        return response()->json(['success' => true, 'incidencia' => $incidencia]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        if (Schema::hasTable('incidencias')) {
            $incidencia = Incidencia::findOrFail($id);
            $incidencia->delete();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Incidencia eliminada.']);
            }

            return back()->with('success', 'Incidencia eliminada correctamente.');
        }

        return back()->with('success', 'Incidencia eliminada.');
    }
}
