@extends('layouts.app')

@section('title', 'Nuevo producto')

@section('header')
    <h1 class="display-5 fw-bold">Nuevo producto</h1>
    <p class="lead">Agrega un producto para que pueda elegirse en los pedidos.</p>
@endsection

@section('content')
<div class="card shadow-sm border-0 p-4">
    <h2 class="h4 mb-3">Registrar producto</h2>
    <form method="POST" action="{{ route('productos.store') }}" novalidate>
        @csrf
        <div class="row g-3">@include('productos._form')</div>
    </form>
</div>
@endsection
