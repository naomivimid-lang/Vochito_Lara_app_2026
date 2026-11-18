
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones - Bochito Envíos y Ventas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilidades.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tema.css') }}">
    <script src="{{ asset('js/tema.js') }}"></script>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-custom-dark sticky-top">
        <div class="container">

            <a class="navbar-brand text-white d-flex align-items-center gap-2" href="{{ route('vochito.inicio') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Bochito" width="60" height="60" class="rounded-circle">
                <div class="d-flex flex-column lh-sm">
                    <span><i class="fa-solid fa-leaf"></i> Bochito</span>
                    <small>Comercio local</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Mostrar menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.inicio') }}">
                            <i class="fa-solid fa-house"></i> Inicio
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.nosotros') }}">
                            <i class="fa-solid fa-users"></i> Nosotros
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.servicios') }}">
                            <i class="fa-solid fa-store"></i> Servicios
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.catalogo') }}">
                            <i class="fa-solid fa-basket-shopping"></i> Catálogo
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.busquedas') }}">
                            <i class="fa-solid fa-magnifying-glass"></i> Búsquedas
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-clipboard-list"></i> Pedidos
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item" href="{{ route('pedidos.create') }}">
                                    <i class="fa-solid fa-file-circle-plus fa-fw"></i> Registrar pedido
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('pedidos.index') }}">
                                    <i class="fa-solid fa-list-check fa-fw"></i> Estatus y búsqueda
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('productos.index') }}">
                                    <i class="fa-solid fa-box fa-fw"></i> Productos
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('vochito.historial') }}">
                                    <i class="fa-solid fa-clock-rotate-left fa-fw"></i> Historial
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item active" aria-current="page"
                                    href="{{ route('vochito.notificaciones') }}">
                                    <i class="fa-solid fa-bell fa-fw"></i> Notificaciones
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ route('vochito.incidencias') }}">
                                    <i class="fa-solid fa-triangle-exclamation fa-fw"></i> Registrar incidencia
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('vochito.evidencias') }}">
                                    <i class="fa-solid fa-camera fa-fw"></i> Evidencia de entrega
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('vochito.cancelacion') }}">
                                    <i class="fa-solid fa-ban fa-fw"></i> Cancelar pedido
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('vochito.modificacion') }}">
                                    <i class="fa-solid fa-pen-to-square fa-fw"></i> Modificar pedido
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('vochito.reporte_desempeno') }}">
                                    <i class="fa-solid fa-chart-column fa-fw"></i> Reporte de desempeño
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.contacto') }}">
                            <i class="fa-solid fa-envelope"></i> Contacto
                        </a>
                    </li>

                    @include('partials.sesion')

                </ul>
            </div>
        </div>
    </nav>

    <!-- Encabezado -->
    <header class="bg-primary text-white text-center py-5">
        <div class="container py-3">
            <h1 class="display-4 fw-bold">Centro de Notificaciones</h1>
            <p class="lead">Consulta los avisos sobre el estatus de tus pedidos e incidencias.</p>
        </div>
    </header>

    <main class="container my-5 flex-grow-1">

        <!-- Mensajes -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Revisa los siguientes datos:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario para registrar -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-custom-dark text-white">
                <h2 class="h5 mb-0">
                    <i class="fa-solid fa-bell"></i> Registrar notificación
                </h2>
            </div>

            <div class="card-body">
                <form action="{{ route('notificaciones.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Orden de compra (PO)</label>
                            <input type="text" name="orden_compra" class="form-control"
                                placeholder="PO-2026-0005"
                                value="{{ old('orden_compra') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control"
                                placeholder="Pedido entregado"
                                value="{{ old('titulo') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tipo de notificación</label>
                            <select name="tipo" class="form-select" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="Entrega" {{ old('tipo') == 'Entrega' ? 'selected' : '' }}>Entrega</option>
                                <option value="Cambio de estatus" {{ old('tipo') == 'Cambio de estatus' ? 'selected' : '' }}>Cambio de estatus</option>
                                <option value="Incidencia" {{ old('tipo') == 'Incidencia' ? 'selected' : '' }}>Incidencia</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mensaje</label>
                            <textarea name="mensaje" class="form-control" rows="3"
                                placeholder="Escribe el aviso de la notificación..."
                                required>{{ old('mensaje') }}</textarea>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Registrar notificación
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de notificaciones -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 fw-bold mb-0">Mis notificaciones</h2>
            <span class="badge bg-primary">
                {{ $notificaciones->count() }} registros
            </span>
        </div>

        @forelse($notificaciones as $notificacion)

            <div class="card shadow-sm mb-3 {{ $notificacion->leida ? 'bg-light' : 'border-primary' }}">
                <div class="card-body">

                    <div class="d-flex gap-3 align-items-start">

                        <div class="fs-2
                            @if($notificacion->tipo == 'Entrega') text-success
                            @elseif($notificacion->tipo == 'Incidencia') text-warning
                            @else text-primary
                            @endif">

                            @if($notificacion->tipo == 'Entrega')
                                <i class="fa-solid fa-circle-check"></i>
                            @elseif($notificacion->tipo == 'Incidencia')
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            @else
                                <i class="fa-solid fa-arrows-rotate"></i>
                            @endif

                        </div>

                        <div class="flex-grow-1">

                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <h3 class="h5 fw-bold mb-1">
                                    {{ $notificacion->titulo }}
                                </h3>

                                @if($notificacion->leida)
                                    <span class="badge bg-secondary">Leída</span>
                                @else
                                    <span class="badge bg-primary">Nueva</span>
                                @endif
                            </div>

                            <p class="text-muted mb-2">
                                {{ $notificacion->mensaje }}
                            </p>

                            <small class="text-secondary">
                                <i class="fa-solid fa-file-lines"></i>
                                Pedido: {{ $notificacion->orden_compra }}
                                · {{ $notificacion->created_at->diffForHumans() }}
                            </small>

                            <div class="d-flex flex-wrap gap-2 mt-3">

                                <!-- Marcar como leída o no leída -->
                                <form action="{{ route('notificaciones.update', $notificacion->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="orden_compra" value="{{ $notificacion->orden_compra }}">
                                    <input type="hidden" name="titulo" value="{{ $notificacion->titulo }}">
                                    <input type="hidden" name="mensaje" value="{{ $notificacion->mensaje }}">
                                    <input type="hidden" name="tipo" value="{{ $notificacion->tipo }}">
                                    <input type="hidden" name="leida" value="{{ $notificacion->leida ? 0 : 1 }}">

                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        @if($notificacion->leida)
                                            <i class="fa-solid fa-envelope"></i> Marcar como no leída
                                        @else
                                            <i class="fa-solid fa-envelope-open"></i> Marcar como leída
                                        @endif
                                    </button>
                                </form>

                                <!-- Mostrar formulario de edición -->
                                <button class="btn btn-sm btn-outline-secondary" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#editar{{ $notificacion->id }}"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-pen"></i> Editar
                                </button>

                                <!-- Eliminar -->
                                <form action="{{ route('notificaciones.destroy', $notificacion->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Estás segura de eliminar esta notificación?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </button>
                                </form>

                            </div>

                            <!-- Formulario de edición -->
                            <div class="collapse mt-3" id="editar{{ $notificacion->id }}">
                                <div class="card card-body bg-white">

                                    <h4 class="h6 fw-bold">Editar notificación</h4>

                                    <form action="{{ route('notificaciones.update', $notificacion->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-2">
                                            <label class="form-label">Orden de compra</label>
                                            <input type="text" name="orden_compra" class="form-control"
                                                value="{{ $notificacion->orden_compra }}" required>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label">Título</label>
                                            <input type="text" name="titulo" class="form-control"
                                                value="{{ $notificacion->titulo }}" required>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label">Tipo</label>
                                            <select name="tipo" class="form-select" required>
                                                <option value="Entrega" {{ $notificacion->tipo == 'Entrega' ? 'selected' : '' }}>Entrega</option>
                                                <option value="Cambio de estatus" {{ $notificacion->tipo == 'Cambio de estatus' ? 'selected' : '' }}>Cambio de estatus</option>
                                                <option value="Incidencia" {{ $notificacion->tipo == 'Incidencia' ? 'selected' : '' }}>Incidencia</option>
                                            </select>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label">Mensaje</label>
                                            <textarea name="mensaje" class="form-control" rows="3" required>{{ $notificacion->mensaje }}</textarea>
                                        </div>

                                        <input type="hidden" name="leida" value="{{ $notificacion->leida ? 1 : 0 }}">

                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
                                        </button>

                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        @empty

            <div class="alert alert-info text-center py-4">
                <i class="fa-solid fa-bell-slash fs-2 mb-2"></i>
                <h3 class="h5">No hay notificaciones registradas</h3>
                <p class="mb-0">Cuando registres una notificación aparecerá aquí.</p>
            </div>

        @endforelse

    </main>

    <!-- Pie de página -->
    <footer class="bg-custom-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="small mb-2">
                <a class="link-light" href="{{ route('vochito.ayuda') }}">Ayuda</a> ·
                <a class="link-light" href="{{ route('vochito.privacidad') }}">Privacidad</a> ·
                <a class="link-light" href="{{ route('vochito.rnf') }}">Requerimientos no funcionales</a>
            </p>

            <p class="mb-0 small">
                &copy; 2026 Bochito - Envíos y Ventas. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
