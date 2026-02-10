<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar nueva cita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/recepcionista/citas/create.css'])
</head>

<body>

    <div class="wrapper">

        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>
            <a href="{{ route('recepcionista.home') }}"
                class="nav-link {{ request()->routeIs('recepcionista.home') ? 'active' : '' }}">
                <i class="fas fa-user-md"></i> Mi Perfil
            </a>
            <a href="{{ route('secretaria.citas.index') }}"
                class="nav-link active {{ request()->routeIs('recpcionista.citas.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Citas
            </a>
            <a href="{{ route('secretaria.pacientes.index') }}"
                class="nav-link {{ request()->routeIs('secretaria.pacientes.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Pacientes
            </a>
            <a href="{{ route('profile.2fa') }}"
                class="nav-link {{ request()->routeIs('profile.2fa') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i> Seguridad 2FA
            </a>


            <div class="user">
                <strong>{{ Auth::user()->nombre }}</strong><br>
                <small>{{ Auth::user()->nombre_rol }}</small>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
                </form>
            </div>
        </aside>

        <main class="content">

            <h4 class="fw-bold mb-2">Registrar nueva cita</h4>
            <p class="text-muted mb-4">Aquí puedes gestionar tus próximas citas.</p>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    ✅ {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif



            <div class="panel mb-4">
                <h5 class="fw-bold mb-3">Buscar Cliente</h5>

                <form method="GET" action="{{ route('secretaria.citas.create') }}" class="row g-3">

                    <div class="col-md-8">
                        <input type="text" name="cedula" maxlength="10" class="form-control"
                            placeholder="Ingrese la cédula del cliente" value="{{ request('cedula') }}" required>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-gold w-100">Buscar</button>
                    </div>

                    <div class="col-md-2">
                        <a href="{{ route('secretaria.pacientes.create') }}" class="btn btn-outline-secondary w-100">
                            Crear
                        </a>
                    </div>
                </form>
                @if (session('paciente_no_encontrado'))
                    <div class="alert alert-warning d-flex justify-content-between align-items-center mt-3">
                        <div>
                            ❌ No existe ningún paciente con la cédula
                            <strong>{{ session('paciente_no_encontrado') }}</strong>
                        </div>

                        <a href="{{ route('secretaria.pacientes.create', ['cedula' => session('paciente_no_encontrado')]) }}"
                            class="btn btn-gold">
                            Crear Paciente
                        </a>
                    </div>
                @endif


            </div>


            <div class="panel">

                <form method="POST" action="{{ route('secretaria.citas.store') }}">
                    @csrf

                    <div class="row g-4">


                        <div class="col-md-6">
                            <span class="section-title">1. Datos del Paciente</span>

                            <input type="hidden" name="paciente_id" value="{{ optional($paciente ?? null)->id }}">

                            <div class="mt-3">
                                <label>Cédula / DNI</label>
                                <input class="form-control" value="{{ optional($paciente ?? null)->cedula }}" disabled>
                            </div>

                            <div class="mt-3">
                                <label>Nombre y Apellido</label>
                                <input class="form-control"
                                    value="{{ optional($paciente ?? null)->nombres }} {{ optional($paciente ?? null)->apellidos }}"
                                    disabled>
                            </div>

                            <div class="mt-3">
                                <label>Teléfono</label>
                                <input class="form-control" value="{{ optional($paciente ?? null)->telefono }}"
                                    disabled>
                            </div>

                            <div class="mt-3">
                                <label>Correo Electrónico</label>
                                <input class="form-control" value="{{ optional($paciente ?? null)->email }}" disabled>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <span class="section-title">2. Detalles de la Cita</span>

                            <div class="mt-3">
                                <label>Especialidad</label>
                                <select class="form-select" name="especialidad_id" required>
                                    @foreach ($especialidades ?? [] as $e)
                                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-3">
                                <label>Doctor Asignado</label>
                                <select class="form-select" name="doctor_id" required>
                                    @foreach ($doctores ?? [] as $d)
                                        <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-3">
                                <label>Fecha y Hora Inicio</label>
                                <input type="datetime-local" name="fecha_inicio" class="form-control" required>
                            </div>

                            <div class="mt-3">
                                <label>Motivo de Consulta</label>
                                <textarea name="motivo" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('secretaria.citas.create') }}" class="btn btn-light">
                            Cancelar
                        </a>

                        <button class="btn btn-gold ms-2">
                            Confirmar y Agendar
                        </button>
                    </div>

                </form>
            </div>

        </main>
    </div>

</body>

</html>
