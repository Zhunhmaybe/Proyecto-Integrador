<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pacientes - Auditoría</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/auditor/tables/pacientes.css'])
</head>

<body>

<div class="main-container">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
        </div>

        <nav>
            <a href="{{ route('auditor.dashboard') }}" class="nav-link icon-profile">Mi Perfil</a>
            <a href="{{ route('auditor.logs.index') }}" class="nav-link icon-logs">Logs</a>
            <a href="{{ route('auditor.tables.citas') }}" class="nav-link icon-citas">Citas</a>
            <a href="{{ route('auditor.tables.pacientes') }}" class="nav-link icon-pacientes active">Pacientes</a>
            <a href="{{ route('auditor.tables.users') }}" class="nav-link icon-users">Usuarios</a>
        </nav>

        <div class="user">
            <strong>{{ Auth::user()->nombre }}</strong>
            <small>{{ Auth::user()->nombre_rol }}</small>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    {{-- CONTENIDO --}}
    <main class="main-content">

        <h1 class="mb-2">Pacientes</h1>
        <p class="text-muted mb-4">Listado general de pacientes registrados.</p>

        {{-- ESTADÍSTICAS --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $totalPacientes }}</div>
                <div class="stat-label">Total Pacientes</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">{{ $pacientesHoy }}</div>
                <div class="stat-label">Registrados Hoy</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">{{ $pacientesMes }}</div>
                <div class="stat-label">Este Mes</div>
            </div>
        </div>

        {{-- FILTRO --}}
        <div class="content-card">
            <form method="GET" class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Buscar</label>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Nombre, apellido, email o teléfono"
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Buscar</button>
                </div>
            </form>
        </div>

        {{-- TABLA --}}
        <div class="content-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha Nac.</th>
                            <th>Dirección</th>
                            <th>Registrado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pacientes as $paciente)
                        <tr>
                            <td>#{{ $paciente->id }}</td>
                            <td>{{ $paciente->nombres }} {{ $paciente->apellidos }}</td>
                            <td>{{ $paciente->email ?? 'N/A' }}</td>
                            <td>{{ $paciente->telefono ?? 'N/A' }}</td>
                            <td>{{ optional($paciente->fecha_nacimiento)->format('d/m/Y') }}</td>
                            <td>{{ $paciente->direccion ?? 'N/A' }}</td>
                            <td>{{ $paciente->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding:40px;">
                                No hay pacientes registrados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $pacientes->links() }}
            </div>
        </div>

    </main>
</div>

</body>
</html>
