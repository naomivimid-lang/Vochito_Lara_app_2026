
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario - Vochito</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow mx-auto" style="max-width: 600px;">

        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Editar usuario</h4>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $usuario->name) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $usuario->email) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nueva contraseña</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Dejar vacío para conservar la actual">
                </div>

                <div class="d-flex justify-content-between">

                    <a href="{{ route('usuarios.index') }}"
                       class="btn btn-secondary">
                        Regresar
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>
