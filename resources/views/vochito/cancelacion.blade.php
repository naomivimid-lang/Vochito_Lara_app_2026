@extends('layouts.app')

@section('title', 'Cancelación de pedidos - Bochito Envíos y Ventas')

@section('content')
  {{-- Carrusel informativo --}}
  <div id="carouselCancela" class="carousel slide mb-5 rounded shadow overflow-hidden" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active bg-teal text-white p-5 text-center">
        <div class="d-flex justify-content-center mb-3">
          <span class="icon-circle icon-circle-blue"><i class="bi bi-arrow-counterclockwise"></i></span>
        </div>
        <h2>Garantía de Cancelación Segura</h2>
        <p class="mb-0">
          Puedes cancelar tu orden de compra mientras el pedido no haya sido embarcado (antes del estatus "En tránsito").
        </p>
      </div>
    </div>
  </div>

  <div class="d-flex align-items-center gap-3 mb-4">
    <span class="icon-circle icon-circle-pink"><i class="bi bi-x-octagon"></i></span>
    <div>
      <h1 class="text-bochito fw-bold m-0">Cancelación de Pedidos</h1>
      <p class="text-muted m-0">Registra y da seguimiento a solicitudes de cancelación de pedidos activos.</p>
    </div>
  </div>

  <div class="row g-4 mb-5">
    {{-- Formulario Reutilizable de Cancelación --}}
    <div class="col-lg-7">
      <div class="card p-4 shadow-sm border-0">
        <h4 class="text-bochito mb-3 d-flex align-items-center gap-2">
          <span class="icon-circle icon-circle-yellow circulo-36"><i class="bi bi-exclamation-triangle"></i></span>
          Registrar Solicitud de Cancelación
        </h4>
        @include('partials.form_solicitud', ['tipo' => 'cancelacion', 'accion' => 'cancelar'])
      </div>
    </div>

    {{-- Preguntas Frecuentes y Políticas --}}
    <div class="col-lg-5">
      <h4 class="text-bochito mb-3 d-flex align-items-center gap-2">
        <span class="icon-circle icon-circle-blue circulo-36"><i class="bi bi-bell"></i></span>
        Preguntas Frecuentes
      </h4>
      <div class="accordion shadow-sm mb-4" id="accordionFAQ">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
              ¿Cuánto tarda el reembolso?
            </button>
          </h2>
          <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordionFAQ">
            <div class="accordion-body">
              El reembolso se procesa de 24 a 48 horas hábiles a tu método de pago original una vez aprobada la solicitud.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
              ¿Puedo cancelar si ya está en tránsito?
            </button>
          </h2>
          <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
            <div class="accordion-body">
              No, por políticas de logística los pedidos con estatus <strong>"En tránsito"</strong> o posterior no admiten cancelación inmediata y deberán procesarse como devolución.
            </div>
          </div>
        </div>
      </div>

      <div class="card p-3 text-center bg-white border shadow-sm">
        <p class="mb-2 text-muted">¿Tienes dudas sobre los cargos?</p>
        <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#policyModal">
          <i class="bi bi-shield-check me-1"></i> Ver Políticas de Cancelación
        </button>
      </div>
    </div>
  </div>

  {{-- Sección: Consultar, Actualizar y Eliminar Solicitudes Realizadas --}}
  <div class="card shadow-sm border-0 p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <div>
        <h3 class="h4 text-bochito fw-bold mb-1 d-flex align-items-center gap-2">
          <i class="bi bi-card-checklist text-teal"></i> Solicitudes de Cancelación Realizadas
        </h3>
        <p class="text-muted small mb-0">
          Consulta, actualiza el estatus o elimina solicitudes registradas en el sistema.
        </p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-teal text-white px-3 py-2 fs-6">
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
            <th scope="col">Motivo y Comentarios</th>
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
                  <div class="fw-semibold text-truncate" style="max-width: 180px;">{{ $sol->motivo ?? 'Cancelación' }}</div>
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

  {{-- Modal Políticas --}}
  <div class="modal fade" id="policyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-teal text-white">
          <h5 class="modal-title"><i class="bi bi-shield-check me-2"></i> Políticas Bochito</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <ul class="mb-0">
            <li>Las cancelaciones efectuadas antes de la preparación o embarque del producto son 100% libres de penalización.</li>
            <li>Una vez que el paquete pase a estatus <strong>"En tránsito"</strong>, el pedido no podrá cancelarse por este medio.</li>
            <li>El reembolso se efectúa en un plazo de 24 a 48 horas hábiles.</li>
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
  <script src="{{ asset('js/solicitudes.js') }}?v=2"></script>
@endpush
