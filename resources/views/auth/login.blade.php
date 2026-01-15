<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            overflow-x: hidden;
        }

        /* ===== FONDO ===== */
        .background-container {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #2c7fb8 0%, #4a9fd8 50%, #6bb9e8 100%);
            z-index: -1;
        }

        /* ===== OLA ===== */
.wave-decoration {
    position: absolute;
    top: 45%;
    left: 0;
    width: 100%;
    height: 55%;
    overflow: hidden;
}
        .wave-decoration svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* ===== CONTENEDOR LOGIN ===== */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 50px 40px;
            max-width: 420px;
            width: 100%;
            border: 3px dotted #e0e0e0;
            position: relative;
            z-index: 2;
        }

        /* ===== ICONO ===== */
        .icon-circle {
            width: 70px;
            height: 70px;
            background: white;
            border: 3px solid #f4c430;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 4px 15px rgba(244, 196, 48, 0.2);
        }

        .icon-circle svg {
            width: 35px;
            height: 35px;
            stroke: #f4c430;
            fill: none;
            stroke-width: 2;
        }

        /* ===== TEXTO ===== */
        .login-title {
            text-align: center;
            color: #1a5490;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-subtitle {
            text-align: center;
            color: #999;
            font-size: 14px;
            margin-bottom: 35px;
        }

        /* ===== FORM ===== */
        .form-label {
            color: #555;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #2c7fb8;
            box-shadow: 0 0 0 3px rgba(44, 127, 184, 0.1);
        }

        .form-check-input:checked {
            background-color: #2c7fb8;
            border-color: #2c7fb8;
        }

        /* ===== BOTÓN ===== */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #1a5490 0%, #2c7fb8 100%);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            text-transform: uppercase;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(44, 127, 184, 0.3);
        }

        .link-text {
            color: #2c7fb8;
            text-decoration: none;
            font-size: 13px;
        }

        .link-text:hover {
            color: #1a5490;
        }

        .register-link {
            color: #f4c430;
            font-weight: 600;
        }
    </style>
</head>

<body>

<!-- ===== FONDO + OLA ===== -->
<div class="background-container">
    <div class="wave-decoration">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path
                fill="#ffffff"
                d="
                    M0,160
                    C240,160 480,240 720,240
                    C960,240 1200,160 1440,160
                    L1440,320 L0,320 Z
                ">
            </path>
        </svg>
    </div>
</div>

<!-- ===== LOGIN ===== -->
<div class="login-container">
    <div class="login-card">

        <div class="icon-circle">
            <svg viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10
                         10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0
                         3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3
                         1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22
                         .03-1.99 4-3.08 6-3.08 1.99 0 5.97
                         1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
            </svg>
        </div>

        <h2 class="login-title">Bienvenido</h2>
        <p class="login-subtitle">Ingresa tus credenciales para continuar</p>

         {{-- ERRORES LARAVEL --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 🔑 FORMULARIO FUNCIONAL -->
        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>
                <a href="{{ route('password.request') }}" class="link-text">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button type="submit" class="btn btn-login">Iniciar sesión</button>

            <div class="text-center mt-3">
                <span style="font-size: 13px;">¿No tienes cuenta?</span>
                <a href="{{ url('/register') }}" class="register-link">Regístrate</a>
            </div>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>