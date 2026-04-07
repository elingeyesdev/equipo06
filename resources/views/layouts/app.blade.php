<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Trazabilidad')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-semibold d-flex align-items-center gap-2" href="{{ route('productores.index') }}">
                <i class="bi bi-diagram-3 fs-5"></i>
                <span>Sistema de Trazabilidad - Sprint 0</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <div class="navbar-nav ms-auto gap-lg-2">
                    <a class="nav-link d-flex align-items-center gap-1 px-3 py-2 rounded {{ request()->routeIs('productores.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('productores.index') }}">
                        <i class="bi bi-people"></i> Productores
                    </a>
                    <a class="nav-link d-flex align-items-center gap-1 px-3 py-2 rounded {{ request()->routeIs('productos.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('productos.index') }}">
                        <i class="bi bi-flower1"></i> Productos agrícolas
                    </a>
                    <a class="nav-link d-flex align-items-center gap-1 px-3 py-2 rounded {{ request()->routeIs('eventos-produccion.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('eventos-produccion.index') }}">
                        <i class="bi bi-calendar2-week"></i> Proceso productivo
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4 py-lg-5">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
