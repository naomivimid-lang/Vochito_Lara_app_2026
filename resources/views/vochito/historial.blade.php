<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Historial de pedidos - Bochito Envíos y Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
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
            <li class="nav-item"><a class="nav-link" href="{{ route('vochito.busquedas') }}"><i class="fa-solid fa-magnifying-glass"></i> Búsquedas</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-clipboard-list"></i> Pedidos</a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('pedidos.create') }}"><i class="fa-solid fa-file-circle-plus fa-fw"></i> Registrar pedido</a></li>
                <li><a class="dropdown-item" href="{{ route('pedidos.index') }}"><i class="fa-solid fa-list-check fa-fw"></i> Estatus y búsqueda</a></li>
                <li><a class="dropdown-item" href="{{ route('productos.index') }}"><i class="fa-solid fa-box fa-fw"></i> Productos</a></li>
                <li><a class="dropdown-item active" aria-current="page" href="{{ route('vochito.historial') }}"><i class="fa-solid fa-clock-rotate-left fa-fw"></i> Historial</a></li>
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
            <h1 class="display-4 fw-bold">Historial de Pedidos</h1>
            <p class="lead">Consulta tus pedidos anteriores con su fecha de entrega y estatus final.</p>
        </div>
    </header>

    <main class="container my-5 flex-grow-1">
        <form method="GET" action="{{ route('vochito.historial') }}" class="d-flex flex-wrap gap-2 align-items-end mb-4">
            <div>
                <label for="estatus" class="form-label fw-bold">Filtrar por estatus</label>
                <select id="estatus" name="estatus" class="form-select">
                    <option value="">Todos</option>
                    @foreach (\App\Models\Pedido::ESTATUS as $e)
                        <option value="{{ $e }}" @selected($estatusSeleccionado === $e)>{{ $e }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-filter"></i> Filtrar</button>
            @if ($estatusSeleccionado)
                <a href="{{ route('vochito.historial') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>PO</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Fecha de entrega</th>
                            <th>Estatus final</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pedidos as $pedido)
                            <tr>
                                <td class="fw-bold text-nowrap">PO-{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $pedido->producto->nombre ?? 'Producto eliminado' }}</td>
                                <td>{{ $pedido->cantidad }}</td>
                                <td class="text-nowrap">{{ $pedido->entrega->format('d/m/Y') }}</td>
                                <td><span class="badge text-bg-{{ \App\Models\Pedido::COLOR_ESTATUS[$pedido->estatus] ?? 'secondary' }}">{{ $pedido->estatus }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No hay pedidos{{ $estatusSeleccionado ? ' con ese estatus' : '' }}.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $pedidos->links() }}</div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>