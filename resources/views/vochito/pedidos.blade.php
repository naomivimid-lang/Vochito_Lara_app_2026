@extends('layouts.app')

@section('title', 'Pedidos - Bochito Envíos y Ventas')

@section('header')
  <header class="bg-primary text-white text-center py-5">
    <div class="container py-3">
      <h1 class="display-5 fw-bold">Pedidos</h1>
      <p class="lead">Registra nuevos pedidos y reporta incidencias del proceso.</p>
    </div>
  </header>
@endsection

@section('content')
  <div class="d-flex justify-content-end align-items-center gap-3 mb-3">
    <span class="text-muted small"><i class="fa-solid fa-user"></i> <span id="usuarioActual"></span></span>
    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnSalir"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</button>
  </div>
  <div id="mensaje" class="alert d-none" role="alert"></div>

  <!-- RF02: Registro de pedidos -->
  <div class="card shadow-sm border-0 p-4 mb-5">
    <h2 class="h4 mb-3">Registrar nuevo pedido</h2>
    <!-- Barra de progreso: avanza conforme los campos son válidos (js/pedidos.js) -->
    <div class="mb-4">
      <div class="small text-muted mb-1" id="textoProgreso" aria-live="polite">0 % completado</div>
      <div class="progress" id="progresoPedido" role="progressbar" aria-label="Avance del formulario" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar w-0"></div>
      </div>
    </div>
    <form id="formPedido" method="POST" action="{{ route('pedidos.store') }}" novalidate>
      @csrf
      <div class="row g-3">
        <div class="col-md-6">
          <label for="cliente" class="form-label fw-bold">Nombre del cliente <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('cliente') is-invalid @enderror" id="cliente" name="cliente" minlength="3" value="{{ old('cliente') }}" required />
          @error('cliente')
            <div class="invalid-feedback">{{ $message }}</div>
          @else
            <div class="invalid-feedback">Escribe el nombre del cliente (mínimo 3 caracteres).</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label for="producto" class="form-label fw-bold">Producto <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('producto') is-invalid @enderror" id="producto" name="producto" value="{{ old('producto') }}" required />
          @error('producto')
            <div class="invalid-feedback">{{ $message }}</div>
          @else
            <div class="invalid-feedback">Escribe el producto.</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label for="cantidad" class="form-label fw-bold">Cantidad <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" min="1" step="1" value="{{ old('cantidad') }}" required />
          @error('cantidad')
            <div class="invalid-feedback">{{ $message }}</div>
          @else
            <div class="invalid-feedback">La cantidad debe ser un número entero mayor a 0.</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label for="entrega" class="form-label fw-bold">Fecha y hora estimada de entrega <span class="text-danger">*</span></label>
          <input type="datetime-local" class="form-control @error('entrega') is-invalid @enderror" id="entrega" name="entrega" value="{{ old('entrega') }}" required />
          @error('entrega')
            <div class="invalid-feedback">{{ $message }}</div>
          @else
            <div class="invalid-feedback">Elige una fecha y hora futuras.</div>
          @enderror
        </div>
      </div>
      <div class="d-flex justify-content-between pt-4">
        <button type="reset" class="btn btn-outline-secondary px-4">Limpiar</button>
        <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-plus me-1"></i> Registrar pedido</button>
      </div>
    </form>
  </div>

  <!-- Lista de pedidos -->
  <h2 class="h4 mb-3">Pedidos registrados</h2>
  <div class="card shadow-sm border-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr><th>PO</th><th>Cliente</th><th>Producto</th><th>Cantidad</th><th>Entrega estimada</th><th>Estatus</th><th></th></tr>
        </thead>
        <tbody id="tablaPedidos">
          @if(isset($pedidos) && count($pedidos) > 0)
            @foreach($pedidos as $p)
              <tr>
                <td><strong class="text-bochito">{{ $p->po }}</strong></td>
                <td>{{ $p->cliente }}</td>
                <td>{{ $p->producto }}</td>
                <td>{{ $p->cantidad }}</td>
                <td><small>{{ $p->entrega_estimada ? $p->entrega_estimada->format('d/m/Y H:i') : '—' }}</small></td>
                <td><span class="badge text-bg-secondary">{{ $p->estatus }}</span></td>
                <td class="text-end">
                  <div class="btn-group btn-group-sm">
                    <form method="POST" action="{{ route('pedidos.destroy', $p->id) }}" onsubmit="return confirm('¿Eliminar pedido?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>

  <!-- RF05: Modal Registro de incidencias -->
  <div class="modal fade" id="modalIncidencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="formIncidencia" method="POST" action="{{ route('incidencias.store') }}" novalidate>
          @csrf
          <input type="hidden" id="incidenciaPoHidden" name="idPedido" value="" />
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation text-warning"></i> Registrar incidencia</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <p class="text-muted" id="incidenciaPedido"></p>
            <div class="mb-3">
              <label for="modalTipoIncidencia" class="form-label fw-bold">Tipo <span class="text-danger">*</span></label>
              <select id="modalTipoIncidencia" name="tipoIncidencia" class="form-select" required>
                <option value="">Selecciona un tipo</option>
                <option value="Retraso">Retraso</option>
                <option value="Falla de maquinaria">Falla de maquinaria</option>
                <option value="Falta de materia prima">Falta de materia prima</option>
              </select>
              <div class="invalid-feedback">Selecciona el tipo de incidencia.</div>
            </div>
            <div class="mb-3">
              <label for="causaIncidencia" class="form-label fw-bold">Causa <span class="text-danger">*</span></label>
              <textarea id="causaIncidencia" name="causa" class="form-control" rows="3" minlength="5" required></textarea>
              <div class="invalid-feedback">Describe la causa (mínimo 5 caracteres).</div>
            </div>
            <div>
              <label for="modalNuevaFecha" class="form-label fw-bold">Nueva fecha estimada <span class="text-danger">*</span></label>
              <input type="datetime-local" id="modalNuevaFecha" name="nuevaFecha" class="form-control" required />
              <div class="invalid-feedback">Elige la nueva fecha estimada.</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar incidencia</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('js/pedidos.js') }}"></script>
@endpush
