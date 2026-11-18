@extends('layouts.app')

@section('title', 'Modificar pedido')

@section('header')
    <h1 class="display-5 fw-bold">Modificar pedido</h1>
    <p class="lead">PO-{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }} · {{ $pedido->cliente }}</p>
@endsection

@section('content')
<div class="card shadow-sm border-0 p-4">
    <h2 class="h4 mb-3">Datos del pedido</h2>
    <form method="POST" action="{{ route('pedidos.update', $pedido) }}" novalidate>
        @csrf
        @method('PUT')
        <div class="row g-3">@include('pedidos._form')</div>
    </form>
</div>
@endsection
