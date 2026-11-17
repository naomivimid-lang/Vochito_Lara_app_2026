<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productos = Producto::query()
            ->when($request->buscar, fn ($q, $b) => $q->where('nombre', 'ilike', "%{$b}%"))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(StoreProductoRequest $request)
    {
        Producto::create($request->validated());
        return redirect()->route('productos.index')->with('ok', 'Producto registrado correctamente.');
    }

    public function edit(Producto $producto)          // route model binding
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());
        return redirect()->route('productos.index')->with('ok', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->pedidos()->exists()) {
            return back()->with('error', 'No se puede eliminar: el producto tiene pedidos registrados.');
        }
        $producto->delete();
        return redirect()->route('productos.index')->with('ok', 'Producto eliminado.');
    }
}
