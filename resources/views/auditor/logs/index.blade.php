<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Logs de Auditoría</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/auditor/logs/index.css'])
</head>
<body>

<div class="main-container">

    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo">
        </div>

        <nav>
            <a href="{{ route('auditor.dashboard') }}" class="nav-link icon-profile">Mi Perfil</a>
            <a href="{{ route('auditor.logs.index') }}" class="nav-link icon-logs active">Logs</a>
            <a href="{{ route('auditor.tables.citas') }}" class="nav-link icon-citas">Citas</a>
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

    <!-- CONTENIDO -->
    <main class="main-content">

        <!-- HEADER -->
        <header class="page-header">
            <h1>Logs de Auditoría</h1>
            <p>Historial detallado de operaciones del sistema.</p>
        </header>

        <!-- FILTROS -->
        <section class="filter-card">
            <form method="GET" action="{{ route('auditor.logs.index') }}">

                <div class="filter-grid">
                    <div>
                        <label class="form-label">Buscar</label>
                        <input type="text" name="search" class="form-control"
                               value="{{ request('search') }}" placeholder="ID, IP, tabla">
                    </div>

                    <div>
                        <label class="form-label">Acción</label>
                        <select name="accion" class="form-control">
                            <option value="">Todas</option>
                            <option>INSERT</option>
                            <option>UPDATE</option>
                            <option>DELETE</option>
                            <option>LOGIN</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Tabla</label>
                        <select name="tabla" class="form-control">
                            <option value="">Todas</option>
                            @foreach($tablas as $tabla)
                                <option value="{{ $tabla }}">{{ $tabla }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Usuario</label>
                        <select name="usuario_id" class="form-control">
                            <option value="">Todos</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control">
                    </div>

                    <div>
                        <label class="form-label">Fecha fin</label>
                        <input type="date" name="fecha_fin" class="form-control">
                    </div>
                </div>

                <div class="filter-actions">
                    <a href="{{ route('auditor.logs.index') }}" class="btn btn-secondary">Limpiar</a>
                    <button class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('auditor.logs.export') }}" class="btn btn-success icon-export">
                        Exportar CSV
                    </a>
                </div>
            </form>
        </section>

        <section class="content-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Tabla</th>
                            <th>Registro</th>
                            <th>IP</th>
                            <th>Fecha</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>#{{ $log->id }}</td>
                                <td>{{ $log->usuario?->nombre ?? 'Sistema' }}</td>
                                <td>
                                    <span class="badge badge-{{ strtolower($log->accion) }}">
                                        {{ $log->accion }}
                                    </span>
                                </td>
                                <td>{{ $log->tabla_afectada ?? 'N/A' }}</td>
                                <td>{{ $log->registro_id ?? 'N/A' }}</td>
                                <td class="mono">{{ $log->ip_address }}</td>
                                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button class="details-btn">Ver</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-state">
                                    No se encontraron registros
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</div>

</body>
</html>
