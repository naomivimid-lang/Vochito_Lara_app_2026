@extends('layouts.app')

@section('title', 'Modificación de pedidos - Bochito Envíos y Ventas')

@section('content')
  {{-- Carrusel informativo --}}
  <div id="carouselMod" class="carousel slide mb-5 rounded shadow overflow-hidden" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active bg-teal text-white p-5 text-center">
        <div class="d-flex justify-content-center mb-3">
          <span class="icon-circle icon-circle-pink"><i class="bi bi-pencil-square"></i></span>
        </div>
        <h2>Modifica tu Pedido al Instante</h2>
        <p class="mb-0">
          Solicita cambios a tu orden de compra mientras el pedido no haya sido embarcado (antes del estatus "En tránsito").
        </p>
      </div>
    </div>
  </div>

  <div class="d-flex align-items-center gap-3 mb-4">
    <span class="icon-circle icon-circle-pink"><i class="bi bi-pencil-square"></i></span>
    <div>
      <h1 class="text-bochito fw-bold m-0">Modificación de Pedidos</h1>
      <p class="text-muted m-0">Actualiza y consulta las solicitudes de cambio en tus órdenes activas.</p>
    </div>
  </div>

  <div class="row g-4 mb-5">
    {{-- Formulario Reutilizable de Modificación --}}
    <div class="col-lg-7">
      <div class="card p-4 shadow-sm border-0">
        <h4 class="text-bochito mb-3 d-flex align-items-center gap-2">
          <span class="icon-circle icon-circle-pink circulo-36"><i class="bi bi-pencil-fill"></i></span>
          Detalles de Solicitud de Modificación
        </h4>
        @include('partials.form_solicitud', ['tipo' => 'modificacion', 'accion' => 'modificar'])
      </div>
    </div>

    {{-- Ayuda y Restricciones --}}
    <div class="col-lg-5">
      <h4 class="text-bochito mb-3 d-flex align-items-center gap-2">
        <span class="icon-circle icon-circle-blue circulo-36"><i class="bi bi-question-circle"></i></span>
        Ayuda sobre Cambios
      </h4>
      <div class="accordion shadow-sm mb-4" id="accordionMod">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#mod1">
              ¿Puedo cambiar la dirección de entrega?
            </button>
          </h2>
          <div id="mod1" class="accordion-collapse collapse show" data-bs-parent="#accordionMod">
            <div class="accordion-body">
              Sí, siempre y cuando el repartidor no haya iniciado el trayecto (estatus previo a "En tránsito").
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mod2">
              ¿Qué ocurre si cambio las cantidades?
            </button>
          </h2>
          <div id="mod2" class="accordion-collapse collapse" data-bs-parent="#accordionMod">
            <div class="accordion-body">
              Si la cantidad aumenta, la orden quedará sujeta a validación de stock disponible y ajuste en el total de compra.
            </div>
          </div>
        </div>
      </div>

      <div class="card p-3 text-center bg-white border shadow-sm">
        <p class="mb-2 text-muted">¿Dudas con los tiempos de ajuste?</p>
        <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#modModal">
          <i class="bi bi-info-circle me-1"></i> Ver Restricciones y Condiciones
        </button>
      </div>
    </div>
  </div>

  {{-- Sección: Consultar, Actualizar y Eliminar Solicitudes de Modificación --}}
  <div class="card shadow-sm border-0 p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <div>
        <h3 class="h4 text-bochito fw-bold mb-1 d-flex align-items-center gap-2">
          <i class="bi bi-pencil-square text-teal"></i> Solicitudes de Modificación Registradas
        </h3>
        <p class="text-muted small mb-0">
          Consulta el estado de tus solicitudes de cambio, actualízalas o retíralas según corresponda.
        </p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="badge text-bg-teal px-3 py-2 fs-6">
          Total: <span id="contadorSolicitudes">0</span>
        </span>
        <select id="filtroEstado" class="form-select form-select-sm w-auto">
          <option value="">Todos los estados</option>
          <option value="Pendiente">Pendiente</option>
          <option value="Aprobada">Aprobada</option>
          <option value="Rechazada">Rechazada</option>
        </select>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="tablaSolicitudes">
        <thead class="table-light">
          <tr>
            <th scope="col" style="width: 80px;">Folio</th>
            <th scope="col">Orden (PO)</th>
            <th scope="col">Solicitante</th>
            <th scope="col">Tipo y Cambios Solicitados</th>
            <th scope="col">Fecha Registro</th>
            <th scope="col">Estado</th>
            <th scope="col" class="text-end" style="min-width: 170px;">Acciones</th>
          </tr>
        </thead>
        <tbody id="cuerpoSolicitudes">
          @if(isset($solicitudes) && count($solicitudes) > 0)
            @foreach($solicitudes as $sol)
              <tr>
                <td class="fw-bold text-secondary">#{{ $sol->id }}</td>
                <td><span class="badge text-bg-light border">{{ $sol->po }}</span></td>
                <td><small class="text-muted">{{ $sol->email }}</small></td>
                <td>
                  <div class="fw-semibold text-truncate" style="max-width: 180px;">{{ $sol->motivo ?? 'Modificación' }}</div>
                  <small class="text-muted text-truncate d-block" style="max-width: 220px;">{{ $sol->comentarios }}</small>
                </td>
                <td><small>{{ $sol->created_at ? $sol->created_at->format('d/m/Y H:i') : '—' }}</small></td>
                <td>
                  <span class="badge text-bg-{{ $sol->estado === 'Aprobada' ? 'success' : ($sol->estado === 'Rechazada' ? 'danger' : 'warning') }}">
                    {{ $sol->estado }}
                  </span>
                </td>
                <td class="text-end">
                  <div class="btn-group btn-group-sm">
                    @if($sol->estado === 'Pendiente')
                      <form method="POST" action="{{ route('vochito.solicitudes.estado', $sol->id) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="estado" value="Aprobada">
                        <button type="submit" class="btn btn-outline-success" title="Aprobar"><i class="bi bi-check-lg"></i> Aprobar</button>
                      </form>
                      <form method="POST" action="{{ route('vochito.solicitudes.estado', $sol->id) }}" class="d-inline ms-1">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="estado" value="Rechazada">
                        <button type="submit" class="btn btn-outline-warning" title="Rechazar"><i class="bi bi-x-lg"></i> Rechazar</button>
                      </form>
                    @endif
                    <form method="POST" action="{{ route('solicitudes.destroy', $sol->id) }}" class="d-inline ms-1" onsubmit="return confirm('¿Eliminar esta solicitud?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
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

  {{-- Modal Restricciones --}}
  <div class="modal fade" id="modModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-teal text-white">
          <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i> Restricciones de Modificación</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <ul class="mb-0">
            <li>Los cambios de artículos están sujetos a disponibilidad inmediata en el almacén central.</li>
            <li>Toda modificación solicitada después de haber iniciado la fase de empaque podría acarrear un reajuste en la fecha estimada de entrega.</li>
            <li>No se aplican penalizaciones económicas por ajustes solicitados en horario hábil.</li>
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('js/solicitudes.js') }}"></script>
@endpush
