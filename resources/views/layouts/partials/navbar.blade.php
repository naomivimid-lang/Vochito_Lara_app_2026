<nav class="navbar navbar-expand-lg navbar-dark bg-custom-dark sticky-top">
    <div class="container">
        <a class="navbar-brand text-white d-flex align-items-center gap-2" href="{{ route('vochito.inicio') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Bochito" width="60" height="60" class="rounded-circle d-inline-block align-text-top">
            <div class="d-flex flex-column lh-sm">
                <span><i class="fa-solid fa-leaf"></i> Bochito</span>
                <small>Comercio local</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('vochito.inicio') ? 'active' : '' }}" href="{{ route('vochito.inicio') }}"><i class="fa-solid fa-house"></i> Inicio</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('vochito.nosotros') ? 'active' : '' }}" href="{{ route('vochito.nosotros') }}"><i class="fa-solid fa-users"></i> Nosotros</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('vochito.servicios') ? 'active' : '' }}" href="{{ route('vochito.servicios') }}"><i class="fa-solid fa-store"></i> Servicios</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('vochito.catalogo') ? 'active' : '' }}" href="{{ route('vochito.catalogo') }}"><i class="fa-solid fa-basket-shopping"></i> Catálogo</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('vochito.busquedas') ? 'active' : '' }}" href="{{ route('vochito.busquedas') }}"><i class="fa-solid fa-magnifying-glass"></i> Búsquedas</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('pedidos*', 'solicitudes*', 'cancelacion*', 'modificacion*', 'reporte*', 'incidencias*', 'evidencias*', 'productos*', 'historial*', 'notificaciones*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown"><i class="fa-solid fa-clipboard-list"></i> Pedidos</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item {{ request()->routeIs('pedidos.*') ? 'active' : '' }}" href="{{ route('pedidos.index') }}"><i class="fa-solid fa-file-circle-plus fa-fw"></i> Registrar pedido</a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('vochito.productos') ? 'active' : '' }}" href="{{ route('vochito.productos') }}"><i class="fa-solid fa-list-check fa-fw"></i> Estatus y búsqueda</a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('vochito.historial') ? 'active' : '' }}" href="{{ route('vochito.historial') }}"><i class="fa-solid fa-clock-rotate-left fa-fw"></i> Historial</a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('vochito.notificaciones') ? 'active' : '' }}" href="{{ route('vochito.notificaciones') }}"><i class="fa-solid fa-bell fa-fw"></i> Notificaciones</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item {{ request()->routeIs('incidencias.*') ? 'active' : '' }}" href="{{ route('incidencias.create') }}"><i class="fa-solid fa-triangle-exclamation fa-fw"></i> Registrar incidencia</a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('evidencias.*') ? 'active' : '' }}" href="{{ route('evidencias.create') }}"><i class="fa-solid fa-camera fa-fw"></i> Evidencia de entrega</a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('solicitudes.cancelacion*') ? 'active' : '' }}" href="{{ route('solicitudes.cancelacion') }}"><i class="fa-solid fa-ban fa-fw"></i> Cancelar pedido</a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('solicitudes.modificacion*') ? 'active' : '' }}" href="{{ route('solicitudes.modificacion') }}"><i class="fa-solid fa-pen-to-square fa-fw"></i> Modificar pedido</a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="{{ route('reportes.index') }}"><i class="fa-solid fa-chart-column fa-fw"></i> Reporte de desempeño</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('vochito.contacto') ? 'active' : '' }}" href="{{ route('vochito.contacto') }}"><i class="fa-solid fa-envelope"></i> Contacto</a></li>
                <li class="nav-item ms-lg-2"><a class="btn btn-sm btn-secondary-custom" href="{{ route('vochito.iniciosesion') }}"><i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>
