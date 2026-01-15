<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas del Paciente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', sans-serif;
        }

        .wrapper { display: flex; min-height: 100vh; }

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

        /* CONTENT */
        .content {
            flex: 1;
            padding: 30px;
        }

        .panel {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,.12);
            padding: 25px;
        }

        .cita-card {
            border: 1px solid #e6e6e6;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .badge-estado {
            background: #e0b23f;
            color: #fff;
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 13px;
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
            <small>{{ Auth::user()->nombre_rol }}</small>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="content">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0">Citas del Paciente</h4>
                <small class="text-muted">
                    {{ $paciente->nombres }} {{ $paciente->apellidos }} — {{ $paciente->cedula }}
                </small>
            </div>

            <a href="{{ route('pacientes.index', ['paciente' => $paciente->id]) }}"
               class="btn btn-outline-secondary">
                ⬅ Volver
            </a>
        </div>

        <div class="panel">

            @if($paciente->citas->isEmpty())
                <div class="text-center text-muted py-5">
                    Este paciente no tiene citas registradas.
                </div>
            @else
                @foreach($paciente->citas as $cita)
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

                            <div class="col-md-4">
                                <small class="text-muted">Fin</small><br>
                                {{ \Carbon\Carbon::parse($cita->fecha_fin)->format('d/m/Y H:i') }}
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
