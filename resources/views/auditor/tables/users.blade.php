<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/auditor/tables/users.css'])
</head>

<body>

    <div class="main-container">

        {{-- ================= SIDEBAR ================= --}}
        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <nav>
                <a href="{{ route('auditor.dashboard') }}" class="nav-link icon-profile">Mi Perfil</a>
                <a href="{{ route('auditor.logs.index') }}" class="nav-link icon-logs">Logs</a>
                <a href="{{ route('auditor.tables.citas') }}" class="nav-link icon-citas">Citas</a>
                <a href="{{ route('auditor.tables.pacientes') }}" class="nav-link icon-pacientes">Pacientes</a>
                <a href="{{ route('auditor.tables.users') }}" class="nav-link icon-users active">Usuarios</a>
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

        {{-- ================= CONTENIDO ================= --}}
            <main class="main-content">

                {{-- ================= ESTADÍSTICAS ================= --}}
                <div class="stats-grid">

                    <div class="stat-card">
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Usuarios</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-value">{{ $stats['doctor'] }}</div>
                        <div class="stat-label">Doctor</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-value">{{ $stats['admin'] }}</div>
                        <div class="stat-label">Administradores</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-value">{{ $stats['auditor'] }}</div>
                        <div class="stat-label">Auditores</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-value">{{ $stats['recepcion'] }}</div>
                        <div class="stat-label">Recepcionistas</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-value">{{ $stats['usuario'] }}</div>
                        <div class="stat-label">Usuarios</div>
                    </div>

                </div>

            {{-- ================= FILTROS ================= --}}
            <div class="filter-card">
                <form method="GET">

                    <div class="filter-grid">

                        <div>
                            <label class="form-label">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Nombre o email">
                        </div>

                        <div>
                            <label class="form-label">Rol</label>
                            <select class="form-control" name="rol">
                                <option value="">Todos</option>
                                <option value="0" {{ request('rol') === '0' ? 'selected' : '' }}>Doctor</option>
                                <option value="1" {{ request('rol') === '1' ? 'selected' : '' }}>Admin</option>
                                <option value="2" {{ request('rol') === '2' ? 'selected' : '' }}>Auditor</option>
                                <option value="3" {{ request('rol') === '3' ? 'selected' : '' }}>Recepción
                                </option>
                                <option value="4" {{ request('rol') === '4' ? 'selected' : '' }}>Usuario</option>
                            </select>
                        </div>

                    </div>

                    <div class="filter-actions">
                        <a href="{{ route('auditor.tables.users') }}" class="btn btn-secondary">Limpiar</a>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>

                </form>
            </div>

            {{-- ================= TABLA ================= --}}
            <div class="content-card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Email Verificado</th>
                                <th>Registrado</th>
                            </tr>
                        </thead>
                        <tbody>

                            @php
                                $roles = [
                                    0 => ['Doctor', 'badge-doctor'],
                                    1 => ['Admin', 'badge-admin'],
                                    2 => ['Auditor', 'badge-auditor'],
                                    3 => ['Recepción', 'badge-recepcion'],
                                    4 => ['Usuario', 'badge-usuario'],
                                ];
                            @endphp


                            @forelse($users as $user)
                                <tr>
                                    <td>#{{ $user->id }}</td>
                                    <td>{{ $user->nombre }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if (isset($roles[$user->rol]))
                                            <span class="badge {{ $roles[$user->rol][1] }}">
                                                {{ $roles[$user->rol][0] }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">Sin rol</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($user->email_verified_at)
                                            <span style="color:#10b981;">✓ Verificado</span>
                                        @else
                                            <span style="color:#ef4444;">✗ No verificado</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted" style="padding:40px;">
                                        No hay usuarios registrados
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div style="padding:16px;">
                    {{ $users->links() }}
                </div>
            </div>

        </main>
    </div>

</body>

</html>
