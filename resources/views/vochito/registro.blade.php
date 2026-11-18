@extends('layouts.app')

@section('title', 'Crear cuenta')

@section('header')
    <h1 class="display-5 fw-bold">Crear cuenta</h1>
    <p class="lead">Regístrate para hacer y consultar tus pedidos en Bochito.</p>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 p-4">
            <h2 class="h4 text-center mb-4"><i class="fa-solid fa-user-plus"></i> Datos de tu cuenta</h2>

            <form method="POST" action="{{ route('registro.store') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Nombre completo <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" minlength="3" maxlength="120" required autocomplete="name"
                           class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Correo electrónico <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" required autocomplete="email" placeholder="ejemplo@correo.com"
                           class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Contraseña <span class="text-danger">*</span></label>
                    <input type="password" id="password" name="password" minlength="8" required autocomplete="new-password"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Mínimo 8 caracteres.</div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-bold">Confirmar contraseña <span class="text-danger">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                           class="form-control">
                </div>

                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-user-plus"></i> Crear cuenta</button>
            </form>

            <hr class="my-4">
            <p class="text-center mb-0">
                ¿Ya tienes cuenta? <a href="{{ route('vochito.iniciosesion') }}">Inicia sesión</a>
            </p>
        </div>
    </div>
</div>
@endsection
