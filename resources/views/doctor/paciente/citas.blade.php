<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Citas del Paciente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/doctor/paciente/citas.css'])
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
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>


        <main class="content">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-0">Citas del Paciente</h4>
                    <small class="text-muted">
                        {{ $paciente->nombres }} {{ $paciente->apellidos }} — {{ $paciente->cedula }}
                    </small>
                </div>

                <a href="{{ route('doctor.pacientes.index', ['paciente' => $paciente->id]) }}"
                    class="btn btn-outline-secondary">
                    ⬅ Volver
                </a>
            </div>

            <div class="panel">

                @if ($paciente->citas->isEmpty())
                <div class="text-center text-muted py-5">
                    Este paciente no tiene citas registradas.
                </div>
                @else
                @foreach ($paciente->citas as $cita)
                <div class="cita-card">

                    <div class="d-flex justify-content-between mb-2">
                        <strong>{{ $cita->especialidad->nombre }}</strong>
                        <span class="badge-estado">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Doctor</small><br>
                            {{ $cita->doctor->nombre }}
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted">Inicio</small><br>
                            {{ \Carbon\Carbon::parse($cita->fecha_inicio)->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted">Motivo</small><br>
                        {{ $cita->motivo ?? '—' }}
                    </div>

                </div>
                @endforeach
                @endif

            </div>

        </main>
    </div>

</body>

</html>