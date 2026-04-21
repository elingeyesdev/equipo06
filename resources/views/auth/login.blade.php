<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Sistema de Trazabilidad</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: #f5f7f6;
            color: #1e2b22;
        }
        .auth-layout {
            min-height: 100vh;
        }
        .hero-pane {
            position: relative;
            min-height: 42vh;
            border-radius: 0 0 1.25rem 1.25rem;
            overflow: hidden;
            background-image:
                linear-gradient(145deg, rgba(8, 36, 20, 0.82) 0%, rgba(23, 88, 44, 0.72) 45%, rgba(46, 125, 50, 0.52) 100%),
                url('https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1400&q=80');
            background-size: cover;
            background-position: center;
        }
        .hero-pane::before,
        .hero-pane::after {
            content: "";
            position: absolute;
            width: 11rem;
            height: 11rem;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 999px;
            opacity: 0.55;
        }
        .hero-pane::before {
            left: -3rem;
            bottom: -3rem;
        }
        .hero-pane::after {
            right: -2rem;
            top: -2rem;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 33rem;
        }
        .hero-logo {
            width: 4.2rem;
            height: 4.2rem;
            border-radius: 50%;
            border: 2px solid rgba(162, 214, 86, 0.7);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #a2d656;
            font-size: 2rem;
            margin-bottom: 1.1rem;
            backdrop-filter: blur(1.5px);
        }
        .hero-title {
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
        }
        .hero-subtitle {
            color: rgba(241, 249, 243, 0.88);
            max-width: 31rem;
            margin-bottom: 2rem;
        }
        .hero-features {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.85rem;
        }
        .hero-feature {
            background: rgba(12, 55, 30, 0.38);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.85rem;
            padding: 0.9rem 0.75rem;
            text-align: center;
        }
        .hero-feature i {
            display: inline-flex;
            margin-bottom: 0.5rem;
            font-size: 1.35rem;
            color: #b7f07f;
        }
        .hero-feature span {
            color: #f2fff4;
            font-size: 0.82rem;
            line-height: 1.35;
            font-weight: 500;
        }
        .form-pane {
            min-height: 58vh;
        }
        .login-card {
            width: 100%;
            max-width: 29rem;
            border: none;
            border-radius: 1.1rem;
            box-shadow: 0 18px 45px rgba(20, 63, 33, 0.12);
            animation: fadeInUp 0.5s ease-out;
        }
        .brand-mark {
            width: 3.4rem;
            height: 3.4rem;
            margin: 0 auto 0.8rem;
            border-radius: 0.9rem;
            background: linear-gradient(145deg, #e6f4ea, #f4fbf6);
            border: 1px solid #d1ead8;
            color: #2e7d32;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
        }
        .subtitle {
            color: #5d6f61;
            font-size: 0.94rem;
        }
        .form-label {
            color: #2d5b34;
        }
        .input-group-text {
            background: #f6faf7;
            border-color: #d6e6da;
            color: #4d7f53;
        }
        .form-control {
            border-color: #d6e6da;
            padding-block: 0.72rem;
        }
        .form-control:focus {
            border-color: #2e7d32;
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.15);
        }
        .password-toggle {
            border-color: #d6e6da;
            color: #57785c;
            background: #f9fcfa;
        }
        .password-toggle:hover {
            background: #eef6f0;
            color: #2e7d32;
        }
        .login-meta a {
            color: #2e7d32;
            text-decoration: none;
            font-weight: 500;
        }
        .login-meta a:hover {
            text-decoration: underline;
        }
        .btn-login-main {
            background: linear-gradient(90deg, #2e7d32 0%, #3b9b41 100%);
            border: 0;
            color: #fff;
            font-weight: 700;
            border-radius: 0.7rem;
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.28);
            transition: all 0.24s ease;
        }
        .btn-login-main:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 11px 25px rgba(46, 125, 50, 0.35);
            background: linear-gradient(90deg, #256b2a 0%, #328a38 100%);
        }
        .auth-footer {
            color: #8a9d8d;
            font-size: 0.82rem;
            text-align: center;
            margin-top: 1.2rem;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (min-width: 992px) {
            .hero-pane {
                min-height: 100vh;
                border-radius: 0 1.45rem 1.45rem 0;
            }
            .form-pane {
                min-height: 100vh;
            }
        }
        @media (max-width: 991.98px) {
            .hero-features {
                grid-template-columns: 1fr;
            }
            .hero-feature {
                text-align: left;
                display: flex;
                align-items: center;
                gap: 0.7rem;
            }
            .hero-feature i {
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <main class="container-fluid px-0 auth-layout">
        <div class="row g-0 auth-layout">
            <section class="col-lg-6 d-flex align-items-center hero-pane">
                <div class="hero-content text-white px-4 px-md-5 py-5">
                    <div class="hero-logo">
                        <i class="bi bi-flower3"></i>
                    </div>
                    <h1 class="display-6 hero-title">Bienvenido a<br>Trazabilidad Agronómica</h1>
                    <p class="hero-subtitle fs-6">
                        Sistema inteligente para el control y seguimiento de la producción agrícola.
                    </p>
                    <div class="hero-features">
                        <div class="hero-feature">
                            <i class="bi bi-compass"></i>
                            <span>Control total de procesos</span>
                        </div>
                        <div class="hero-feature">
                            <i class="bi bi-shield-check"></i>
                            <span>Información segura</span>
                        </div>
                        <div class="hero-feature">
                            <i class="bi bi-graph-up-arrow"></i>
                            <span>Decisiones inteligentes</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="col-lg-6 d-flex align-items-center justify-content-center p-3 p-md-4 p-xl-5 form-pane">
                <div class="card login-card">
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <div class="brand-mark">
                                <i class="bi bi-leaf"></i>
                            </div>
                            <h1 class="h3 mb-1 fw-bold">Iniciar sesión</h1>
                            <p class="subtitle mb-0">Accede a tu cuenta para continuar</p>
                        </div>

                        <form method="POST" action="{{ route('login.attempt') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium">Correo electrónico</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        placeholder="ejemplo@correo.com"
                                        required
                                        autofocus
                                    >
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="password" class="form-label fw-medium">Contraseña</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="••••••••"
                                        required
                                    >
                                    <button class="btn password-toggle" type="button" id="togglePassword" aria-label="Mostrar u ocultar contraseña">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 login-meta">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Recordarme</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                                @else
                                    <a href="#" aria-disabled="true">¿Olvidaste tu contraseña?</a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-login-main w-100 btn-lg d-inline-flex align-items-center justify-content-center gap-2">
                                Entrar
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </form>

                        <p class="auth-footer mb-0 mt-4">© Trazabilidad Agronómica</p>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        if (passwordInput && togglePassword) {
            togglePassword.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                this.innerHTML = isPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
            });
        }
    </script>
</body>
</html>
