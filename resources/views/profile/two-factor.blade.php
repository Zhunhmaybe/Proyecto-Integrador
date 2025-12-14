<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración 2FA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mi Aplicación</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->nombre }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.2fa') }}">Seguridad 2FA</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Autenticación de Dos Factores (2FA)</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="mb-4">
                            <h5>Estado actual:</h5>
                            @if(Auth::user()->two_factor_enabled)
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-shield-check"></i> Habilitado
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">
                                    <i class="bi bi-shield-x"></i> Deshabilitado
                                </span>
                            @endif
                        </div>

                        <div class="alert alert-info">
                            <h5 class="alert-heading">¿Qué es la autenticación de dos factores?</h5>
                            <p class="mb-0">
                                La autenticación de dos factores añade una capa extra de seguridad a tu cuenta.
                                Cuando está habilitada, se te enviará un código de 6 dígitos a tu email cada vez que inicies sesión.
                            </p>
                        </div>

                        @if(Auth::user()->two_factor_enabled)
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">2FA está activo</h5>
                                    <p class="card-text">
                                        Tu cuenta está protegida con autenticación de dos factores.
                                        Se te enviará un código a <strong>{{ Auth::user()->email }}</strong> cada vez que inicies sesión.
                                    </p>
                                    <form method="POST" action="{{ route('profile.2fa.disable') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de querer deshabilitar 2FA?')">
                                            Deshabilitar 2FA
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Habilitar 2FA</h5>
                                    <p class="card-text">
                                        Protege tu cuenta habilitando la autenticación de dos factores.
                                        Recibirás códigos de verificación en <strong>{{ Auth::user()->email }}</strong>.
                                    </p>
                                    <form method="POST" action="{{ route('profile.2fa.enable') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-shield-lock"></i> Habilitar 2FA
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4">
                            <h5>Beneficios de 2FA:</h5>
                            <ul>
                                <li>Mayor seguridad para tu cuenta</li>
                                <li>Protección contra accesos no autorizados</li>
                                <li>Notificación instantánea de intentos de inicio de sesión</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
