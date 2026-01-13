<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seguridad 2FA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        /* ===== LAYOUT ===== */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: #0b4f79;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .sidebar .logo img {
            width: 110px;
        }

        .sidebar a {
            color: #cfe6f5;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background: rgba(255,255,255,0.15);
            color: white;
        }

        .sidebar .user {
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 15px;
            font-size: 13px;
        }

        /* ===== CONTENT ===== */
        .content {
            flex: 1;
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ===== CARD ===== */
        .card-box {
            background: white;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            max-width: 900px;
            width: 100%;
            overflow: hidden;
        }

        .card-header-custom {
            background: #b7d8ee;
            padding: 20px 30px;
        }

        .card-body-custom {
            padding: 30px;
        }

        .btn-primary-custom {
            background: #0b4f79;
            border: none;
            border-radius: 20px;
            padding: 8px 30px;
            color: white;
        }

        .btn-primary-custom:hover {
            background: #083d5f;
        }

        .btn-warning-custom {
            background: #e0b23f;
            border: none;
            border-radius: 20px;
            padding: 8px 30px;
            color: white;
        }

        .btn-warning-custom:hover {
            background: #c89b2d;
        }

        .btn-danger-custom {
            background: #dc3545;
            border: none;
            border-radius: 20px;
            padding: 8px 30px;
            color: white;
        }

        .badge-status {
            font-size: 14px;
            padding: 8px 15px;
            border-radius: 20px;
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
            <p class="mt-2 fw-bold">
                DANNY LARA<br>
                <small>Dental Solutions</small>
            </p>
        </div>

        <a href="{{ route('home') }}">👤 Mi Perfil</a>

        <div class="user">
            <strong>{{ Auth::user()->nombre }}</strong><br>
            <small>Recepcionista</small>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="btn btn-light btn-sm w-100">Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="content">

        <h3 class="fw-bold mb-4" style="max-width:900px;width:100%">
            Seguridad – Autenticación 2FA
        </h3>

        <div class="card-box">

            <div class="card-header-custom">
                <h4 class="mb-0">Autenticación de Dos Factores (2FA)</h4>
            </div>

            <div class="card-body-custom">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="mb-4">
                    <strong>Estado actual:</strong><br><br>
                    @if(Auth::user()->two_factor_enabled)
                        <span class="badge bg-success badge-status">Habilitado</span>
                    @else
                        <span class="badge bg-secondary badge-status">Deshabilitado</span>
                    @endif
                </div>

                <div class="alert alert-info">
                    <strong>¿Qué es 2FA?</strong><br>
                    Añade una capa extra de seguridad.  
                    Se enviará un código de 6 dígitos a tu correo
                    <strong>{{ Auth::user()->email }}</strong>
                    en cada inicio de sesión.
                </div>

                @if(Auth::user()->two_factor_enabled)
                    <div class="mt-4">
                        <p>Tu cuenta está protegida con 2FA.</p>
                        <form method="POST" action="{{ route('profile.2fa.disable') }}">
                            @csrf
                            <button type="submit"
                                    class="btn-danger-custom"
                                    onclick="return confirm('¿Deshabilitar 2FA?')">
                                Deshabilitar 2FA
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-4">
                        <p>Activa 2FA para proteger tu cuenta.</p>
                        <form method="POST" action="{{ route('profile.2fa.enable') }}">
                            @csrf
                            <button type="submit" class="btn-primary-custom">
                                Habilitar 2FA
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
