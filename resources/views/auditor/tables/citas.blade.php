<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Citas - Auditoría</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/auditor/tables/citas.css'])

    <style>
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f5f7fa;
        }
    </style>
</head>

<body>

    <div class="main-container">

        {{-- =======================
        SIDEBAR
    ======================= --}}
        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <nav>
                <a href="{{ route('auditor.dashboard') }}" class="nav-link icon-profile">Dashboard</a>
                <a href="{{ route('auditor.logs.index') }}" class="nav-link icon-logs">Logs</a>
                <a href="{{ route('auditor.tables.citas') }}" class="nav-link icon-citas active">Citas</a>
                <a href="{{ route('auditor.tables.pacientes') }}" class="nav-link icon-pacientes">Pacientes</a>
                <a href="{{ route('auditor.tables.users') }}" class="nav-link icon-users">Usuarios</a>
            </nav>

            <div class="user">
                <strong>{{ Auth::user()->nombre }}</strong>
                <small>{{ Auth::user()->nombre_rol }}</small>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-light w-100">Cerrar sesión</button>
                </form>
            </div>
        </aside>

        {{-- =======================
        CONTENIDO
    ======================= --}}
        <main class="main-content">

            <div style="margin-bottom: 24px;">
                <h1 style="font-size: 28px; font-weight: 700;">Citas Médicas</h1>
                <p style="color:#666;font-size:14px;">Listado y control de citas registradas.</p>
            </div>

            {{-- =======================
            ESTADÍSTICAS SIMPLES
        ======================= --}}
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="stat-label">Total citas</div>
                    <div class="stat-value">{{ $totalCitas }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Pendientes</div>
                    <div class="stat-value">{{ $citasPendientes }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Confirmadas</div>
                    <div class="stat-value">{{ $citasConfirmadas }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Canceladas</div>
                    <div class="stat-value">{{ $citasCanceladas }}</div>
                </div>

            </div>


            {{-- =======================
            FILTROS
        ======================= --}}
            <div class="content-card">
                <form method="GET" class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-control">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                                Pendiente
                            </option>
                            <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>
                                Confirmada
                            </option>
                            <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>
                                Cancelada
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-primary w-100">Filtrar</button>
                    </div>

                </form>
            </div>

            {{-- =======================
            TABLA
        ======================= --}}
            <div class="content-card">

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Paciente</th>
                                <th>Doctor</th>
                                <th>Motivo</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($citas as $cita)
                                <tr>
                                    <td>#{{ $cita->id }}</td>

                                    <td>
                                        {{ $cita->paciente->nombres ?? 'N/A' }}
                                        {{ $cita->paciente->apellidos ?? '' }}
                                    </td>

                                    <td>{{ $cita->doctor->nombre ?? 'N/A' }}</td>

                                    {{-- 🔹 MOTIVO --}}
                                    <td>
                                        {{ $cita->motivo ?? 'Sin motivo registrado' }}
                                    </td>

                                    <td>
                                        {{ optional($cita->fecha_inicio)->format('d/m/Y H:i') }}
                                    </td>

                                    <td>
                                        <span class="badge badge-{{ strtolower($cita->estado) }}">
                                            {{ $cita->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted" style="padding:40px;">
                                        No hay citas registradas
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

                <div style="padding:16px;">
                    {{ $citas->links() }}
                </div>

            </div>

        </main>
    </div>

</body>

</html>
