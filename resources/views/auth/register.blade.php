<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto;
            overflow-x: hidden;
        }

        /* ===== FONDO ===== */
        .background-container {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #2c7fb8, #4a9fd8, #6bb9e8);
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

        /* ===== CONTENEDOR ===== */
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 45px 40px;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0,0,0,.15);
            border: 3px dotted #e0e0e0;
            z-index: 2;
            position: relative;
        }

        /* ===== ICONO ===== */
        .icon-circle {
            width: 70px;
            height: 70px;
            border: 3px solid #f4c430;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 4px 15px rgba(244,196,48,.25);
        }

        .icon-circle svg {
            width: 35px;
            height: 35px;
            stroke: #f4c430;
            fill: none;
            stroke-width: 2;
        }

        /* ===== TEXTO ===== */
        .register-title {
            text-align: center;
            color: #1a5490;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .register-subtitle {
            text-align: center;
            font-size: 14px;
            color: #999;
            margin-bottom: 25px;
        }

        .btn-register {
            width: 100%;
            background: linear-gradient(135deg, #1a5490, #2c7fb8);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .btn-register:hover {
            box-shadow: 0 8px 20px rgba(44,127,184,.3);
            transform: translateY(-2px);
        }

        .link-login {
            color: #f4c430;
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>

<body>

<!-- FONDO -->
<div class="background-container">
    <div class="wave-decoration">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#ffffff"
                d="M0,160
                   C240,160 480,240 720,240
                   C960,240 1200,160 1440,160
                   L1440,320 L0,320 Z">
            </path>
        </svg>
    </div>
</div>

<!-- REGISTRO -->
<div class="register-container">
    <div class="register-card">

        <div class="icon-circle">
            <svg viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10
                         10-4.48 10-10S17.52 2 12 2z"/>
            </svg>
        </div>

        <h2 class="register-title">Crear Cuenta</h2>
        <p class="register-subtitle">Únete a nuestro sistema</p>

        {{-- ERRORES --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORMULARIO FUNCIONAL -->
        <form method="POST" action="{{ url('/register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nombre Completo</label>
                <input type="text"
                       name="nombre"
                       value="{{ old('nombre') }}"
                       class="form-control @error('nombre') is-invalid @enderror"
                       required autofocus>
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono (opcional)</label>
                <input type="text"
                       name="tel"
                       value="{{ old('tel') }}"
                       class="form-control @error('tel') is-invalid @enderror">
                @error('tel') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar Contraseña</label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Rol (opcional)</label>
                <select name="rol"
                        class="form-select @error('rol') is-invalid @enderror">
                    <option value="3" selected>Recepcionista</option>
                    <option value="2">Doctor</option>
                    <option value="1">Administrador</option>
                </select>
                @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Por defecto se asigna Recepcionista</small>
            </div>

            <button type="submit" class="btn btn-register">Registrarse</button>

            <div class="text-center mt-3">
                ¿Ya tienes cuenta?
                <a href="{{ url('/login') }}" class="link-login">Inicia Sesión</a>
            </div>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
