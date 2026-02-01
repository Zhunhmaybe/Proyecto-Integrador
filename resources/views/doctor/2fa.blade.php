<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Seguridad 2FA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/profile/two-factor.css'])
</head>

<body>

    <div class="wrapper">


        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <a href="{{ route('doctor.dashboard') }}" >🧑⚕️Mi perfil</a>
            <a href="{{ route('doctor.pacientes.index') }}">🧑Pacientes</a>
            <a href="{{ route('doctor.citas.index') }}">📅 Citas</a>
            <a href="{{ route('historia_clinica.index') }}">📋Historial Clinico</a>
            <a href="{{ route('profile.2fa') }}" class="active">🔐 Seguridad 2FA</a>

            <div class="user">
                <strong>{{ Auth::user()->nombre }}</strong><br>
                <small>{{Auth::user()->nombre_rol}}</small>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-light btn-sm w-100">Cerrar Sesión</button>
                </form>
            </div>
        </aside>


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
                    <a href="{{ route('doctor.dashboard') }}" class="btn-link-soft">Volver</a>
                    @endif

                </div>
            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>