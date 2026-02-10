<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/admin/citas/edit-citas.css'])
</head>

<body>

<div class="wrapper">

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
        </div>

        <nav>
            <a href="{{ route('admin.dashboard') }}" class="nav-link icon-profile">
                Mi perfil
            </a>

            <a href="{{ route('admin.pacientes.index') }}" class="nav-link icon-pacientes">
                Pacientes
            </a>

            <a href="{{ route('admin.doctores.index') }}" class="nav-link icon-doctores">
                Doctores
            </a>

            <a href="{{ route('admin.especialidades.index') }}" class="nav-link icon-especialidades">
                Especialidades
            </a>

            <a href="{{ route('admin.usuarios.index') }}" class="nav-link icon-users">
                Usuarios
            </a>

            <a href="{{ route('admin.citas.create') }}" class="nav-link icon-citas active">
                Citas
            </a>

            <a href="{{ route('admin.roles.index') }}" class="nav-link icon-roles">
                Roles
            </a>

            <a href="{{ route('profile.2fa') }}" class="nav-link icon-seguridad">
                Seguridad 2FA
            </a>
        </nav>

        <div class="user">
            <strong>{{ Auth::user()->nombre }}</strong><br>
            <small>{{ Auth::user()->nombre_rol }}</small>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    <!-- ===== CONTENT ===== -->
    <main class="content">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0">✏️ Editar Cita</h4>
                <small class="text-muted">
                    {{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}
                </small>
            </div>

            <a href="{{ route('admin.pacientes.index') }}" class="btn btn-outline-secondary">
                ⬅ Volver
            </a>
        </div>

        <!-- PANEL -->
        <div class="panel">

            {{-- ERRORES --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORMULARIO --}}
            <form method="POST" action="{{ route('admin.citas.update', $cita->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Paciente</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}"
                               disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Doctor</label>
                        <select name="doctor_id" class="form-select" required>
                            @foreach($doctores as $doctor)
                                <option value="{{ $doctor->id }}"
                                    {{ $cita->doctor_id == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Especialidad</label>
                        <select name="especialidad_id" class="form-select" required>
                            @foreach($especialidades as $esp)
                                <option value="{{ $esp->id }}"
                                    {{ $cita->especialidad_id == $esp->id ? 'selected' : '' }}>
                                    {{ $esp->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="pendiente" {{ $cita->estado=='pendiente'?'selected':'' }}>
                                Pendiente
                            </option>
                            <option value="confirmada" {{ $cita->estado=='confirmada'?'selected':'' }}>
                                Confirmada
                            </option>
                            <option value="cancelada" {{ $cita->estado=='cancelada'?'selected':'' }}>
                                Cancelada
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fecha y Hora</label>
                        <input type="datetime-local"
                               name="fecha_inicio"
                               class="form-control"
                               value="{{ $cita->fecha_inicio->format('Y-m-d\TH:i') }}"
                               required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Motivo</label>
                        <textarea name="motivo"
                                  class="form-control"
                                  rows="3">{{ $cita->motivo }}</textarea>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button class="btn btn-gold">
                        Guardar Cambios
                    </button>
                </div>

            </form>

        </div>

    </main>
</div>

</body>
</html>
