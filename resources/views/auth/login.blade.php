<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Trazabilidad Agronómica</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --green-900: #0b3d21;
            --green-800: #14552e;
            --green-700: #1f6f3a;
            --green-600: #2e7d32;
            --green-500: #3da644;
            --green-300: #a8df59;
            --surface: #f5f6f4;
            --text-main: #1f2a22;
            --text-soft: #6f786f;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--surface);
            color: var(--text-main);
        }

        .login-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 60% 40%;
            gap: 0;
            padding: 0;
        }

        .left-panel {
            position: relative;
            overflow: hidden;
            border-top-right-radius: 24px;
            border-bottom-right-radius: 24px;
            background-image:
                linear-gradient(180deg, rgba(37, 102, 53, 0.52) 0%, rgba(18, 66, 36, 0.76) 48%, rgba(6, 33, 18, 0.90) 100%),
                url('https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=1800&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3.5rem 3rem;
        }

        .left-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 520px;
            color: #f6fff7;
            text-align: center;
        }

        .agro-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 110px;
            height: 110px;
            border: 3px solid rgba(168, 223, 89, 0.85);
            border-radius: 50%;
            margin-bottom: 1.5rem;
            background: rgba(5, 38, 21, 0.3);
            backdrop-filter: blur(2px);
        }

        .agro-logo-wrap svg {
            width: 64px;
            height: 64px;
        }

        .welcome-small {
            font-size: 2.75rem;
            font-weight: 500;
            line-height: 1.1;
            margin: 0;
        }

        .welcome-big {
            font-size: 3.05rem;
            font-weight: 800;
            line-height: 1.05;
            margin: 0.2rem 0 0.9rem;
            letter-spacing: -0.02em;
        }

        .welcome-line {
            width: 64px;
            height: 4px;
            border-radius: 999px;
            margin: 0 auto 1.2rem;
            background: #a8df59;
        }

        .welcome-copy {
            font-size: 1.32rem;
            line-height: 1.45;
            color: rgba(240, 250, 241, 0.93);
            margin: 0 auto 2.6rem;
            max-width: 470px;
        }

        .feature-row {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.3rem;
            margin: 0 auto;
            max-width: 520px;
        }

        .feature-item {
            text-align: center;
            color: #f2fdf2;
        }

        .feature-item i {
            font-size: 2rem;
            color: #a8df59;
            display: block;
            margin-bottom: 0.4rem;
        }

        .feature-item span {
            font-size: 1.08rem;
            font-weight: 500;
            line-height: 1.35;
        }

        .left-leaf-deco {
            position: absolute;
            bottom: -18px;
            left: -6px;
            width: 180px;
            height: 180px;
            opacity: 0.35;
            z-index: 1;
        }

        .left-dots {
            position: absolute;
            right: 36px;
            bottom: 46px;
            width: 82px;
            height: 82px;
            background-image: radial-gradient(circle, rgba(160, 224, 93, 0.65) 2.2px, transparent 2.2px);
            background-size: 14px 14px;
            opacity: 0.8;
            z-index: 1;
        }

        .right-panel {
            position: relative;
            background: #f3f4f2;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow: hidden;
        }

        .right-dots {
            position: absolute;
            top: 48px;
            left: 52px;
            width: 74px;
            height: 74px;
            background-image: radial-gradient(circle, #d6d9d4 1.7px, transparent 1.7px);
            background-size: 11px 11px;
        }

        .right-leaf-bg {
            position: absolute;
            right: 18px;
            bottom: 8px;
            width: 190px;
            height: 190px;
            opacity: 0.22;
        }

        .login-card {
            position: relative;
            width: 100%;
            max-width: 500px;
            border: 0;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 20px 48px rgba(31, 57, 37, 0.12);
            padding: 2.15rem 2rem 1.5rem;
            animation: cardFade 0.45s ease-out;
            z-index: 2;
        }

        @keyframes cardFade {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-top {
            text-align: center;
            margin-bottom: 1.35rem;
        }

        .logo-badge {
            width: 78px;
            height: 78px;
            border: 2px solid #2e7d32;
            border-radius: 50%;
            margin: 0 auto 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-badge svg {
            width: 50px;
            height: 50px;
        }

        .brand-title {
            font-size: 2.02rem;
            letter-spacing: 0.08em;
            font-weight: 800;
            margin: 0;
            color: #124f2c;
            text-transform: uppercase;
            line-height: 1;
        }

        .brand-sub {
            margin: 0.22rem 0 0;
            text-transform: uppercase;
            letter-spacing: 0.32em;
            font-size: 0.92rem;
            color: #2f7e37;
            font-weight: 600;
        }

        .brand-rule {
            width: 126px;
            height: 2px;
            background: #86bb6d;
            margin: 0.45rem auto 1.35rem;
            border-radius: 999px;
        }

        .form-heading {
            text-align: center;
            margin-bottom: 1.4rem;
        }

        .form-heading h1 {
            margin: 0;
            font-size: 2.82rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #1c2a20;
        }

        .form-heading p {
            margin: 0.35rem 0 0;
            font-size: 1.17rem;
            color: #6f786f;
        }

        .field-label {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f6f3a;
            margin-bottom: 0.45rem;
        }

        .input-group .input-group-text {
            border-color: #cfd9cd;
            background: #f7f9f6;
            color: #1f6f3a;
            padding-inline: 0.9rem;
            font-size: 1.06rem;
        }

        .input-group .form-control,
        .input-group .btn {
            border-color: #cfd9cd;
            min-height: 52px;
            font-size: 1.06rem;
        }

        .input-group .form-control {
            padding-inline: 0.9rem;
        }

        .form-control:focus {
            border-color: #2e7d32;
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.16);
        }

        .toggle-password {
            background: #f7f9f6;
            color: #7b847b;
            transition: all 0.2s ease;
        }

        .toggle-password:hover {
            background: #edf3ed;
            color: #2e7d32;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            margin: 0.9rem 0 1.35rem;
            font-size: 1.01rem;
        }

        .meta-row a {
            color: #2e7d32;
            text-decoration: none;
            font-weight: 500;
        }

        .meta-row a:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            border: 0;
            border-radius: 11px;
            min-height: 58px;
            font-size: 1.78rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.01em;
            background: linear-gradient(90deg, #2f9b3b 0%, #2f8f34 48%, #2d7a2f 100%);
            box-shadow: 0 10px 24px rgba(46, 125, 50, 0.27);
            position: relative;
            overflow: hidden;
            transition: all 0.24s ease;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            right: 10px;
            top: 50%;
            width: 56px;
            height: 56px;
            transform: translateY(-50%);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 80 80'%3E%3Cg fill='none' stroke='%2354a760' stroke-width='2' stroke-opacity='.5'%3E%3Cpath d='M42 50c7-10 16-15 27-16-1 12-8 21-19 27'/%3E%3Cpath d='M31 55c6-8 11-13 20-15-3 9-8 15-15 20'/%3E%3C/g%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.45;
        }

        .btn-login:hover {
            transform: translateY(-1px) scale(1.01);
            box-shadow: 0 14px 28px rgba(34, 110, 40, 0.35);
            color: #fff;
        }

        .btn-login .arrow {
            font-size: 1.92rem;
            margin-left: 0.5rem;
            position: relative;
            z-index: 2;
        }

        .login-footer {
            margin-top: 1.45rem;
            border-top: 1px solid #ecefea;
            padding-top: 1rem;
            text-align: center;
            color: #8a9288;
            font-size: 0.87rem;
        }

        .login-footer i {
            display: block;
            color: #2e7d32;
            font-size: 1.35rem;
            margin-bottom: 0.45rem;
        }

        @media (max-width: 1399px) {
            .welcome-small { font-size: 2.25rem; }
            .welcome-big { font-size: 2.45rem; }
            .welcome-copy { font-size: 1.1rem; }
            .feature-item span { font-size: 0.95rem; }
            .form-heading h1 { font-size: 2.35rem; }
            .btn-login { font-size: 1.45rem; }
        }

        @media (max-width: 991.98px) {
            .login-shell {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .left-panel {
                border-radius: 0 0 24px 24px;
                padding: 2.5rem 1.6rem;
                min-height: auto;
            }

            .right-panel {
                padding: 1.8rem 1rem 2rem;
            }

            .feature-row {
                gap: 0.8rem;
            }

            .feature-item i {
                font-size: 1.55rem;
            }

            .feature-item span {
                font-size: 0.9rem;
            }

            .login-card {
                padding: 1.55rem 1.2rem 1.2rem;
            }

            .form-heading h1 {
                font-size: 2rem;
            }

            .meta-row {
                font-size: 0.92rem;
            }

            .btn-login {
                min-height: 52px;
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <section class="left-panel">
            <svg class="left-leaf-deco" viewBox="0 0 200 200" aria-hidden="true">
                <g fill="none" stroke="rgba(168,223,89,0.75)" stroke-width="2">
                    <path d="M25 168c16-34 34-53 56-64" />
                    <path d="M49 178c-3-23-1-42 9-58" />
                    <path d="M75 117c15 1 28 7 40 18" />
                    <path d="M93 144c15 2 27 8 35 17" />
                </g>
            </svg>
            <div class="left-dots"></div>

            <div class="left-content">
                <div class="agro-logo-wrap">
                    <svg viewBox="0 0 64 64" aria-hidden="true">
                        <path d="M32 56V25" stroke="#a8df59" stroke-width="2.8" stroke-linecap="round"/>
                        <path d="M32 36c-7.5 0-13.7-5.4-14.7-12.6 7.6 0 13.8 5.4 14.7 12.6z" fill="#a8df59"/>
                        <path d="M32 36c7.5 0 13.7-5.4 14.7-12.6-7.6 0-13.8 5.4-14.7 12.6z" fill="#8ed14a"/>
                        <path d="M12 52c5-7 11-11 20-12" stroke="#a8df59" stroke-width="2.8" stroke-linecap="round"/>
                        <path d="M52 52c-5-7-11-11-20-12" stroke="#a8df59" stroke-width="2.8" stroke-linecap="round"/>
                        <path d="M15 58h34" stroke="#a8df59" stroke-width="2.8" stroke-linecap="round"/>
                    </svg>
                </div>

                <h2 class="welcome-small">Bienvenido a</h2>
                <h1 class="welcome-big">Trazabilidad Agronómica</h1>
                <div class="welcome-line"></div>
                <p class="welcome-copy">Sistema inteligente para el control y seguimiento de la producción agrícola.</p>

                <div class="feature-row">
                    <div class="feature-item">
                        <i class="bi bi-compass"></i>
                        <span>Control total<br>de procesos</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-shield-check"></i>
                        <span>Información<br>segura</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Decisiones<br>inteligentes</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="right-panel">
            <div class="right-dots"></div>
            <svg class="right-leaf-bg" viewBox="0 0 200 200" aria-hidden="true">
                <g fill="none" stroke="#b9c4b6" stroke-width="1.8">
                    <path d="M150 165c-4-32-21-61-49-87" />
                    <path d="M124 154c4-23 16-42 34-56" />
                    <path d="M101 79c16 2 30 10 42 22" />
                    <path d="M84 103c14 3 25 10 33 20" />
                </g>
            </svg>

            <div class="login-card">
                <div class="brand-top">
                    <div class="logo-badge">
                        <svg viewBox="0 0 64 64" aria-hidden="true">
                            <path d="M32 54V23" stroke="#2e7d32" stroke-width="2.8" stroke-linecap="round"/>
                            <path d="M32 34c-7.5 0-13.7-5.4-14.7-12.6 7.6 0 13.8 5.4 14.7 12.6z" fill="#2e7d32"/>
                            <path d="M32 34c7.5 0 13.7-5.4 14.7-12.6-7.6 0-13.8 5.4-14.7 12.6z" fill="#3da644"/>
                            <path d="M12 50c5-7 11-11 20-12" stroke="#2e7d32" stroke-width="2.8" stroke-linecap="round"/>
                            <path d="M52 50c-5-7-11-11-20-12" stroke="#2e7d32" stroke-width="2.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <p class="brand-title">TRAZABILIDAD</p>
                    <p class="brand-sub">AGRONÓMICA</p>
                    <div class="brand-rule"></div>
                </div>

                <div class="form-heading">
                    <h1>Iniciar sesión</h1>
                    <p>Accede a tu cuenta para continuar</p>
                </div>

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label field-label">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
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
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label field-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="••••••••"
                                required
                            >
                            <button class="btn toggle-password" type="button" id="togglePassword" aria-label="Mostrar u ocultar contraseña">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="meta-row">
                        <div class="form-check m-0">
                            <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                        @else
                            <a href="#" aria-disabled="true">¿Olvidaste tu contraseña?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-login">
                        Entrar <span class="arrow">&#8594;</span>
                    </button>
                </form>

                <div class="login-footer">
                    <i class="bi bi-flower3"></i>
                    © 2024 Trazabilidad Agronómica. Todos los derechos reservados.
                </div>
            </div>
        </section>
    </main>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        if (passwordInput && togglePassword) {
            togglePassword.addEventListener('click', function () {
                const hidden = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', hidden ? 'text' : 'password');
                this.innerHTML = hidden ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
            });
        }
    </script>
</body>
</html>
