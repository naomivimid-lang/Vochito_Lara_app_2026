<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Búsquedas - Bochito Envíos y Ventas</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Iconos Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- Hoja de estilos del proyecto -->
    <link rel="stylesheet" href="css/estilos.css" />
    <link rel="stylesheet" href="css/utilidades.css">
    <link rel="stylesheet" href="css/tema.css">
    <script src="js/tema.js"></script>
  </head>
  <body class="d-flex flex-column min-vh-100">
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
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('vochito.busquedas') }}"><i class="fa-solid fa-magnifying-glass"></i> Búsquedas</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-clipboard-list"></i> Pedidos</a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('pedidos.create') }}"><i class="fa-solid fa-file-circle-plus fa-fw"></i> Registrar pedido</a></li>
                <li><a class="dropdown-item" href="{{ route('pedidos.index') }}"><i class="fa-solid fa-list-check fa-fw"></i> Estatus y búsqueda</a></li>
                <li><a class="dropdown-item" href="{{ route('productos.index') }}"><i class="fa-solid fa-box fa-fw"></i> Productos</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.historial') }}"><i class="fa-solid fa-clock-rotate-left fa-fw"></i> Historial</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.notificaciones') }}"><i class="fa-solid fa-bell fa-fw"></i> Notificaciones</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="{{ route('vochito.incidencias') }}"><i class="fa-solid fa-triangle-exclamation fa-fw"></i> Registrar incidencia</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.evidencias') }}"><i class="fa-solid fa-camera fa-fw"></i> Evidencia de entrega</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.cancelacion') }}"><i class="fa-solid fa-ban fa-fw"></i> Cancelar pedido</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.modificacion') }}"><i class="fa-solid fa-pen-to-square fa-fw"></i> Modificar pedido</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.reporte_desempeno') }}"><i class="fa-solid fa-chart-column fa-fw"></i> Reporte de desempeño</a></li>
              </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{ route('vochito.contacto') }}"><i class="fa-solid fa-envelope"></i> Contacto</a></li>
            @include('partials.sesion')
          </ul>
        </div>
      </div>
    </nav>

    <header class="bg-primary text-white text-center py-5">
      <div class="container py-3">
        <h1 class="display-5 fw-bold">Búsqueda de pedidos</h1>
        <p class="lead">Encuentra pedidos por número de orden (PO), cliente, producto, fecha de entrega o estatus.</p>
      </div>
    </header>

    <main class="container my-5">
      <div class="card shadow-sm border-0 p-4 mb-4">
        <form method="GET" action="{{ route('vochito.busquedas') }}" role="search">
          <div class="row g-3 align-items-end">
            <div class="col-md-6 col-lg-4">
              <label for="po" class="form-label fw-bold">PO, cliente o producto</label>
              <input type="search" class="form-control @error('po') is-invalid @enderror" id="po" name="po" maxlength="120"
                     placeholder="Ej. PO-0003, María, miel" value="{{ $filtros['po'] ?? '' }}" />
              @error('po') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 col-lg-3">
              <label for="fecha" class="form-label fw-bold">Fecha de entrega</label>
              <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ $filtros['fecha'] ?? '' }}" />
              @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 col-lg-3">
              <label for="estatus" class="form-label fw-bold">Estatus</label>
              <select id="estatus" name="estatus" class="form-select">
                <option value="">Todos</option>
                @foreach (\App\Models\Pedido::ESTATUS as $e)
                  <option value="{{ $e }}" @selected(($filtros['estatus'] ?? '') === $e)>{{ $e }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 col-lg-2 d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
              <a href="{{ route('vochito.busquedas') }}" class="btn btn-outline-secondary" aria-label="Limpiar filtros"><i class="fa-solid fa-eraser"></i></a>
            </div>
          </div>
        </form>
      </div>

      <p class="text-muted" aria-live="polite">{{ $resultados->total() }} {{ $resultados->total() === 1 ? 'pedido encontrado' : 'pedidos encontrados' }}.</p>

      <div class="card shadow-sm border-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>PO</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th class="text-end">Cantidad</th>
                <th>Entrega</th>
                <th>Estatus</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($resultados as $pedido)
                <tr>
                  <td class="fw-bold text-nowrap">PO-{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</td>
                  <td>{{ $pedido->cliente }}</td>
                  <td>{{ $pedido->producto->nombre ?? 'Producto eliminado' }}</td>
                  <td class="text-end">{{ $pedido->cantidad }}</td>
                  <td class="text-nowrap">{{ $pedido->entrega->format('d/m/Y H:i') }}</td>
                  <td><span class="badge text-bg-{{ \App\Models\Pedido::COLOR_ESTATUS[$pedido->estatus] ?? 'secondary' }}">{{ $pedido->estatus }}</span></td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No encontramos pedidos con esos filtros.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="mt-4">{{ $resultados->links() }}</div>
    </main>

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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
