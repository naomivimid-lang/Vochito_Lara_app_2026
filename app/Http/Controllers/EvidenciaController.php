<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvidenciaRequest;
use App\Models\Evidencia;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EvidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $evidencias = [];
        if (Schema::hasTable('evidencias')) {
            $evidencias = Evidencia::with('pedido')->orderBy('created_at', 'desc')->get();
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'evidencias' => $evidencias]);
        }

        return view('vochito.evidencias', compact('evidencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vochito.evidencias');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEvidenciaRequest $request)
    {
        $validated = $request->validated();
        $po = strtoupper(trim($validated['idPedidoEvidencia']));

        $fotoPath = null;
        if ($request->hasFile('fotoEntrega')) {
            $fotoPath = $request->file('fotoEntrega')->store('evidencias', 'public');
        }

        if (Schema::hasTable('evidencias')) {
            $evidencia = Evidencia::create([
                'po' => $po,
                'recibe' => $validated['recibe'],
                'fecha_entrega' => $validated['fechaEntrega'],
                'foto_path' => $fotoPath,
                'comentarios' => $validated['comentarios'] ?? null,
            ]);

            // Actualizar estatus y entrega real del pedido
            if (Schema::hasTable('pedidos')) {
                $pedido = Pedido::where('po', $po)->first();
                if ($pedido) {
                    $pedido->entrega_real = $validated['fechaEntrega'];
                    $pedido->estatus = 'Entregado a tiempo';

                    // Comparar si fue a tiempo o retrasado
                    if ($pedido->entrega_estimada && $pedido->entrega_real > $pedido->entrega_estimada) {
                        $pedido->desempeno = 'retrasado';
                        $pedido->estatus = 'Retrasado';
                    } else {
                        $pedido->desempeno = 'a_tiempo';
                    }
                    $pedido->save();
                }
            }

            $mensaje = "Evidencia registrada en el expediente de la orden {$po}.";

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $mensaje,
                    'evidencia' => $evidencia,
                ], 201);
            }

            return redirect()->route('evidencias.create')->with('success', $mensaje);
        }

        return redirect()->route('evidencias.create')->with('success', "Evidencia registrada en el expediente del pedido {$po}.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $evidencia = Evidencia::with('pedido')->findOrFail($id);
        return response()->json(['success' => true, 'evidencia' => $evidencia]);
    }
}
