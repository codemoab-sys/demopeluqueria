<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesión · {{ $empresaConfig->nombre ?? 'TPV Peluquería' }}</title>

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='1' y2='1'%3E%3Cstop offset='0' stop-color='%23ec4899'/%3E%3Cstop offset='1' stop-color='%237c3aed'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect rx='20' fill='url(%23g)' width='100' height='100'/%3E%3Ctext x='50' y='68' font-family='Arial' font-size='52' font-weight='bold' fill='white' text-anchor='middle'%3ET%3C/text%3E%3C/svg%3E">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            background: linear-gradient(135deg, #2a1c4a 0%, #4c1d95 50%, #831843 100%);
            min-height: 100vh;
            display: flex;
            padding: 24px 16px;
            color: #1f2937;
            overflow-x: hidden;
            overflow-y: auto;
            position: relative;
        }
        body::before, body::after {
            content: '';
            position: fixed;
            z-index: 0;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            pointer-events: none;
        }
        body::before {
            width: 500px; height: 500px;
            background: radial-gradient(circle, #ec4899, transparent);
            top: -150px; left: -100px;
            animation: float 8s ease-in-out infinite;
        }
        body::after {
            width: 600px; height: 600px;
            background: radial-gradient(circle, #a855f7, transparent);
            bottom: -200px; right: -150px;
            animation: float 10s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(40px, -30px); }
        }

        .login-wrapper {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            max-width: 1100px;
            width: 100%;
            margin: auto;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(20px);
            border-radius: 28px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            position: relative;
            z-index: 10;
            animation: rise 0.6s ease;
        }
        @keyframes rise {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-side {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            padding: 60px 50px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .login-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.15), transparent 50%),
                radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1), transparent 50%);
        }
        .login-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 2;
        }
        .login-logo {
            width: 56px; height: 56px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
        }
        .login-brand h2 {
            margin: 0; font-weight: 800; font-size: 22px;
            letter-spacing: -0.5px;
        }
        .login-brand p { margin: 0; font-size: 13px; opacity: 0.85; }

        .login-hero {
            position: relative; z-index: 2;
            margin: 50px 0;
        }
        .login-hero h1 {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1px;
            margin-bottom: 16px;
        }
        .login-hero p {
            font-size: 15px;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 380px;
        }

        .login-features {
            position: relative; z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .login-feature {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            font-weight: 600;
        }
        .login-feature i {
            font-size: 20px;
            width: 36px; height: 36px;
            background: rgba(255, 255, 255, 0.18);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }

        .login-form {
            padding: 60px 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-form h3 {
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .login-form .login-subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 32px;
        }

        .form-floating-modern { margin-bottom: 18px; position: relative; }
        .form-floating-modern label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap > i {
            position: absolute;
            left: 16px; top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
        }
        .form-control-modern {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
            background: #fafafa;
        }
        .form-control-modern:focus {
            outline: none;
            border-color: #a855f7;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.1);
        }
        .input-wrap .toggle-pass {
            position: absolute;
            right: 16px; top: 50%;
            transform: translateY(-50%);
            background: none; border: 0;
            color: #9ca3af; cursor: pointer;
            padding: 4px;
        }
        .input-wrap .toggle-pass:hover { color: #a855f7; }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            font-size: 13px;
        }
        .form-options label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4b5563;
            cursor: pointer;
        }
        .form-options input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #a855f7;
            cursor: pointer;
        }
        .form-options a { color: #a855f7; text-decoration: none; font-weight: 600; }
        .form-options a:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            color: #fff;
            border: 0;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(236, 72, 153, 0.4);
        }
        .btn-login:active { transform: translateY(0); }

        .btn-info-system {
            width: 100%;
            margin-top: 12px;
            padding: 13px 14px;
            background: #fff;
            color: #6b21a8;
            border: 1.5px solid rgba(168, 85, 247, 0.2);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-info-system:hover {
            background: rgba(168, 85, 247, 0.06);
            border-color: rgba(168, 85, 247, 0.35);
        }

        .demo-box {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.08), rgba(236, 72, 153, 0.08));
            border: 1px solid rgba(168, 85, 247, 0.16);
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 18px;
            font-size: 13px;
            color: #4b5563;
        }
        .demo-box strong {
            display: block;
            color: #6b21a8;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .login-footer {
            text-align: center;
            margin-top: 28px;
            color: #9ca3af;
            font-size: 12px;
        }
        .login-footer strong { color: #6b21a8; }

        .alert-error {
            background: rgba(239, 68, 68, 0.08);
            color: #b91c1c;
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-error i { margin-top: 2px; }

        @media (max-width: 900px) {
            .login-wrapper { grid-template-columns: 1fr; max-width: 480px; }
            .login-form { order: -1; }
            .login-side { padding: 40px 32px; }
            .login-hero { margin: 30px 0; }
            .login-hero h1 { font-size: 26px; }
            .login-features { grid-template-columns: 1fr; }
            .login-form { padding: 40px 32px; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Lado decorativo -->
        <div class="login-side">
            <div class="login-brand">
                <div class="login-logo"><i class="bi bi-scissors"></i></div>
                <div>
                    <h2>{{ $empresaConfig->nombre ?? 'TPV Peluquería' }}</h2>
                    <p>Sistema de gestión integral</p>
                </div>
            </div>

            <div class="login-hero">
                <h1>Bienvenido de vuelta a tu salón</h1>
                <p>Gestiona citas, clientes, ventas y mucho más desde un solo lugar. Tu peluquería, más organizada que nunca.</p>
            </div>

            <div class="login-features">
                <div class="login-feature"><i class="bi bi-calendar-check"></i> Agenda inteligente</div>
                <div class="login-feature"><i class="bi bi-cash-coin"></i> TPV integrado</div>
                <div class="login-feature"><i class="bi bi-people"></i> CRM clientes</div>
                <div class="login-feature"><i class="bi bi-graph-up"></i> Informes en vivo</div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="login-form">
            <h3>Iniciar sesión</h3>
            <p class="login-subtitle">Accede con tu cuenta para continuar</p>

            <div class="demo-box">
                <strong>Acceso demo</strong>
                Usuario: <code>demo</code><br>
                Correo: <code>demo@demo.com</code><br>
                Contraseña: <code>demo@demo.com</code>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf

                <div class="form-floating-modern">
                    <label for="email">Correo electrónico</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope-fill"></i>
                        <input type="email" id="email" name="email" class="form-control-modern"
                               placeholder="demo@demo.com" value="{{ old('email', 'demo@demo.com') }}" required autofocus>
                    </div>
                </div>

                <div class="form-floating-modern">
                    <label for="password">Contraseña</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" id="password" name="password" class="form-control-modern"
                               placeholder="••••••••" required>
                        <button type="button" class="toggle-pass" onclick="togglePass()">
                            <i class="bi bi-eye-fill" id="eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label>
                        <input type="checkbox" name="remember"> Mantener sesión
                    </label>
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
                </button>

                <button type="button" class="btn-info-system" data-bs-toggle="modal" data-bs-target="#sistemaModal">
                    <i class="bi bi-stars"></i> Ver qué incluye el sistema
                </button>
            </form>

            <div class="login-footer">
                © {{ date('Y') }} <strong>{{ $empresaConfig->nombre ?? 'TPV Peluquería' }}</strong> · Todos los derechos reservados
            </div>
        </div>
    </div>

    <div class="modal fade" id="sistemaModal" tabindex="-1" aria-labelledby="sistemaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); color: #fff;">
                    <div>
                        <h5 class="modal-title fw-bold" id="sistemaModalLabel">Software profesional para peluquerías y barberías</h5>
                        <p class="mb-0 small" style="opacity:.9;">Todo lo que necesitas para gestionar tu negocio en un solo lugar.</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4 p-md-5">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 h-100" style="background:#fdf2f8;">
                                <h6 class="fw-bold mb-3"><i class="bi bi-check2-circle text-success me-2"></i>Funcionalidades</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Agenda visual y control de citas</li>
                                    <li>TPV para cobrar servicios y productos</li>
                                    <li>Gestión de clientes con historial</li>
                                    <li>Control de caja, ventas e informes</li>
                                    <li>Inventario y stock de productos</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 h-100" style="background:#f5f3ff;">
                                <h6 class="fw-bold mb-3"><i class="bi bi-shield-check text-primary me-2"></i>Ventajas para tu negocio</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Centraliza toda la gestión en una sola plataforma</li>
                                    <li>Mejora la atención al cliente</li>
                                    <li>Ahorra tiempo en tareas diarias</li>
                                    <li>Controla mejor ventas y caja</li>
                                    <li>Ideal para peluquerías, barberías y salones</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-light border mt-4 mb-0 rounded-4">
                        <strong>Incluye:</strong> dashboard, agenda, TPV, clientes, servicios, productos, empleados, caja, ventas, informes, configuración y backups.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePass() {
            const input = document.getElementById('password');
            const eye = document.getElementById('eye');
            if (input.type === 'password') {
                input.type = 'text';
                eye.className = 'bi bi-eye-slash-fill';
            } else {
                input.type = 'password';
                eye.className = 'bi bi-eye-fill';
            }
        }
    </script>
</body>
</html>
