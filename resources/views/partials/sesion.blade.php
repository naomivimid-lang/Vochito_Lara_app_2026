@auth
    <li class="nav-item dropdown ms-lg-2">
        <a class="btn btn-sm btn-secondary-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-user"></i> {{ auth()->user()->name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            @if (auth()->user()->is_admin)
                <li><a class="dropdown-item" href="{{ route('usuarios.index') }}"><i class="fa-solid fa-users-gear fa-fw"></i> Usuarios</a></li>
                <li><hr class="dropdown-divider" /></li>
            @endif
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="fa-solid fa-right-from-bracket fa-fw"></i> Cerrar sesión</button>
                </form>
            </li>
        </ul>
    </li>
@else
    <li class="nav-item ms-lg-2"><a class="btn btn-sm btn-secondary-custom" href="{{ route('vochito.iniciosesion') }}"><i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión</a></li>
@endauth
