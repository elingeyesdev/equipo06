<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Sistema de Trazabilidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(145deg, #eef6f3 0%, #f7fbf9 55%, #ffffff 100%);
        }
        .login-card {
            max-width: 460px;
            border: none;
            border-radius: 0.9rem;
            box-shadow: 0 10px 30px rgba(26, 92, 56, 0.12);
        }
        .btn-login-main {
            background-color: #3d8b9e;
            border-color: #3d8b9e;
            color: #fff;
            font-weight: 600;
            border-radius: 0.55rem;
            box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
        }
        .btn-login-main:hover {
            background-color: #327183;
            border-color: #327183;
            color: #fff;
        }
    </style>
</head>
<body class="d-flex align-items-center py-5">
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card login-card mx-auto">
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <div class="fs-1 mb-2">🔐</div>
                            <h1 class="h4 mb-1 fw-bold">Iniciar sesión</h1>
                            <p class="text-muted mb-0">Accede al sistema de trazabilidad</p>
                        </div>

                        <form method="POST" action="{{ route('login.attempt') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium">Correo electrónico</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="ejemplo@correo.com"
                                    required
                                    autofocus
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="password" class="form-label fw-medium">Contraseña</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    placeholder="••••••••"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordarme</label>
                            </div>

                            <button type="submit" class="btn btn-login-main w-100 btn-lg d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-box-arrow-in-right"></i>Entrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
