<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar nueva cita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

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
            color: #fff;
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
        }

        .panel {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
            padding: 30px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid #e0b23f;
            display: inline-block;
            padding-bottom: 5px;
        }

        .btn-gold {
            background: #e0b23f;
            color: #fff;
            border-radius: 25px;
            border: none;
            padding: 10px 35px;
        }

        .btn-gold:hover {
            background: #c89b2d;
        }

        input, textarea, select {
            border-radius: 10px !important;
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
        </div>

        <a href="{{ route('home') }}">👤 Mi Perfil</a>
        <a href="{{ route('citas.create') }}" class="active">📅 Citas</a>
        <a href="{{ route('pacientes.index') }}">👥 Pacientes</a>

        <div class="user">
            <strong>{{ Auth::user()->nombre }}</strong><br>
            <small>{{ Auth::user()->nombre_rol }}</small>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="content">

        <h4 class="fw-bold mb-2">Registrar nueva cita</h4>
        <p class="text-muted mb-4">Aquí puedes gestionar tus próximas citas.</p>

        <!-- BUSCAR CLIENTE -->
        <div class="panel mb-4">
            <h5 class="fw-bold mb-3">Buscar Cliente</h5>

            <form method="POST" action="{{ route('citas.buscarPaciente') }}" class="row g-3">
                @csrf

                <div class="col-md-8">
                    <input type="text"
                           name="cedula"
                           class="form-control"
                           placeholder="Ingrese la cédula del cliente"
                           required>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-gold w-100">Buscar</button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('pacientes.create') }}"
                       class="btn btn-outline-secondary w-100">
                        Crear
                    </a>
                </div>
            </form>
            @if(isset($paciente_no_encontrado))
    <div class="alert alert-warning d-flex justify-content-between align-items-center mt-3">
        <div>
            ❌ No existe ningún paciente con la cédula
            <strong>{{ $paciente_no_encontrado }}</strong>
        </div>

        <a href="{{ route('pacientes.create') }}?cedula={{ $paciente_no_encontrado }}"
           class="btn btn-gold">
            Crear Paciente
        </a>
    </div>
@endif

        </div>

        <!-- FORMULARIO -->
        <div class="panel">

            <form method="POST" action="{{ route('citas.store') }}">
                @csrf

                <div class="row g-4">

                    <!-- DATOS PACIENTE -->
                    <div class="col-md-6">
                        <span class="section-title">1. Datos del Paciente</span>

                        <input type="hidden" name="paciente_id"
                               value="{{ optional($paciente)->id }}">

                        <div class="mt-3">
                            <label>Cédula / DNI</label>
                            <input class="form-control"
                                   value="{{ optional($paciente)->cedula }}"
                                   disabled>
                        </div>

                        <div class="mt-3">
                            <label>Nombre y Apellido</label>
                            <input class="form-control"
                                   value="{{ optional($paciente)->nombres }} {{ optional($paciente)->apellidos }}"
                                   disabled>
                        </div>

                        <div class="mt-3">
                            <label>Teléfono</label>
                            <input class="form-control"
                                   value="{{ optional($paciente)->telefono }}"
                                   disabled>
                        </div>

                        <div class="mt-3">
                            <label>Correo Electrónico</label>
                            <input class="form-control"
                                   value="{{ optional($paciente)->email }}"
                                   disabled>
                        </div>
                    </div>

                    <!-- DETALLES CITA -->
                    <div class="col-md-6">
                        <span class="section-title">2. Detalles de la Cita</span>

                        <div class="mt-3">
                            <label>Especialidad</label>
                            <select class="form-select" name="especialidad_id" required>
                                @foreach($especialidades as $e)
                                    <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-3">
                            <label>Doctor Asignado</label>
                            <select class="form-select" name="doctor_id" required>
                                @foreach($doctores as $d)
                                    <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-3">
                            <label>Fecha y Hora Inicio</label>
                            <input type="datetime-local"
                                   name="fecha_inicio"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mt-3">
                            <label>Fecha y Hora Fin</label>
                            <input type="datetime-local"
                                   name="fecha_fin"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mt-3">
                            <label>Motivo de Consulta</label>
                            <textarea name="motivo"
                                      class="form-control"
                                      rows="3"></textarea>
                        </div>
                    </div>

                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('citas.create') }}" class="btn btn-light">
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
