<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePedidoRequest;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pedidos = [];
        if (Schema::hasTable('pedidos')) {
            $query = Pedido::with(['solicitudes', 'incidencias', 'evidencia']);

            if ($request->filled('po')) {
                $query->where('po', 'LIKE', '%' . strtoupper(trim($request->query('po'))) . '%');
            }
            if ($request->filled('estatus')) {
                $query->where('estatus', $request->query('estatus'));
            }

            $pedidos = $query->orderBy('created_at', 'desc')->get();
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'pedidos' => $pedidos]);
        }

        return view('vochito.pedidos', compact('pedidos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vochito.pedidos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePedidoRequest $request)
    {
        $validated = $request->validated();

        $po = $validated['po'] ?? null;
        if (!$po) {
            $year = date('Y');
            $count = Schema::hasTable('pedidos') ? Pedido::count() + 1 : 1;
            $po = sprintf('PO-%s-%04d', $year, $count);
        }

        if (Schema::hasTable('pedidos')) {
            $pedido = Pedido::create([
                'po' => $po,
                'cliente' => $validated['cliente'],
                'producto' => $validated['producto'],
                'cantidad' => $validated['cantidad'],
                'entrega_estimada' => $validated['entrega'],
                'estatus' => 'Orden recibida',
                'desempeno' => 'pendiente',
            ]);

            $mensaje = "Pedido {$po} registrado exitosamente.";

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $mensaje,
                    'pedido' => $pedido,
                ], 201);
            }

            return redirect()->route('pedidos.index')->with('success', $mensaje);
        }

        return response()->json([
            'success' => true,
            'message' => "Pedido {$po} recibido correctamente (modo prototipo).",
            'pedido' => array_merge($validated, ['po' => $po, 'estatus' => 'Orden recibida'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Schema::hasTable('pedidos')) {
            return response()->json(['success' => false, 'message' => 'No disponible en modo local'], 404);
        }

        $pedido = is_numeric($id) ? Pedido::with(['solicitudes', 'incidencias', 'evidencias'])->find($id)
                                  : Pedido::with(['solicitudes', 'incidencias', 'evidencias'])->where('po', $id)->firstOrFail();

        return response()->json(['success' => true, 'pedido' => $pedido]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pedido = is_numeric($id) ? Pedido::findOrFail($id) : Pedido::where('po', $id)->firstOrFail();
        return view('vochito.pedidos', compact('pedido'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pedido = is_numeric($id) ? Pedido::findOrFail($id) : Pedido::where('po', $id)->firstOrFail();

        $validated = $request->validate([
            'estatus' => 'nullable|string',
            'cantidad' => 'nullable|integer|min:1',
            'entrega_estimada' => 'nullable|date',
            'entrega_real' => 'nullable|date',
            'desempeno' => 'nullable|in:a_tiempo,retrasado,cancelado,pendiente',
        ]);

        $pedido->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Pedido {$pedido->po} actualizado exitosamente.",
                'pedido' => $pedido
            ]);
        }

        return back()->with('success', "Pedido {$pedido->po} actualizado correctamente.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        if (Schema::hasTable('pedidos')) {
            $pedido = is_numeric($id) ? Pedido::findOrFail($id) : Pedido::where('po', $id)->firstOrFail();
            $po = $pedido->po;
            $pedido->delete();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => "Pedido {$po} eliminado."]);
            }

            return redirect()->route('pedidos.index')->with('success', "Pedido {$po} eliminado con éxito.");
        }

        return back()->with('success', "Pedido eliminado.");
    }
}
