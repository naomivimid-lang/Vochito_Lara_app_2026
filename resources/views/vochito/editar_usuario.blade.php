@extends('layouts.app')

@section('title', 'Editar usuario')

@section('header')
    <h1 class="display-5 fw-bold">Editar usuario</h1>
    <p class="lead">{{ $usuario->email }}</p>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 p-4">
            <form action="{{ route('usuarios.update', $usuario) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Nombre completo <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" minlength="3" maxlength="120" required
                           class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $usuario->name) }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Correo electrónico <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" required
                           class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $usuario->email) }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Nueva contraseña</label>
                    <input type="password" id="password" name="password" minlength="8" autocomplete="new-password"
                           placeholder="Dejar vacío para conservar la actual"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check form-switch mb-4">
                    <input type="checkbox" id="is_admin" name="is_admin" value="1" role="switch" class="form-check-input"
                           @checked(old('is_admin', $usuario->is_admin))
                           @disabled($usuario->is(auth()->user()))>
                    <label for="is_admin" class="form-check-label">Administrador</label>
                    @if ($usuario->is(auth()->user()))
                        <div class="form-text">No puedes quitarte el permiso de administrador a ti mismo.</div>
                    @endif
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4">Regresar</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk"></i> Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
