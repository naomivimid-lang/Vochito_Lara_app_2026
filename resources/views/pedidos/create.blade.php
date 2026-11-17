@extends('layouts.app')

@section('title', 'Registrar pedido')

@section('header')
    <h1 class="display-5 fw-bold">Registrar pedido</h1>
    <p class="lead">Todo pedido nuevo inicia con el estatus «Orden recibida».</p>
@endsection

@section('content')
<div class="card shadow-sm border-0 p-4">
    <h2 class="h4 mb-3">Nuevo pedido</h2>
    <form method="POST" action="{{ route('pedidos.store') }}" novalidate>
        @csrf
        <div class="row g-3">@include('pedidos._form')</div>
    </form>
</div>
@endsection
