<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Trazabilidad')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body.app-shell-admin { background-color: #f8faf9; }
        body.app-shell-productor {
            background: linear-gradient(180deg, #f2f8f5 0%, #f8faf9 42%, #f1f5f3 100%);
        }
        .navbar-app-admin { background: linear-gradient(90deg, #146c43 0%, #198754 55%, #157347 100%); }
        .navbar-app-productor {
            background: linear-gradient(90deg, #0d5035 0%, #1a6b4a 50%, #2d8a6e 100%);
            border-bottom: 3px solid rgba(255, 255, 255, 0.35);
        }
        .nav-role-badge {
            font-size: 0.7rem;
            letter-spacing: 0.04em;
        }
    </style>
    @stack('styles')
</head>
@php($navUser = auth()->user())
<body class="{{ $navUser && $navUser->esProductor() ? 'app-shell-productor' : 'app-shell-admin' }}">
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm {{ $navUser && $navUser->esProductor() ? 'navbar-app-productor' : 'navbar-app-admin' }}">
        <div class="container">
            <a class="navbar-brand fw-semibold d-flex flex-wrap align-items-center gap-2" href="{{ $navUser && $navUser->esAdmin() ? route('productores.index') : route('productor.dashboard') }}">
                <i class="bi bi-diagram-3 fs-5"></i>
                <span class="d-flex flex-column lh-sm">
                    <span>Sistema de Trazabilidad</span>
                    <span class="small fw-normal opacity-75">Sprint 1</span>
                </span>
                @if ($navUser)
                    <span class="badge rounded-pill bg-white nav-role-badge ms-lg-1 {{ $navUser->esAdmin() ? 'text-success' : 'text-body' }}">
                        {{ $navUser->esAdmin() ? 'Administrador' : 'Productor' }}
                    </span>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <div class="navbar-nav ms-auto gap-lg-1 align-items-lg-center">
                    @if ($navUser && $navUser->esProductor())
                        <a class="nav-link d-flex align-items-center gap-1 px-3 py-2 rounded {{ request()->routeIs('productor.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('productor.dashboard') }}">
                            <i class="bi bi-house-heart"></i> Mi panel
                        </a>
                    @endif

                    <a class="nav-link d-flex align-items-center gap-1 px-3 py-2 rounded {{ request()->routeIs('productores.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('productores.index') }}">
                        <i class="bi bi-people"></i> Productores
                    </a>
                    <a class="nav-link d-flex align-items-center gap-1 px-3 py-2 rounded {{ request()->routeIs('productos.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('productos.index') }}">
                        <i class="bi bi-flower1"></i> Productos agrícolas
                    </a>
                    <a class="nav-link d-flex align-items-center gap-1 px-3 py-2 rounded {{ request()->routeIs('eventos-produccion.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('eventos-produccion.index') }}">
                        <i class="bi bi-calendar2-week"></i> Proceso productivo
                    </a>
                    <a class="nav-link d-flex align-items-center gap-1 px-3 py-2 rounded {{ request()->routeIs('lotes.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('lotes.index') }}">
                        <i class="bi bi-box-seam"></i> Lotes
                    </a>

                    @if ($navUser && $navUser->esAdmin())
                        <span class="d-none d-lg-inline text-white-50 px-1" aria-hidden="true">|</span>
                        <span class="navbar-text text-white-50 small text-uppercase px-lg-2 d-lg-none w-100 mt-2 mb-0">Administración</span>
                        <span class="d-none d-lg-inline navbar-text text-white-50 small text-uppercase px-1" style="font-size: 0.65rem; letter-spacing: 0.08em;">Admin</span>

                        <a class="nav-link d-flex align-items-center gap-1 px-3 py-2 rounded {{ request()->routeIs('envios.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('envios.index') }}">
                            <i class="bi bi-truck"></i> Envíos
                        </a>
                        <a class="nav-link d-flex align-items-center gap-1 px-3 py-2 rounded {{ request()->routeIs('transportistas.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('transportistas.index') }}">
                            <i class="bi bi-person-vcard"></i> Responsables
                        </a>
                        <a class="nav-link d-flex align-items-center gap-1 px-3 py-2 rounded {{ request()->routeIs('ubicaciones.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('ubicaciones.index') }}">
                            <i class="bi bi-geo-alt"></i> Ubicaciones
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="d-flex align-items-center ms-lg-2 mt-2 mt-lg-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light border-0 rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1">
                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                        </button>
                    </form>
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
    @stack('scripts')
</body>
</html>
