
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Bochito Envíos y Ventas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilidades.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tema.css') }}">

    <script src="{{ asset('js/tema.js') }}"></script>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Menú de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-custom-dark sticky-top">
        <div class="container">

            <a class="navbar-brand text-white d-flex align-items-center gap-2"
               href="{{ route('vochito.inicio') }}">

                <img src="{{ asset('img/logo.png') }}"
                     alt="Logo Bochito"
                     width="60"
                     height="60"
                     class="rounded-circle">

                <div class="d-flex flex-column lh-sm">
                    <span><i class="fa-solid fa-leaf"></i> Bochito</span>
                    <small>Comercio local</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
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
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-clipboard-list"></i> Pedidos
                        </a>
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
            <h1 class="display-5 fw-bold">Iniciar sesión</h1>
            <p class="lead">
                Ingresa con tu correo y contraseña para acceder a tu cuenta.
            </p>
        </div>
    </header>

    <!-- Formulario -->
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">

                <div class="card shadow-sm border-0 p-4">

                    <h2 class="h4 text-center mb-4">
                        <i class="fa-solid fa-user-circle"></i>
                        Accede a tu cuenta
                    </h2>

                    <!-- Mensaje general de error -->
                    @if ($errors->any())
                        <div class="alert alert-danger text-center" role="alert">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            No fue posible iniciar sesión. Verifica tu correo y contraseña.
                        </div>
                    @endif

                    <!-- Mensaje de éxito -->
                    @if (session('success'))
                        <div class="alert alert-success text-center" role="alert">
                            <i class="fa-solid fa-circle-check"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Formulario de inicio de sesión -->
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <!-- Correo -->
                        <div class="mb-3">
                            <label for="correo" class="form-label fw-bold">
                                Correo electrónico
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="email"
                                class="form-control"
                                id="correo"
                                name="email"
                                placeholder="ejemplo@correo.com"
                                value="{{ old('email') }}"
                                autocomplete="username"
                                required
                            >
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-3">
                            <label for="contrasena" class="form-label fw-bold">
                                Contraseña
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input
                                    type="password"
                                    class="form-control"
                                    id="contrasena"
                                    name="password"
                                    autocomplete="current-password"
                                    required
                                >

                                <button
                                    class="btn btn-outline-secondary"
                                    type="button"
                                    id="verContrasena"
                                    aria-label="Mostrar u ocultar contraseña">

                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Botón de inicio de sesión -->
                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                            id="btnEntrar">

                            <i class="fa-solid fa-right-to-bracket"></i>
                            Iniciar sesión
                        </button>

                    </form>

                    <hr class="my-4">

                    <!-- Registro -->
                    <div class="text-center">

                        <h3 class="h6 fw-bold">
                            ¿Todavía no tienes una cuenta?
                        </h3>

                        <p class="text-muted small">
                            Regístrate para comenzar a utilizar Bochito.
                        </p>

                        <a href="{{ route('registro') }}"
                           class="btn btn-outline-primary w-100">

                            <i class="fa-solid fa-user-plus"></i>
                            Crear una cuenta
                        </a>

                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Pie de página -->
    <footer class="bg-custom-dark text-white text-center py-3 mt-auto">
        <div class="container">

            <p class="small mb-2">
                <a class="link-light" href="{{ route('vochito.ayuda') }}">Ayuda</a>
                ·
                <a class="link-light" href="{{ route('vochito.privacidad') }}">Privacidad</a>
                ·
                <a class="link-light" href="{{ route('vochito.rnf') }}">
                    Requerimientos no funcionales
                </a>
            </p>

            <p class="mb-0 small">
                &copy; 2026 Bochito - Envíos y Ventas. Todos los derechos reservados.
            </p>

        </div>
    </footer>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mostrar u ocultar contraseña -->
    <script>
        const botonVer = document.getElementById('verContrasena');
        const campoPassword = document.getElementById('contrasena');

        botonVer.addEventListener('click', function () {
            const mostrar = campoPassword.type === 'password';

            campoPassword.type = mostrar ? 'text' : 'password';

            this.innerHTML = mostrar
                ? '<i class="fa-solid fa-eye-slash"></i>'
                : '<i class="fa-solid fa-eye"></i>';
        });
    </script>

</body>
</html>
