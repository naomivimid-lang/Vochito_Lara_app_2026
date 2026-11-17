@extends('layouts.app')

@section('title', 'Evidencia de entrega - Bochito Envíos y Ventas')

@section('header')
  <header class="bg-primary text-white text-center py-5">
    <div class="container py-3">
      <h1 class="display-4 fw-bold">Evidencia de Entrega</h1>
      <p class="lead">Registra quién recibió el pedido, la fecha, la hora y la fotografía de la entrega.</p>
    </div>
  </header>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm border-0 p-4">
        <form class="needs-validation" method="POST" action="{{ route('evidencias.store') }}" enctype="multipart/form-data" novalidate>
          @csrf
          <div class="mb-3">
            <label for="idPedidoEvidencia" class="form-label fw-bold">Orden de compra (PO) <span class="text-danger">*</span></label>
            <input
              type="text"
              class="form-control text-uppercase @error('idPedidoEvidencia') is-invalid @enderror"
              id="idPedidoEvidencia"
              name="idPedidoEvidencia"
              placeholder="Ej. PO-2026-0001"
              pattern="[Pp][Oo]-[0-9]{4}-[0-9]{4}"
              value="{{ old('idPedidoEvidencia') }}"
              required
            >
            @error('idPedidoEvidencia')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="invalid-feedback">Escribe la orden de compra con el formato PO-2026-0001.</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="recibe" class="form-label fw-bold">Nombre de quien recibe <span class="text-danger">*</span></label>
            <input
              type="text"
              class="form-control @error('recibe') is-invalid @enderror"
              id="recibe"
              name="recibe"
              minlength="3"
              value="{{ old('recibe') }}"
              required
            >
            @error('recibe')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="invalid-feedback">Escribe el nombre de quien recibió el pedido.</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="fechaEntrega" class="form-label fw-bold">Fecha y hora de entrega <span class="text-danger">*</span></label>
            <input
              type="datetime-local"
              class="form-control @error('fechaEntrega') is-invalid @enderror"
              id="fechaEntrega"
              name="fechaEntrega"
              value="{{ old('fechaEntrega') }}"
              required
            >
            @error('fechaEntrega')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="invalid-feedback">Elige la fecha y hora de la entrega.</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="fotoEntrega" class="form-label fw-bold">Fotografía de la entrega</label>
            <input class="form-control @error('fotoEntrega') is-invalid @enderror" type="file" id="fotoEntrega" name="fotoEntrega" accept="image/*">
            @error('fotoEntrega')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="form-text">Adjunta una foto o comprobante visual de la entrega (opcional).</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="comentarios" class="form-label fw-bold">Comentarios adicionales</label>
            <textarea
              class="form-control @error('comentarios') is-invalid @enderror"
              id="comentarios"
              name="comentarios"
              rows="3"
              placeholder="Referencias del domicilio, observaciones, etc."
            >{{ old('comentarios') }}</textarea>
            @error('comentarios')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 mt-3">
            <i class="fa-solid fa-cloud-arrow-up me-1"></i> Registrar evidencia
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection
