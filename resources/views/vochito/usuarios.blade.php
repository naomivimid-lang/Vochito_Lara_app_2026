@extends('layouts.app')

@section('title', 'Usuarios')

@section('header')
    <h1 class="display-4 fw-bold">Usuarios</h1>
    <p class="lead">Administra las cuentas registradas en Bochito.</p>
@endsection

@section('content')
<form method="GET" action="{{ route('usuarios.index') }}" class="d-flex gap-2 align-items-end mb-4" role="search">
    <div>
        <label for="buscar" class="form-label fw-bold">Buscar por nombre o correo</label>
        <input type="search" id="buscar" name="buscar" class="form-control" value="{{ request('buscar') }}">
    </div>
    <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
    @if (request('buscar'))
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Limpiar</a>
    @endif
</form>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Registro</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td class="fw-bold">{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            @if ($usuario->is_admin)
                                <span class="badge text-bg-primary">Administrador</span>
                            @else
                                <span class="badge text-bg-secondary">Usuario</span>
                            @endif
                        </td>
                        <td class="text-nowrap">{{ $usuario->created_at?->format('d/m/Y') }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-pen"></i> Editar
                            </a>
                            @unless ($usuario->is(auth()->user()))
                                <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" class="d-inline"
                                      data-confirm="¿Eliminar la cuenta de {{ $usuario->name }}? Esta acción no se puede deshacer.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> Eliminar</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No hay usuarios con esa búsqueda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $usuarios->links() }}</div>
@endsection
