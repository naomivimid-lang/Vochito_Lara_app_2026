@extends('layouts.app')

@section('title', 'Catálogo')

@section('header')
    <h1 class="display-5 fw-bold">Catálogo de productos</h1>
    <p class="lead">Pan, frutas, verduras, artesanías y más, directo de las tiendas locales de tu comunidad.</p>
@endsection

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <p class="text-muted mb-0">{{ $productos->total() }} productos disponibles de tiendas locales.</p>
    <a class="btn btn-primary" href="{{ route('pedidos.create') }}"><i class="fa-solid fa-file-circle-plus"></i> Hacer un pedido</a>
</div>

<div class="row g-4">
    @forelse ($productos as $p)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 p-3">
                <div class="card-body d-flex flex-column">
                    <div class="icono-circulo fondo-azul rounded-circle d-flex align-items-center justify-content-center mb-3">
                        <i class="fa-solid fa-box fs-4 icono-azul-oscuro" aria-hidden="true"></i>
                    </div>
                    <span class="small fw-bold texto-verde">
                        {{ $p->stock > 0 ? "{$p->stock} disponibles" : 'Agotado' }}
                    </span>
                    <h2 class="h5 mt-1">{{ $p->nombre }}</h2>
                    <p class="text-muted mb-3">{{ $p->descripcion }}</p>
                    <strong class="fs-5 mt-auto">${{ number_format($p->precio, 2) }}</strong>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center text-muted">Aún no hay productos en el catálogo.</p>
    @endforelse
</div>

<div class="mt-4">{{ $productos->links() }}</div>
@endsection
