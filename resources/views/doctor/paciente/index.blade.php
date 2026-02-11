<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Directorio de Pacientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    @vite(['resources/css/doctor/paciente/index.css'])
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
            <h4 class="fw-bold mb-3">Directorio de Pacientes</h4>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="panel">

                        <input type="text" class="form-control mb-3" placeholder="Buscar por nombre o cédula">

                        @if ($pacientes->isEmpty())
                        <div class="text-center mt-5">
                            <p class="text-muted">No hay pacientes registrados</p>
                            <a href="{{ route('doctor.pacientes.create') }}" class="btn btn-gold">
                                Crear Paciente
                            </a>
                        </div>
                        @else
                        <div style="max-height: 65vh; overflow-y: auto;">
                            @foreach ($pacientes as $p)
                            <a href="{{ route('doctor.pacientes.index', ['paciente' => $p->id]) }}"
                                class="paciente-item
                                   {{ optional($pacienteSeleccionado)->id === $p->id ? 'active' : '' }}">

                                <div class="avatar">
                                    {{ strtoupper(substr($p->nombres, 0, 1)) }}
                                </div>

                                <div>
                                    <strong>{{ $p->nombres }} {{ $p->apellidos }}</strong><br>
                                    <small>ID: {{ $p->cedula }}</small>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        <div class="text-center mt-3 pt-3 border-top">
                            <a href="{{ route('doctor.pacientes.create') }}" class="btn btn-gold w-100">
                                + Crear Paciente
                            </a>
                        </div>

                        @endif
                    </div>
                </div>


                <div class="col-md-8">
                    <div class="panel">

                        @if (!$pacienteSeleccionado)
                        <div class="text-center mt-5 text-muted">
                            Selecciona un paciente del listado
                        </div>
                        @else
                        <h5 class="fw-bold mb-3">Información del Paciente</h5>

                        <div class="card bg-light border-0 mb-4 p-3 rounded-3">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-stethoscope"></i> Acciones Clínicas
                            </h6>

                            <a href="{{ route('historia_clinica.create', ['paciente_id' => $pacienteSeleccionado->id]) }}"
                                class="btn btn-success w-100 mb-2 py-2 shadow-sm text-white fw-bold">
                                <i class="fas fa-plus-circle me-2"></i> Nueva Historia Clínica
                            </a>

                            <div class="d-flex gap-2">
                                <a href="{{ route('doctor.pacientes.citas', $pacienteSeleccionado->id) }}"
                                    class="btn btn-outline-primary flex-grow-1 bg-white">
                                    📅 Ver Citas
                                </a>

                                <a href="{{ route('doctor.pacientes.historia', $pacienteSeleccionado->id) }}"
                                    class="btn btn-outline-secondary flex-grow-1 bg-white">
                                    📋 Ver Historiales
                                </a>
                            </div>
                        </div>

                        <form method="POST"
                            action="{{ route('doctor.pacientes.update', $pacienteSeleccionado->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label>Cédula</label>
                                <input class="form-control" disabled value="{{ $pacienteSeleccionado->cedula }}">
                            </div>

                            <div class="mb-3">
                                <label>Nombre Completo</label>
                                <input class="form-control"
                                    value="{{ $pacienteSeleccionado->nombres }} {{ $pacienteSeleccionado->apellidos }}"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label>Teléfono</label>
                                <input class="form-control" name="telefono"
                                    value="{{ $pacienteSeleccionado->telefono }}">
                            </div>

                            <div class="mb-3">
                                <label>Correo Electrónico</label>
                                <input class="form-control" name="email"
                                    value="{{ $pacienteSeleccionado->email }}">
                            </div>

                            <div class="mb-4">
                                <label>Dirección / Notas</label>
                                <textarea class="form-control" name="direccion">{{ $pacienteSeleccionado->direccion }}</textarea>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('doctor.pacientes.index') }}" class="btn btn-light">
                                    Cancelar
                                </a>

                                <button class="btn btn-gold ms-2">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                        @endif

                    </div>
                </div>

            </div>
        </main>
    </div>

</body>

</html>