@extends('layouts.app')

@section('title', 'Editar producto')

@section('header')
    <h1 class="display-5 fw-bold">Editar producto</h1>
    <p class="lead">{{ $producto->nombre }}</p>
@endsection

@section('content')
<div class="card shadow-sm border-0 p-4">
    <h2 class="h4 mb-3">Datos del producto</h2>
    <form method="POST" action="{{ route('productos.update', $producto) }}" novalidate>
        @csrf
        @method('PUT')
        <div class="row g-3">@include('productos._form')</div>
    </form>
</div>
@endsection
