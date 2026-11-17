<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Cancelación de pedidos - Bochito Envíos y Ventas</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto+Flex:wght@400;600&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/estilos.css" />
    <link rel="stylesheet" href="css/miestilos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="css/utilidades.css" />
    <link rel="stylesheet" href="css/tema.css" />
    <script src="js/tema.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-custom-dark sticky-top">
      <div class="container">
        <a class="navbar-brand text-white d-flex align-items-center gap-2" href="{{ route('vochito.inicio') }}">
          <img src="img/logo.png" alt="Logo Bochito Envíos y Ventas" width="60" height="60" class="rounded-circle d-inline-block align-text-top" />
          <div class="d-flex flex-column lh-sm">
            <span><i class="fa-solid fa-leaf"></i> Bochito</span>
            <small>Comercio local</small>
          </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Mostrar menú">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-lg-center">
            <li class="nav-item"><a class="nav-link" href="{{ route('vochito.inicio') }}"><i class="fa-solid fa-house"></i> Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('vochito.nosotros') }}"><i class="fa-solid fa-users"></i> Nosotros</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('vochito.servicios') }}"><i class="fa-solid fa-store"></i> Servicios</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('vochito.catalogo') }}"><i class="fa-solid fa-basket-shopping"></i> Catálogo</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('vochito.busquedas') }}"><i class="fa-solid fa-magnifying-glass"></i> Búsquedas</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-clipboard-list"></i> Pedidos</a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('vochito.pedidos') }}"><i class="fa-solid fa-file-circle-plus fa-fw"></i> Registrar pedido</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.productos') }}"><i class="fa-solid fa-list-check fa-fw"></i> Estatus y búsqueda</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.historial') }}"><i class="fa-solid fa-clock-rotate-left fa-fw"></i> Historial</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.notificaciones') }}"><i class="fa-solid fa-bell fa-fw"></i> Notificaciones</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="{{ route('vochito.incidencias') }}"><i class="fa-solid fa-triangle-exclamation fa-fw"></i> Registrar incidencia</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.evidencias') }}"><i class="fa-solid fa-camera fa-fw"></i> Evidencia de entrega</a></li>
                <li><a class="dropdown-item active" aria-current="page" href="{{ route('vochito.cancelacion') }}"><i class="fa-solid fa-ban fa-fw"></i> Cancelar pedido</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.modificacion') }}"><i class="fa-solid fa-pen-to-square fa-fw"></i> Modificar pedido</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.reporte_desempeno') }}"><i class="fa-solid fa-chart-column fa-fw"></i> Reporte de desempeño</a></li>
              </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{ route('vochito.contacto') }}"><i class="fa-solid fa-envelope"></i> Contacto</a></li>
            <li class="nav-item ms-lg-2"><a class="btn btn-sm btn-secondary-custom" href="{{ route('vochito.iniciosesion') }}"><i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container my-5">
      <div
        id="carouselCancela"
        class="carousel slide mb-5 rounded shadow overflow-hidden"
        data-bs-ride="carousel"
      >
        <div class="carousel-inner">
          <div class="carousel-item active bg-teal text-white p-5 text-center">
            <div class="d-flex justify-content-center mb-3">
              <span class="icon-circle icon-circle-blue"
                ><i class="bi bi-arrow-counterclockwise"></i
              ></span>
            </div>
            <h2>Garantía de Cancelación Segura</h2>
            <p class="mb-0">
              Puedes cancelar tu orden de compra mientras el pedido no haya sido embarcado (antes del estatus "En tránsito").
            </p>
          </div>
        </div>
      </div>

      <div class="d-flex align-items-center gap-3 mb-4">
        <span class="icon-circle icon-circle-pink"
          ><i class="bi bi-x-octagon"></i
        ></span>
        <div>
          <h1 class="text-bochito fw-bold m-0">Cancelación de Pedidos</h1>
          <p class="text-muted m-0">
            Registra y da seguimiento a solicitudes de cancelación de pedidos activos.
          </p>
        </div>
      </div>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="row g-4 mb-5">
        <!-- Formulario de Registro de Cancelación -->
        <div class="col-lg-7">
          <div class="card p-4 shadow-sm border-0">
            <h4 class="text-bochito mb-3 d-flex align-items-center gap-2">
              <span class="icon-circle icon-circle-yellow circulo-36"
                ><i class="bi bi-exclamation-triangle"></i
              ></span>
              Registrar Solicitud de Cancelación
            </h4>
            <form id="formSolicitud" data-accion="cancelar" method="POST" action="{{ route('vochito.solicitudes.store') }}" novalidate>
              @csrf
              <input type="hidden" name="tipo" value="cancelacion" />

              <div class="mb-3">
                <label for="email" class="form-label fw-semibold"
                  >Correo Electrónico:</label
                >
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control"
                  placeholder="tu@correo.com"
                  required
                />
                <div class="invalid-feedback">Por favor ingresa un correo electrónico válido.</div>
              </div>

              <div class="mb-3">
                <label for="po" class="form-label fw-semibold"
                  >Orden de compra (PO):</label
                >
                <input
                  type="text"
                  id="po"
                  name="po"
                  class="form-control text-uppercase"
                  placeholder="Ej. PO-2026-0001"
                  pattern="[Pp][Oo]-[0-9]{4}-[0-9]{4}"
                  required
                />
                <div class="invalid-feedback">Escribe la orden de compra con el formato PO-2026-0001.</div>
              </div>

              <div class="mb-3">
                <label for="reason" class="form-label fw-semibold"
                  >Motivo de cancelación:</label
                >
                <select id="reason" name="motivo" class="form-select" required>
                  <option value="">Selecciona una opción</option>
                  <option value="Demora en tiempo de entrega">Demora en tiempo de entrega</option>
                  <option value="Error en productos seleccionados">
                    Error en productos seleccionados
                  </option>
                  <option value="Cambio de opinión">Cambio de opinión</option>
                  <option value="Problemas de pago / presupuesto">Problemas de pago / presupuesto</option>
                  <option value="Otro motivo">Otro motivo</option>
                </select>
                <div class="invalid-feedback">Selecciona un motivo.</div>
              </div>

              <div class="mb-3">
                <label for="comments" class="form-label fw-semibold"
                  >Comentarios adicionales:</label
                >
                <textarea
                  id="comments"
                  name="comentarios"
                  class="form-control"
                  rows="3"
                  placeholder="Describe brevemente la causa de cancelación..."
                  required
                ></textarea>
                <div class="invalid-feedback">Agrega una breve descripción.</div>
              </div>

              <button type="submit" class="btn btn-teal w-100 py-2 fw-bold shadow-sm">
                <i class="bi bi-send-fill me-1"></i> Confirmar y Registrar Cancelación
              </button>
            </form>
            <div id="avisoSolicitud" class="d-none" role="status"></div>
          </div>
        </div>

        <!-- Preguntas Frecuentes y Políticas -->
        <div class="col-lg-5">
          <h4 class="text-bochito mb-3 d-flex align-items-center gap-2">
            <span class="icon-circle icon-circle-blue circulo-36"
              ><i class="bi bi-bell"></i
            ></span>
            Preguntas Frecuentes
          </h4>
          <div class="accordion shadow-sm mb-4" id="accordionFAQ">
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button
                  class="accordion-button"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#faq1"
                >
                  ¿Cuánto tarda el reembolso?
                </button>
              </h2>
              <div
                id="faq1"
                class="accordion-collapse collapse show"
                data-bs-parent="#accordionFAQ"
              >
                <div class="accordion-body">
                  El reembolso se procesa de 24 a 48 horas hábiles a tu método
                  de pago original una vez aprobada la solicitud.
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button
                  class="accordion-button collapsed"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#faq2"
                >
                  ¿Puedo cancelar si ya está en tránsito?
                </button>
              </h2>
              <div
                id="faq2"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFAQ"
              >
                <div class="accordion-body">
                  No, por políticas de logística los pedidos con estatus <strong>"En tránsito"</strong> o posterior no admiten cancelación inmediata y deberán procesarse como devolución.
                </div>
              </div>
            </div>
          </div>

          <div class="card p-3 text-center bg-white border shadow-sm">
            <p class="mb-2 text-muted">¿Tienes dudas sobre los cargos?</p>
            <button
              type="button"
              class="btn btn-outline-dark btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#policyModal"
            >
              <i class="bi bi-shield-check me-1"></i> Ver Políticas de Cancelación
            </button>
          </div>
        </div>
      </div>

      <!-- Sección: Consultar, Actualizar y Eliminar Solicitudes Realizadas -->
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
                        <form method="POST" action="{{ route('vochito.solicitudes.destroy', $sol->id) }}" class="d-inline ms-1" onsubmit="return confirm('¿Eliminar esta solicitud?');">
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
    </main>

    <!-- Modal Políticas -->
    <div class="modal fade" id="policyModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-teal text-white">
            <h5 class="modal-title"><i class="bi bi-shield-check me-2"></i> Políticas Bochito</h5>
            <button
              type="button"
              class="btn-close btn-close-white"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <ul class="mb-0">
              <li>Las cancelaciones efectuadas antes de la preparación o embarque del producto son 100% libres de penalización.</li>
              <li>Una vez que el paquete pase a estatus <strong>"En tránsito"</strong>, el pedido no podrá cancelarse por este medio.</li>
              <li>El reembolso se efectúa en un plazo de 24 a 48 horas hábiles.</li>
            </ul>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/datos.js"></script>
    <script src="js/solicitudes.js"></script>
    <footer class="bg-custom-dark text-white text-center py-3 mt-auto">
      <div class="container">
        <p class="small mb-2">
          <a class="link-light" href="{{ route('vochito.ayuda') }}">Ayuda</a> ·
          <a class="link-light" href="{{ route('vochito.privacidad') }}">Privacidad</a> ·
          <a class="link-light" href="{{ route('vochito.rnf') }}">Requerimientos no funcionales</a>
        </p>
        <p class="mb-0 small">&copy; 2026 Bochito - Envíos y Ventas. Todos los derechos reservados.</p>
      </div>
    </footer>
  </body>
</html>
