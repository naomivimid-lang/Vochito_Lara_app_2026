<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarEstatusRequest;
use App\Http\Requests\StorePedidoRequest;
use App\Http\Requests\UpdatePedidoRequest;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $pedidos = Pedido::with('producto')
            ->when($request->buscar, fn ($q, $b) => $q->where('cliente', 'ilike', "%{$b}%"))
            ->when($request->estatus, fn ($q, $e) => $q->where('estatus', $e))
            ->when($request->desde, fn ($q, $d) => $q->whereDate('entrega', '>=', $d))
            ->when($request->hasta, fn ($q, $h) => $q->whereDate('entrega', '<=', $h))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        $productos = $this->productosActivos();

        return view('pedidos.create', compact('productos'));
    }

    public function store(StorePedidoRequest $request)
    {
        Pedido::create($request->validated());

        return redirect()->route('pedidos.index')->with('ok', 'Pedido registrado correctamente.');
    }

    public function edit(Pedido $pedido)
    {
        $productos = $this->productosActivos($pedido->producto_id);

        return view('pedidos.edit', compact('pedido', 'productos'));
    }

    public function update(UpdatePedidoRequest $request, Pedido $pedido)
    {
        $pedido->update($request->validated());

        return redirect()->route('pedidos.index')->with('ok', 'Pedido actualizado.');
    }

    public function destroy(Pedido $pedido)
    {
        $pedido->delete();

        return redirect()->route('pedidos.index')->with('ok', 'Pedido eliminado.');
    }

    public function actualizarEstatus(ActualizarEstatusRequest $request, Pedido $pedido)
    {
        $pedido->update($request->validated());

        return back()->with('ok', "Estatus actualizado a «{$pedido->estatus}».");
    }

    // Al editar se incluye el producto actual del pedido aunque ya esté inactivo
    private function productosActivos(?int $incluirId = null)
    {
        return Producto::where('activo', true)
            ->when($incluirId, fn ($q, $id) => $q->orWhere('id', $id))
            ->orderBy('nombre')
            ->get();
    }
}
