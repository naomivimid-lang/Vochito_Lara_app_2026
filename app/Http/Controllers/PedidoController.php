<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PedidoController extends Controller
{
    // Obtener los pedidos guardados en PostgreSQL
    public function index()
    {
        $pedidos = Pedido::orderByDesc('created_at')->get();

        return response()->json($pedidos);
    }

    // Registrar un pedido nuevo
    public function store(Request $request)
    {
        $datos = $request->validate([
            'cliente' => 'required|string|min:3|max:255',
            'producto' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:1',
            'entrega' => 'required|date',
        ]);

        $pedido = Pedido::create([
            'po' => 'PO-' . date('Y') . '-' . strtoupper(Str::random(6)),
            'cliente' => $datos['cliente'],
            'producto' => $datos['producto'],
            'cantidad' => $datos['cantidad'],
            'entrega' => $datos['entrega'],
            'estatus' => 'Orden recibida',
            'incidencias' => [],
        ]);

        return response()->json([
            'mensaje' => 'Pedido registrado correctamente.',
            'pedido' => $pedido,
        ], 201);
    }

    // Registrar una incidencia y actualizar la fecha de entrega
    public function guardarIncidencia(Request $request, Pedido $pedido)
    {
        $datos = $request->validate([
            'tipo' => 'required|string|max:255',
            'causa' => 'required|string|min:5',
            'nuevaFecha' => 'required|date',
        ]);

        $incidencias = $pedido->incidencias ?? [];

        $incidencias[] = [
            'tipo' => $datos['tipo'],
            'causa' => $datos['causa'],
            'nuevaFecha' => $datos['nuevaFecha'],
            'registrada' => now()->toISOString(),
        ];

        $pedido->update([
            'incidencias' => $incidencias,
            'entrega' => $datos['nuevaFecha'],
        ]);

        return response()->json([
            'mensaje' => 'Incidencia registrada correctamente.',
            'pedido' => $pedido->fresh(),
        ]);
    }
}
