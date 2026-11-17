@extends('layouts.app')

@section('title', 'Productos')

@section('header')
    <h1 class="display-4 fw-bold">Productos</h1>
    <p class="lead">Administra los productos disponibles para registrar pedidos.</p>
@endsection

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <form method="GET" action="{{ route('productos.index') }}" class="d-flex gap-2" role="search">
        <div>
            <label for="buscar" class="form-label fw-bold">Buscar por nombre</label>
            <input type="search" id="buscar" name="buscar" class="form-control"
                   placeholder="Ej. pan" value="{{ request('buscar') }}">
        </div>
        <div class="d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            @if (request('buscar'))
                <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </div>
    </form>
    <a href="{{ route('productos.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nuevo producto</a>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Stock</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $producto)
                    <tr>
                        <td class="fw-bold">{{ $producto->nombre }}</td>
                        <td class="text-muted small">{{ \Illuminate\Support\Str::limit($producto->descripcion, 60) }}</td>
                        <td class="text-end">${{ number_format($producto->precio, 2) }}</td>
                        <td class="text-end">{{ $producto->stock }}</td>
                        <td>
                            @if ($producto->activo)
                                <span class="badge text-bg-success">Activo</span>
                            @else
                                <span class="badge text-bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-pen"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('productos.destroy', $producto) }}" class="d-inline"
                                  data-confirm="¿Eliminar «{{ $producto->nombre }}»? Esta acción no se puede deshacer.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No hay productos{{ request('buscar') ? ' con ese nombre' : '' }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $productos->links() }}</div>
@endsection
