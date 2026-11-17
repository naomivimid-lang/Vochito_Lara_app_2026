@extends('layouts.app')

@section('title', 'Contacto - Bochito Envíos y Ventas')

@section('header')
  <header class="bg-primary text-white text-center py-5">
    <div class="container py-3">
      <h1 class="display-4 fw-bold">Ponte en Contacto</h1>
      <p class="lead">¿Tienes dudas o quieres colaborar con artesanos locales? Escríbenos.</p>
    </div>
  </header>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0 p-4">
        <h3 class="mb-4 text-center text-dark">Formulario de Contacto</h3>

        <form class="needs-validation" method="POST" action="{{ route('contacto.store') }}" novalidate>
          @csrf
          <div class="mb-3">
            <label for="nombre" class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
            <input
              type="text"
              class="form-control @error('nombre') is-invalid @enderror"
              id="nombre"
              name="nombre"
              placeholder="Tu nombre y apellidos"
              value="{{ old('nombre') }}"
              required
            />
            @error('nombre')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="invalid-feedback">Ingresa tu nombre completo.</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="correo" class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
            <input
              type="email"
              class="form-control @error('correo') is-invalid @enderror"
              id="correo"
              name="correo"
              placeholder="ejemplo@correo.com"
              value="{{ old('correo') }}"
              required
            />
            @error('correo')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="invalid-feedback">Ingresa un correo electrónico válido.</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="telefono" class="form-label fw-bold">Número de Teléfono</label>
            <input
              type="tel"
              class="form-control @error('telefono') is-invalid @enderror"
              id="telefono"
              name="telefono"
              placeholder="10 dígitos"
              value="{{ old('telefono') }}"
            />
            @error('telefono')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="asunto" class="form-label fw-bold">Asunto <span class="text-danger">*</span></label>
            <input
              type="text"
              class="form-control @error('asunto') is-invalid @enderror"
              id="asunto"
              name="asunto"
              placeholder="Motivo de tu mensaje"
              value="{{ old('asunto') }}"
              required
            />
            @error('asunto')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="invalid-feedback">Ingresa el asunto del mensaje.</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="mensaje" class="form-label fw-bold">Mensaje <span class="text-danger">*</span></label>
            <textarea
              class="form-control @error('mensaje') is-invalid @enderror"
              id="mensaje"
              name="mensaje"
              rows="4"
              placeholder="Escribe aquí tus dudas o comentarios..."
              required
            >{{ old('mensaje') }}</textarea>
            @error('mensaje')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="invalid-feedback">Por favor escribe un mensaje detallado.</div>
            @enderror
          </div>

          <div class="d-flex justify-content-between pt-3">
            <button type="reset" class="btn btn-outline-secondary px-4">Limpiar</button>
            <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-paper-plane me-1"></i> Enviar Mensaje</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
