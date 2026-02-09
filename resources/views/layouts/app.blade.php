<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'DentalSoft')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/home.css'])
    
    @stack('styles')
</head>
<body>

    <div class="wrapper">


        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <nav>
                <a href="{{ route('doctor.dashboard') }}"
                    class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-user-md"></i> Mi Perfil
                </a>

                <a href="{{ route('doctor.pacientes.index') }}"
                    class="nav-link {{ request()->routeIs('doctor.pacientes.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Pacientes
                </a>

                <a href="{{ route('doctor.citas.index') }}"
                    class="nav-link {{ request()->routeIs('doctor.citas.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Citas
                </a>

                <a href="{{ route('historia_clinica.index') }}"
                    class="nav-link {{ request()->routeIs('historia_clinica.*') ? 'active' : '' }}">
                    <i class="fas fa-file-medical"></i> Historial Clínico
                </a>

                <a href="{{ route('profile.2fa') }}"
                    class="nav-link {{ request()->routeIs('profile.2fa') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt"></i> Seguridad 2FA
                </a>
            </nav>

            <div class="user">
                <strong>{{ Auth::user()->nombre }}</strong><br>
                <small>{{ Auth::user()->nombre_rol }}</small>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-outline-light w-100 border-0 text-start ps-0">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Cerrar Sesión
                    </button>

                </form>
            </div>
        </aside>

        <main class="content">
            @yield('content')
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')

</body>
</html>