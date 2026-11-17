@extends('layouts.app')

@section('title', 'Reporte de desempeño - Bochito Envíos y Ventas')

@section('content')
  {{-- Carrusel informativo --}}
  <div id="carouselRep" class="carousel slide mb-5 rounded shadow overflow-hidden" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active bg-teal text-white p-5 text-center">
        <div class="d-flex justify-content-center mb-3">
          <span class="icon-circle icon-circle-blue"><i class="bi bi-bar-chart-line"></i></span>
        </div>
        <h2>Indicadores de Calidad y Desempeño</h2>
        <p class="mb-0">
          Analiza pedidos entregados a tiempo, retrasados y cancelados en cualquier rango de fechas seleccionado.
        </p>
      </div>
    </div>
  </div>

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
      <span class="icon-circle icon-circle-blue"><i class="bi bi-bar-chart-line"></i></span>
      <div>
        <h1 class="text-bochito fw-bold m-0">Reporte de Desempeño Operativo</h1>
        <p class="text-muted m-0">Métricas consolidadas de efectividad y puntualidad en entregas.</p>
      </div>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="badge text-bg-light border px-3 py-2 text-muted">
        <i class="bi bi-speedometer2 text-teal me-1"></i> Cumplimiento Global: 
        <strong class="text-teal fs-6" id="kpiEfectividad">{{ isset($reporte) ? $reporte['metricas']['porcentaje_efectividad'] . '%' : '94.6%' }}</strong>
      </span>
    </div>
  </div>

  {{-- Tarjetas KPI --}}
  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card text-center p-4 border-0 shadow-sm h-100">
        <div class="d-flex justify-content-center mb-2">
          <span class="icon-circle icon-circle-green circulo-36"><i class="bi bi-truck"></i></span>
        </div>
        <h6 class="text-muted text-uppercase small fw-bold mb-1">Entregados a tiempo</h6>
        <h2 class="display-6 text-success fw-bold mb-0" id="kpiATiempo">
          {{ isset($reporte) ? number_format($reporte['metricas']['entregados_a_tiempo']) : '1,180' }}
        </h2>
        <small class="text-muted">Órdenes puntuales</small>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-4 border-0 shadow-sm h-100">
        <div class="d-flex justify-content-center mb-2">
          <span class="icon-circle icon-circle-yellow circulo-36"><i class="bi bi-hourglass-split"></i></span>
        </div>
        <h6 class="text-muted text-uppercase small fw-bold mb-1">Retrasados</h6>
        <h2 class="display-6 text-warning fw-bold mb-0" id="kpiRetrasados">
          {{ isset($reporte) ? number_format($reporte['metricas']['retrasados']) : '52' }}
        </h2>
        <small class="text-muted">Fuera de tiempo estimado</small>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-4 border-0 shadow-sm h-100">
        <div class="d-flex justify-content-center mb-2">
          <span class="icon-circle icon-circle-pink circulo-36"><i class="bi bi-x-circle"></i></span>
        </div>
        <h6 class="text-muted text-uppercase small fw-bold mb-1">Cancelados</h6>
        <h2 class="display-6 text-danger fw-bold mb-0" id="kpiCancelados">
          {{ isset($reporte) ? number_format($reporte['metricas']['cancelados']) : '16' }}
        </h2>
        <small class="text-muted">Órdenes canceladas</small>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-4 border-0 shadow-sm h-100">
        <div class="d-flex justify-content-center mb-2">
          <span class="icon-circle icon-circle-blue circulo-36"><i class="bi bi-boxes"></i></span>
        </div>
        <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Pedidos</h6>
        <h2 class="display-6 text-teal fw-bold mb-0" id="kpiTotal">
          {{ isset($reporte) ? number_format($reporte['metricas']['total']) : '1,248' }}
        </h2>
        <small class="text-muted">Total en periodo</small>
      </div>
    </div>
  </div>

  {{-- Barra de distribución porcentual --}}
  <div class="card p-3 shadow-sm border-0 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-2 small fw-semibold text-muted">
      <span>Distribución de resultados en el periodo</span>
      <div class="d-flex gap-3">
        <span class="text-success"><i class="bi bi-square-fill"></i> A tiempo</span>
        <span class="text-warning"><i class="bi bi-square-fill"></i> Retrasados</span>
        <span class="text-danger"><i class="bi bi-square-fill"></i> Cancelados</span>
      </div>
    </div>
    <div class="progress" style="height: 22px;">
      <div id="barATiempo" class="progress-bar bg-success" role="progressbar" style="width: 94.6%;" title="A tiempo: 94.6%">94.6%</div>
      <div id="barRetrasados" class="progress-bar bg-warning text-dark" role="progressbar" style="width: 4.2%;" title="Retrasados: 4.2%">4.2%</div>
      <div id="barCancelados" class="progress-bar bg-danger" role="progressbar" style="width: 1.2%;" title="Cancelados: 1.2%">1.2%</div>
    </div>
  </div>

  <div class="row g-4 mb-5">
    {{-- Formulario Selector de Rango de Fechas --}}
    <div class="col-lg-7">
      <div class="card p-4 shadow-sm border-0 h-100">
        <h4 class="text-bochito mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-calendar3 text-teal"></i> Generar Reporte por Rango de Fechas
        </h4>

        <div class="mb-3">
          <label class="form-label small text-muted fw-semibold">Atajos rápidos:</label>
          <div class="btn-group btn-group-sm w-100" role="group">
            <button type="button" class="btn btn-outline-secondary" data-preset="7d">Últimos 7 días</button>
            <button type="button" class="btn btn-outline-secondary active" data-preset="30d">Últimos 30 días</button>
            <button type="button" class="btn btn-outline-secondary" data-preset="year">Este año</button>
            <button type="button" class="btn btn-outline-secondary" data-preset="all">Histórico total</button>
          </div>
        </div>

        <form id="formReporte" method="POST" action="{{ route('vochito.reporte.generar') }}" novalidate>
          @csrf
          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label for="rep-desde" class="form-label fw-semibold">Fecha Inicial (Desde):</label>
              <input
                type="date"
                id="rep-desde"
                name="desde"
                class="form-control"
                value="{{ $desde ?? now()->subMonth()->format('Y-m-d') }}"
                required
              />
              <div class="invalid-feedback">Elige la fecha inicial.</div>
            </div>
            <div class="col-sm-6">
              <label for="rep-hasta" class="form-label fw-semibold">Fecha Final (Hasta):</label>
              <input
                type="date"
                id="rep-hasta"
                name="hasta"
                class="form-control"
                value="{{ $hasta ?? now()->format('Y-m-d') }}"
                required
              />
              <div class="invalid-feedback">Elige una fecha final igual o posterior a la inicial.</div>
            </div>
          </div>
          <button type="submit" class="btn btn-teal w-100 py-2 fw-bold shadow-sm">
            <i class="bi bi-funnel-fill me-1"></i> Filtrar y Calcular Métricas
          </button>
        </form>
        <div id="avisoReporte" class="d-none" role="status"></div>
      </div>
    </div>

    {{-- Preguntas y Metodología --}}
    <div class="col-lg-5">
      <div class="card p-4 shadow-sm border-0 h-100">
        <h4 class="text-bochito mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-info-circle text-teal"></i> Criterios de Evaluación
        </h4>
        <div class="accordion shadow-sm mb-4" id="accRep">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#rep1">
                ¿Cómo se clasifica cada pedido?
              </button>
            </h2>
            <div id="rep1" class="accordion-collapse collapse show" data-bs-parent="#accRep">
              <div class="accordion-body small">
                <ul class="mb-0 ps-3">
                  <li><strong>A tiempo:</strong> Entrega real efectuada en o antes de la fecha y hora estimada.</li>
                  <li><strong>Retrasado:</strong> Entrega posterior al tiempo estimado o con incidencia de demora.</li>
                  <li><strong>Cancelado:</strong> Pedido con solicitud de cancelación procesada y aprobada.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="text-center mt-auto">
          <button type="button" class="btn btn-outline-dark btn-sm w-100" data-bs-toggle="modal" data-bs-target="#repModal">
            <i class="bi bi-file-earmark-bar-graph me-1"></i> Ver Metodología Oficial
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Tabla Detallada de Pedidos en el Periodo --}}
  <div class="card shadow-sm border-0 p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <div>
        <h3 class="h4 text-bochito fw-bold mb-1 d-flex align-items-center gap-2">
          <i class="bi bi-list-columns-reverse text-teal"></i> Desglose de Pedidos en el Periodo
        </h3>
        <p class="text-muted small mb-0">Detalle individual de órdenes evaluadas y su estatus de cumplimiento.</p>
      </div>
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
        <i class="bi bi-printer me-1"></i> Imprimir Reporte
      </button>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="tablaReporte">
        <thead class="table-light">
          <tr>
            <th scope="col">Orden (PO)</th>
            <th scope="col">Cliente</th>
            <th scope="col">Producto y Cantidad</th>
            <th scope="col">Entrega Estimada</th>
            <th scope="col">Entrega Real</th>
            <th scope="col">Estatus Actual</th>
            <th scope="col">Desempeño</th>
          </tr>
        </thead>
        <tbody id="cuerpoTablaReporte">
          @if(isset($reporte['pedidos']) && count($reporte['pedidos']) > 0)
            @foreach($reporte['pedidos'] as $p)
              <tr>
                <td><strong class="text-bochito">{{ $p['po'] }}</strong></td>
                <td>{{ $p['cliente'] }}</td>
                <td>{{ $p['producto'] }} <small class="text-muted">({{ $p['cantidad'] }})</small></td>
                <td><small>{{ $p['entrega_estimada'] }}</small></td>
                <td><small class="text-muted">{{ $p['entrega_real'] ?? '—' }}</small></td>
                <td><span class="badge text-bg-secondary">{{ $p['estatus'] }}</span></td>
                <td>
                  @if($p['desempeno'] === 'a_tiempo')
                    <span class="badge text-bg-success"><i class="bi bi-check-circle-fill me-1"></i> A tiempo</span>
                  @elseif($p['desempeno'] === 'retrasado')
                    <span class="badge text-bg-warning text-dark"><i class="bi bi-clock-fill me-1"></i> Retrasado</span>
                  @elseif($p['desempeno'] === 'cancelado')
                    <span class="badge text-bg-danger"><i class="bi bi-x-circle-fill me-1"></i> Cancelado</span>
                  @else
                    <span class="badge text-bg-info text-white"><i class="bi bi-hourglass-split me-1"></i> En proceso</span>
                  @endif
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>

  {{-- Modal Metodología --}}
  <div class="modal fade" id="repModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-teal text-white">
          <h5 class="modal-title"><i class="bi bi-gear-fill me-2"></i> Metodología de Cálculo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>El desempeño se calcula de forma automática comparando la marca de tiempo de confirmación contra la marca de tiempo en la evidencia de entrega:</p>
          <ol>
            <li><strong>Entregados a tiempo:</strong> Diferencia menor o igual a 0 minutos respecto a la hora pactada.</li>
            <li><strong>Retrasados:</strong> Diferencia positiva mayor a 0 minutos sin justificación aprobada.</li>
            <li><strong>Cancelados:</strong> Órdenes donde se confirmó la solicitud de cancelación antes del despacho.</li>
          </ol>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('js/reportes.js') }}?v=2"></script>
@endpush
