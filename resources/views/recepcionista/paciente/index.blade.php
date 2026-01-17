<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Directorio de Pacientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', sans-serif;
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
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .sidebar .user {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 15px;
            font-size: 13px;
        }


        /* CONTENT */
        .content {
            flex: 1;
            padding: 30px;
        }

        .panel {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .12);
            padding: 20px;
            height: 80vh;
        }

        /* LISTA PACIENTES */
        .paciente-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            color: #333;
        }

        .paciente-item:hover,
        .paciente-item.active {
            background: #e9f2fb;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .btn-gold {
            background: #e0b23f;
            color: #fff;
            border-radius: 25px;
            border: none;
            padding: 8px 30px;
        }

        .btn-gold:hover {
            background: #c89b2d;
        }

        input,
        textarea {
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
            <a href="{{ route('citas.create') }}">📅 Citas</a>
            <a href="{{ route('pacientes.index') }}" class="active">👥 Pacientes</a>


            <div class="user">
                <strong>{{ Auth::user()->nombre }}</strong><br>
                <small>{{Auth::user()->nombre_rol}}</small>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
                </form>
            </div>
        </aside>

        <!-- CONTENT -->
        <main class="content">
            <h4 class="fw-bold mb-3">Directorio de Pacientes</h4>

            <div class="row g-4">

                <!-- IZQUIERDA -->
                <div class="col-md-4">
                    <div class="panel">

                        <input type="text" class="form-control mb-3"
                            placeholder="Buscar por nombre o cédula">

                        @if($pacientes->isEmpty())
                        <div class="text-center mt-5">
                            <p class="text-muted">No hay pacientes registrados</p>
                            <a href="{{ route('pacientes.create') }}"
                                class="btn btn-gold">
                                Crear Paciente
                            </a>
                        </div>
                        @else
                        <div style="max-height: 65vh; overflow-y: auto;">
                            @foreach($pacientes as $p)
                            <a href="{{ route('pacientes.index', ['paciente' => $p->id]) }}"
                                class="paciente-item
                                   {{ optional($pacienteSeleccionado)->id === $p->id ? 'active' : '' }}">

                                <div class="avatar">
                                    {{ strtoupper(substr($p->nombres,0,1)) }}
                                </div>

                                <div>
                                    <strong>{{ $p->nombres }} {{ $p->apellidos }}</strong><br>
                                    <small>ID: {{ $p->cedula }}</small>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        <div class="text-center mt-3 pt-3 border-top">
                            <a href="{{ route('pacientes.create') }}"
                                class="btn btn-gold w-100">
                                + Crear Paciente
                            </a>
                        </div>

                        @endif
                    </div>
                </div>

                <!-- DERECHA -->
                <div class="col-md-8">
                    <div class="panel">

                        @if(!$pacienteSeleccionado)
                        <div class="text-center mt-5 text-muted">
                            Selecciona un paciente del listado
                        </div>
                        @else
                        <h5 class="fw-bold mb-3">Información del Paciente</h5>
                        <a href="{{ route('pacientes.citas', $pacienteSeleccionado->id) }}"
                            class="btn btn-outline-primary mb-3">
                            📅 Ver Citas del Paciente
                        </a>



                        <form method="POST"
                            action="{{ route('pacientes.update', $pacienteSeleccionado->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label>Cédula</label>
                                <input class="form-control" disabled
                                    value="{{ $pacienteSeleccionado->cedula }}">
                            </div>

                            <div class="mb-3">
                                <label>Nombre Completo</label>
                                <input class="form-control"
                                    value="{{ $pacienteSeleccionado->nombres }} {{ $pacienteSeleccionado->apellidos }}"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label>Teléfono</label>
                                <input class="form-control"
                                    name="telefono"
                                    value="{{ $pacienteSeleccionado->telefono }}">
                            </div>

                            <div class="mb-3">
                                <label>Correo Electrónico</label>
                                <input class="form-control"
                                    name="email"
                                    value="{{ $pacienteSeleccionado->email }}">
                            </div>

                            <div class="mb-4">
                                <label>Dirección / Notas</label>
                                <textarea class="form-control"
                                    name="direccion">{{ $pacienteSeleccionado->direccion }}</textarea>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('pacientes.index') }}"
                                    class="btn btn-light">
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