
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro de incidencias - Bochito Envíos y Ventas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/utilidades.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tema.css') }}">
    <script src="{{ asset('js/tema.js') }}"></script>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-custom-dark sticky-top">
        <div class="container">
            <a class="navbar-brand text-white d-flex align-items-center gap-2" href="{{ route('vochito.inicio') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Bochito Envíos y Ventas" width="60" height="60" class="rounded-circle d-inline-block align-text-top" />
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.inicio') }}"><i class="fa-solid fa-house"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.nosotros') }}"><i class="fa-solid fa-users"></i> Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.servicios') }}"><i class="fa-solid fa-store"></i> Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.catalogo') }}"><i class="fa-solid fa-basket-shopping"></i> Catálogo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.busquedas') }}"><i class="fa-solid fa-magnifying-glass"></i> Búsquedas</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-clipboard-list"></i> Pedidos
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('pedidos.create') }}"><i class="fa-solid fa-file-circle-plus fa-fw"></i> Registrar pedido</a></li>
                            <li><a class="dropdown-item" href="{{ route('pedidos.index') }}"><i class="fa-solid fa-list-check fa-fw"></i> Estatus y búsqueda</a></li>
                            <li><a class="dropdown-item" href="{{ route('productos.index') }}"><i class="fa-solid fa-box fa-fw"></i> Productos</a></li>
                            <li><a class="dropdown-item" href="{{ route('vochito.historial') }}"><i class="fa-solid fa-clock-rotate-left fa-fw"></i> Historial</a></li>
                            <li><a class="dropdown-item" href="{{ route('vochito.notificaciones') }}"><i class="fa-solid fa-bell fa-fw"></i> Notificaciones</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item active" aria-current="page" href="{{ route('vochito.incidencias') }}"><i class="fa-solid fa-triangle-exclamation fa-fw"></i> Registrar incidencia</a></li>
                            <li><a class="dropdown-item" href="{{ route('vochito.evidencias') }}"><i class="fa-solid fa-camera fa-fw"></i> Evidencia de entrega</a></li>
                            <li><a class="dropdown-item" href="{{ route('vochito.cancelacion') }}"><i class="fa-solid fa-ban fa-fw"></i> Cancelar pedido</a></li>
                            <li><a class="dropdown-item" href="{{ route('vochito.modificacion') }}"><i class="fa-solid fa-pen-to-square fa-fw"></i> Modificar pedido</a></li>
                            <li><a class="dropdown-item" href="{{ route('vochito.reporte_desempeno') }}"><i class="fa-solid fa-chart-column fa-fw"></i> Reporte de desempeño</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vochito.contacto') }}"><i class="fa-solid fa-envelope"></i> Contacto</a>
                    </li>

                    @include('partials.sesion')
                </ul>
            </div>
        </div>
    </nav>

    <!-- ENCABEZADO -->
    <header class="bg-primary text-white text-center py-5">
        <div class="container py-3">
            <h1 class="display-4 fw-bold">Registro de Incidencias</h1>
            <p class="lead">
                Registra un retraso, una falla de maquinaria o falta de materia prima,
                con su causa y la nueva fecha estimada.
            </p>
        </div>
    </header>

    <!-- CONTENIDO -->
    <main class="container my-5 flex-grow-1">

        <!-- MENSAJE DE ÉXITO -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}

                <button type="button" class="btn-close"
                        data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <!-- MENSAJES DE VALIDACIÓN -->
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Revisa los siguientes errores:</strong>

                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORMULARIO DE REGISTRO -->
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm border-0 p-4">

                    <h2 class="h4 mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-warning"></i>
                        Registrar nueva incidencia
                    </h2>

                    <form action="{{ route('incidencias.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="idPedido" class="form-label fw-bold">
                                Orden de compra (PO)
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   class="form-control"
                                   id="idPedido"
                                   name="orden_compra"
                                   value="{{ old('orden_compra') }}"
                                   placeholder="Ej. PO-2026-0001"
                                   pattern="[Pp][Oo]-[0-9]{4}-[0-9]{4}"
                                   required>

                            <div class="form-text">
                                Formato requerido: PO-2026-0001.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tipoIncidencia" class="form-label fw-bold">
                                Tipo de incidencia
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select"
                                    id="tipoIncidencia"
                                    name="tipo_incidencia"
                                    required>

                                <option value="">Selecciona una opción</option>

                                <option value="Retraso"
                                    {{ old('tipo_incidencia') == 'Retraso' ? 'selected' : '' }}>
                                    Retraso
                                </option>

                                <option value="Falla de maquinaria"
                                    {{ old('tipo_incidencia') == 'Falla de maquinaria' ? 'selected' : '' }}>
                                    Falla de maquinaria
                                </option>

                                <option value="Falta de materia prima"
                                    {{ old('tipo_incidencia') == 'Falta de materia prima' ? 'selected' : '' }}>
                                    Falta de materia prima
                                </option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="causa" class="form-label fw-bold">
                                Causa
                                <span class="text-danger">*</span>
                            </label>

                            <textarea class="form-control"
                                      id="causa"
                                      name="causa"
                                      rows="4"
                                      minlength="5"
                                      placeholder="Describe la causa de la incidencia..."
                                      required>{{ old('causa') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="nuevaFecha" class="form-label fw-bold">
                                Nueva fecha estimada de entrega
                                <span class="text-danger">*</span>
                            </label>

                            <input type="datetime-local"
                                   class="form-control"
                                   id="nuevaFecha"
                                   name="nueva_fecha_estimada"
                                   value="{{ old('nueva_fecha_estimada') }}"
                                   required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Registrar incidencia
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- LISTADO DE INCIDENCIAS -->
        <div class="card shadow-sm border-0 p-4 mt-5">

            <h2 class="h4 mb-4">
                <i class="fa-solid fa-list"></i>
                Incidencias registradas
            </h2>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Orden de compra</th>
                            <th>Tipo</th>
                            <th>Causa</th>
                            <th>Nueva fecha estimada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($incidencias as $incidencia)

                            <tr>
                                <td>{{ $incidencia->id }}</td>
                                <td>{{ $incidencia->orden_compra }}</td>
                                <td>{{ $incidencia->tipo_incidencia }}</td>
                                <td>{{ $incidencia->causa }}</td>

                                <td>
                                    {{ $incidencia->nueva_fecha_estimada->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <!-- BOTÓN EDITAR -->
                                        <button type="button"
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#editar{{ $incidencia->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <!-- FORMULARIO ELIMINAR -->
                                        <form action="{{ route('incidencias.destroy', $incidencia->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta incidencia?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>

                                        </form>

                                    </div>
                                </td>
                            </tr>

                            <!-- FORMULARIO PARA EDITAR -->
                            <tr class="collapse" id="editar{{ $incidencia->id }}">
                                <td colspan="6">

                                    <div class="card card-body bg-light">

                                        <h3 class="h6">Editar incidencia #{{ $incidencia->id }}</h3>

                                        <form action="{{ route('incidencias.update', $incidencia->id) }}"
                                              method="POST">

                                            @csrf
                                            @method('PUT')

                                            <div class="mb-2">
                                                <label class="form-label">Orden de compra</label>

                                                <input type="text"
                                                       class="form-control"
                                                       name="orden_compra"
                                                       value="{{ $incidencia->orden_compra }}"
                                                       pattern="[Pp][Oo]-[0-9]{4}-[0-9]{4}"
                                                       required>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label">Tipo de incidencia</label>

                                                <select class="form-select"
                                                        name="tipo_incidencia"
                                                        required>

                                                    <option value="Retraso"
                                                        {{ $incidencia->tipo_incidencia == 'Retraso' ? 'selected' : '' }}>
                                                        Retraso
                                                    </option>

                                                    <option value="Falla de maquinaria"
                                                        {{ $incidencia->tipo_incidencia == 'Falla de maquinaria' ? 'selected' : '' }}>
                                                        Falla de maquinaria
                                                    </option>

                                                    <option value="Falta de materia prima"
                                                        {{ $incidencia->tipo_incidencia == 'Falta de materia prima' ? 'selected' : '' }}>
                                                        Falta de materia prima
                                                    </option>

                                                </select>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label">Causa</label>

                                                <textarea class="form-control"
                                                          name="causa"
                                                          rows="3"
                                                          minlength="5"
                                                          required>{{ $incidencia->causa }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Nueva fecha estimada</label>

                                                <input type="datetime-local"
                                                       class="form-control"
                                                       name="nueva_fecha_estimada"
                                                       value="{{ $incidencia->nueva_fecha_estimada->format('Y-m-d\TH:i') }}"
                                                       required>
                                            </div>

                                            <button type="submit" class="btn btn-success">
                                                <i class="fa-solid fa-check"></i>
                                                Guardar cambios
                                            </button>

                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Todavía no hay incidencias registradas.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-custom-dark text-white text-center py-3 mt-auto">

        <div class="container">

            <p class="small mb-2">
                <a class="link-light" href="{{ route('vochito.ayuda') }}">Ayuda</a> ·
                <a class="link-light" href="{{ route('vochito.privacidad') }}">Privacidad</a> ·
                <a class="link-light" href="{{ route('vochito.rnf') }}">
                    Requerimientos no funcionales
                </a>
            </p>

            <p class="mb-0 small">
                &copy; 2026 Bochito - Envíos y Ventas. Todos los derechos reservados.
            </p>

        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
