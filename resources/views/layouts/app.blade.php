<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bochito Envíos y Ventas')</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS del proyecto --}}
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilidades.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tema.css') }}">
    @stack('styles')

    <script src="{{ asset('js/tema.js') }}"></script>
</head>
<body class="d-flex flex-column min-vh-100">

    @include('layouts.partials.navbar')

    @yield('header')

    <main class="container my-5 flex-grow-1">
        @include('layouts.partials.alerts')
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/datos.js') }}"></script>
    @stack('scripts')
</body>
</html>
