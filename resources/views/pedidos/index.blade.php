@extends('layouts.app')

@section('title', 'Pedidos')

@section('header')
    <h1 class="display-4 fw-bold">Pedidos</h1>
    <p class="lead">Busca pedidos por cliente, fechas o estatus y actualiza su etapa del proceso.</p>
@endsection

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('pedidos.create') }}" class="btn btn-primary"><i class="fa-solid fa-file-circle-plus"></i> Registrar pedido</a>
</div>

<form method="GET" action="{{ route('pedidos.index') }}" class="row g-3 mb-4 align-items-end" role="search">
    <div class="col-md-6 col-lg-3">
        <label for="buscar" class="form-label fw-bold">Cliente</label>
        <input type="search" id="buscar" name="buscar" class="form-control" placeholder="Nombre del cliente" value="{{ request('buscar') }}">
    </div>
    <div class="col-md-6 col-lg-2">
        <label for="filtroEstatus" class="form-label fw-bold">Estatus</label>
        <select id="filtroEstatus" name="estatus" class="form-select">
            <option value="">Todos</option>
            @foreach (\App\Models\Pedido::ESTATUS as $e)
                <option value="{{ $e }}" @selected(request('estatus') === $e)>{{ $e }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 col-lg-2">
        <label for="desde" class="form-label fw-bold">Entrega desde</label>
        <input type="date" id="desde" name="desde" class="form-control" value="{{ request('desde') }}">
    </div>
    <div class="col-md-6 col-lg-2">
        <label for="hasta" class="form-label fw-bold">Entrega hasta</label>
        <input type="date" id="hasta" name="hasta" class="form-control" value="{{ request('hasta') }}">
    </div>
    <div class="col-lg-3 d-flex gap-2">
        <button type="submit" class="btn btn-outline-primary flex-fill"><i class="fa-solid fa-filter"></i> Filtrar</button>
        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
    </div>
</form>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>PO</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th class="text-end">Cantidad</th>
                    <th>Entrega estimada</th>
                    <th>Estatus</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pedidos as $pedido)
                    <tr>
                        <td class="fw-bold text-nowrap">PO-{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $pedido->cliente }}</td>
                        <td>{{ $pedido->producto->nombre }}</td>
                        <td class="text-end">{{ $pedido->cantidad }}</td>
                        <td class="text-nowrap">{{ $pedido->entrega->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge text-bg-{{ \App\Models\Pedido::COLOR_ESTATUS[$pedido->estatus] ?? 'secondary' }} mb-1">{{ $pedido->estatus }}</span>
                            <form method="POST" action="{{ route('pedidos.estatus', $pedido) }}">
                                @csrf
                                @method('PATCH')
                                <label for="estatus-{{ $pedido->id }}" class="visually-hidden">Cambiar estatus de PO-{{ $pedido->id }}</label>
                                <select id="estatus-{{ $pedido->id }}" name="estatus" class="form-select form-select-sm" onchange="this.form.submit()">
                                    @foreach (\App\Models\Pedido::ESTATUS as $e)
                                        <option value="{{ $e }}" @selected($pedido->estatus === $e)>{{ $e }}</option>
                                    @endforeach
                                </select>
                                <noscript><button type="submit" class="btn btn-sm btn-outline-primary mt-1">Actualizar</button></noscript>
                            </form>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('pedidos.edit', $pedido) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-pen"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('pedidos.destroy', $pedido) }}" class="d-inline"
                                  data-confirm="¿Cancelar y eliminar el pedido de {{ $pedido->cliente }}? Esta acción no se puede deshacer.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-ban"></i> Cancelar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No hay pedidos con esos filtros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $pedidos->links() }}</div>
@endsection
