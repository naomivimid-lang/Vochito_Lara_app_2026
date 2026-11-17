@extends('layouts.app')

@section('title', 'Registro de incidencias - Bochito Envíos y Ventas')

@section('header')
  <header class="bg-primary text-white text-center py-5">
    <div class="container py-3">
      <h1 class="display-4 fw-bold">Registro de Incidencias</h1>
      <p class="lead">Registra un retraso, una falla de maquinaria o falta de materia prima, con su causa y la nueva fecha estimada.</p>
    </div>
  </header>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm border-0 p-4">
        <form class="needs-validation" method="POST" action="{{ route('incidencias.store') }}" novalidate>
          @csrf
          <div class="mb-3">
            <label for="idPedido" class="form-label fw-bold">Orden de compra (PO) <span class="text-danger">*</span></label>
            <input
              type="text"
              class="form-control text-uppercase @error('idPedido') is-invalid @enderror"
              id="idPedido"
              name="idPedido"
              placeholder="Ej. PO-2026-0001"
              pattern="[Pp][Oo]-[0-9]{4}-[0-9]{4}"
              value="{{ old('idPedido') }}"
              required
            >
            @error('idPedido')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="invalid-feedback">Escribe la orden de compra con el formato PO-2026-0001.</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="tipoIncidencia" class="form-label fw-bold">Tipo de incidencia <span class="text-danger">*</span></label>
            <select class="form-select @error('tipoIncidencia') is-invalid @enderror" id="tipoIncidencia" name="tipoIncidencia" required>
              <option value="">Selecciona una opción</option>
              <option value="Retraso" {{ old('tipoIncidencia') == 'Retraso' ? 'selected' : '' }}>Retraso</option>
              <option value="Falla de maquinaria" {{ old('tipoIncidencia') == 'Falla de maquinaria' ? 'selected' : '' }}>Falla de maquinaria</option>
              <option value="Falta de materia prima" {{ old('tipoIncidencia') == 'Falta de materia prima' ? 'selected' : '' }}>Falta de materia prima</option>
              <option value="Problemas de logística" {{ old('tipoIncidencia') == 'Problemas de logística' ? 'selected' : '' }}>Problemas de logística</option>
            </select>
            @error('tipoIncidencia')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="invalid-feedback">Selecciona el tipo de incidencia.</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="causa" class="form-label fw-bold">Causa <span class="text-danger">*</span></label>
            <textarea
              class="form-control @error('causa') is-invalid @enderror"
              id="causa"
              name="causa"
              rows="4"
              minlength="5"
              placeholder="Describe la causa de la incidencia..."
              required
            >{{ old('causa') }}</textarea>
            @error('causa')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="invalid-feedback">Describe la causa (mínimo 5 caracteres).</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="nuevaFecha" class="form-label fw-bold">Nueva fecha estimada de entrega <span class="text-danger">*</span></label>
            <input
              type="datetime-local"
              class="form-control @error('nuevaFecha') is-invalid @enderror"
              id="nuevaFecha"
              name="nuevaFecha"
              value="{{ old('nuevaFecha') }}"
              required
            >
            @error('nuevaFecha')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div class="invalid-feedback">Elige la nueva fecha estimada.</div>
            @enderror
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-triangle-exclamation me-1"></i> Registrar incidencia</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
