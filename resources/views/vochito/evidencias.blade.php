<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Evidencia de entrega - Bochito Envíos y Ventas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/utilidades.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tema.css') }}">

    <script src="{{ asset('js/tema.js') }}"></script>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- MENÚ DE NAVEGACIÓN -->
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
                <li><a class="dropdown-item" href="{{ route('vochito.historial') }}"><i class="fa-solid fa-clock-rotate-left fa-fw"></i> Historial</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.notificaciones') }}"><i class="fa-solid fa-bell fa-fw"></i> Notificaciones</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="{{ route('vochito.incidencias') }}"><i class="fa-solid fa-triangle-exclamation fa-fw"></i> Registrar incidencia</a></li>
                <li><a class="dropdown-item active" aria-current="page" href="{{ route('vochito.evidencias') }}"><i class="fa-solid fa-camera fa-fw"></i> Evidencia de entrega</a></li>
                <li><a class="dropdown-item" href="{{ route('vochito.cancelacion') }}"><i class="fa-solid fa-ban fa-fw"></i> Cancelar pedido</a></li>
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

    <!-- ENCABEZADO -->
    <header class="bg-primary text-white text-center py-5">
        <div class="container py-3">
            <h1 class="display-4 fw-bold">Evidencia de Entrega</h1>
            <p class="lead">
                Registra quién recibió el pedido, la fecha, la hora y la fotografía de la entrega.
            </p>
        </div>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="container my-5 flex-grow-1">

        <!-- MENSAJE DE ÉXITO -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <!-- MENSAJES DE ERROR -->
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>No se pudo completar el registro.</strong>

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORMULARIO PARA REGISTRAR -->
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                <div class="card shadow-sm border-0 p-4">

                    <h4 class="text-center mb-4">
                        <i class="fa-solid fa-camera"></i> Registrar evidencia
                    </h4>

                    <form action="{{ route('evidencias.store') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="mb-3">
                            <label for="orden_compra" class="form-label fw-bold">
                                Orden de compra (PO) <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="orden_compra"
                                name="orden_compra"
                                placeholder="Ej. PO-2026-0001"
                                pattern="[Pp][Oo]-[0-9]{4}-[0-9]{4}"
                                value="{{ old('orden_compra') }}"
                                required
                            >

                            <div class="form-text">
                                Formato requerido: PO-2026-0001.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="recibe" class="form-label fw-bold">
                                Nombre de quien recibe <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="recibe"
                                name="recibe"
                                minlength="3"
                                maxlength="150"
                                value="{{ old('recibe') }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="fecha_entrega" class="form-label fw-bold">
                                Fecha y hora de entrega <span class="text-danger">*</span>
                            </label>

                            <input
                                type="datetime-local"
                                class="form-control"
                                id="fecha_entrega"
                                name="fecha_entrega"
                                value="{{ old('fecha_entrega') }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label fw-bold">
                                Fotografía de la entrega <span class="text-danger">*</span>
                            </label>

                            <input
                                class="form-control"
                                type="file"
                                id="foto"
                                name="foto"
                                accept="image/jpeg,image/png,image/webp"
                                required
                            >

                            <div class="form-text">
                                Formatos permitidos: JPG, JPEG, PNG y WEBP. Máximo 5 MB.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="comentarios" class="form-label fw-bold">
                                Comentarios adicionales
                            </label>

                            <textarea
                                class="form-control"
                                id="comentarios"
                                name="comentarios"
                                rows="3"
                                maxlength="1000"
                                placeholder="Referencias del domicilio, observaciones, etc."
                            >{{ old('comentarios') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mt-3">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Registrar evidencia
                        </button>

                    </form>
                </div>
            </div>
        </div>

        <!-- LISTADO DE EVIDENCIAS -->
        <div class="card shadow-sm border-0 mt-5">

            <div class="card-header bg-custom-dark text-white py-3">
                <h4 class="mb-0">
                    <i class="fa-solid fa-folder-open"></i> Evidencias registradas
                </h4>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Orden de compra</th>
                                <th>Recibió</th>
                                <th>Fecha de entrega</th>
                                <th>Fotografía</th>
                                <th>Comentarios</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($evidencias as $evidencia)

                                <tr>
                                    <td>{{ $evidencia->id }}</td>

                                    <td>{{ $evidencia->orden_compra }}</td>

                                    <td>{{ $evidencia->recibe }}</td>

                                    <td>
                                        {{ $evidencia->fecha_entrega->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ asset('storage/' . $evidencia->foto) }}" target="_blank">
                                            <img
                                                src="{{ asset('storage/' . $evidencia->foto) }}"
                                                alt="Fotografía de entrega"
                                                width="75"
                                                height="65"
                                                class="rounded"
                                                style="object-fit: cover;"
                                            >
                                        </a>
                                    </td>

                                    <td>{{ $evidencia->comentarios ?: 'Sin comentarios' }}</td>

                                    <td>
                                        <div class="d-flex flex-wrap gap-2">

                                            <button
                                                class="btn btn-warning btn-sm"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#editar{{ $evidencia->id }}"
                                            >
                                                <i class="fa-solid fa-pen"></i> Editar
                                            </button>

                                            <form
                                                action="{{ route('evidencias.destroy', $evidencia->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Estás seguro de eliminar esta evidencia?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i> Eliminar
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>

                                <!-- FORMULARIO PARA EDITAR -->
                                <tr class="collapse" id="editar{{ $evidencia->id }}">
                                    <td colspan="7">

                                        <div class="card card-body bg-light">

                                            <h5 class="mb-3">
                                                Editar evidencia #{{ $evidencia->id }}
                                            </h5>

                                            <form
                                                action="{{ route('evidencias.update', $evidencia->id) }}"
                                                method="POST"
                                                enctype="multipart/form-data"
                                            >

                                                @csrf
                                                @method('PUT')

                                                <div class="row">

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">
                                                            Orden de compra
                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            name="orden_compra"
                                                            value="{{ $evidencia->orden_compra }}"
                                                            pattern="[Pp][Oo]-[0-9]{4}-[0-9]{4}"
                                                            required
                                                        >
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">
                                                            Nombre de quien recibe
                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            name="recibe"
                                                            value="{{ $evidencia->recibe }}"
                                                            minlength="3"
                                                            maxlength="150"
                                                            required
                                                        >
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">
                                                            Fecha y hora de entrega
                                                        </label>

                                                        <input
                                                            type="datetime-local"
                                                            class="form-control"
                                                            name="fecha_entrega"
                                                            value="{{ $evidencia->fecha_entrega->format('Y-m-d\TH:i') }}"
                                                            required
                                                        >
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">
                                                            Cambiar fotografía
                                                        </label>

                                                        <input
                                                            type="file"
                                                            class="form-control"
                                                            name="foto"
                                                            accept="image/jpeg,image/png,image/webp"
                                                        >

                                                        <small class="text-muted">
                                                            Si no seleccionas otra imagen, se conserva la actual.
                                                        </small>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <label class="form-label fw-bold">
                                                            Comentarios
                                                        </label>

                                                        <textarea
                                                            class="form-control"
                                                            name="comentarios"
                                                            rows="2"
                                                            maxlength="1000"
                                                        >{{ $evidencia->comentarios }}</textarea>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <p class="mb-2">Fotografía actual:</p>

                                                        <img
                                                            src="{{ asset('storage/' . $evidencia->foto) }}"
                                                            alt="Fotografía actual"
                                                            width="100"
                                                            class="rounded"
                                                        >
                                                    </div>

                                                </div>

                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
                                                </button>

                                            </form>

                                        </div>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fa-solid fa-folder-open fa-2x text-muted mb-2"></i>
                                        <p class="mb-0">Todavía no hay evidencias registradas.</p>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </main>

    <!-- PIE DE PÁGINA -->
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
