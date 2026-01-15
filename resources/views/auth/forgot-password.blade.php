<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>

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

        /* ===== CONTENEDOR ===== */
        .recover-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .recover-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 40px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
            z-index: 2;
        }

        h2 {
            color: #1a5490;
            font-weight: 700;
        }

        .form-control {
            border-radius: 8px;
            font-size: 14px;
            padding: 12px 15px;
        }

        .btn-recover {
            width: 100%;
            background: linear-gradient(135deg, #1a5490, #2c7fb8);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-recover:hover {
            box-shadow: 0 8px 18px rgba(44,127,184,.3);
            transform: translateY(-1px);
        }

        .link-login {
            font-size: 13px;
            color: #f4c430;
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>

<body>

<!-- FONDO + OLA -->
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

<!-- RECUPERAR CONTRASEÑA -->
<div class="recover-container">
    <div class="recover-card">

        <h2 class="text-center mb-4">Recuperar contraseña</h2>

        {{-- MENSAJE DE ÉXITO --}}
        @if (session('status'))
            <div class="alert alert-success text-center">
                {{ session('status') }}
            </div>
        @endif

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
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="usuario@ejemplo.com"
                       required>
            </div>

            <button type="submit" class="btn btn-recover mb-3">
                Enviar enlace
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="link-login">
                Volver a iniciar sesión
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
