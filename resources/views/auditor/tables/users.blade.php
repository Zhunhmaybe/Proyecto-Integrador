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

    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
        </div>

        <nav>
            <a href="{{ route('auditor.dashboard') }}" class="nav-link icon-profile">Mi perfil</a>
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

    <main class="main-content">

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">120</div>
                <div class="stat-label">Total de Usuarios</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">10</div>
                <div class="stat-label">Administradores</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">25</div>
                <div class="stat-label">Auditores</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">85</div>
                <div class="stat-label">Usuarios</div>
            </div>
        </div>

        <div class="filter-card">
            <form method="GET">
                <div class="filter-grid">

                    <div>
                        <label class="form-label">Buscar</label>
                        <input type="text" class="form-control" placeholder="Nombre, email...">
                    </div>

                    <div>
                        <label class="form-label">Rol</label>
                        <select class="form-control">
                            <option value="">Todos</option>
                            <option value="admin">Administrador</option>
                            <option value="auditor">Auditor</option>
                            <option value="usuario">Usuario</option>
                        </select>
                    </div>

                </div>

                <div class="filter-actions">
                    <a href="#" class="btn btn-secondary">Limpiar</a>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>

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
                            <th>Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>#1</td>
                            <td>Oskar Jurado</td>
                            <td>oskar@email.com</td>
                            <td><span class="badge badge-admin">Administrador</span></td>
                            <td style="color:#10b981;">✓ Verificado</td>
                            <td>10/01/2026 09:15</td>
                        </tr>

                        <tr>
                            <td>#2</td>
                            <td>Ana Torres</td>
                            <td>ana@email.com</td>
                            <td><span class="badge badge-auditor">Auditor</span></td>
                            <td style="color:#ef4444;">✗ No verificado</td>
                            <td>12/01/2026 14:40</td>
                        </tr>

                        <tr>
                            <td>#3</td>
                            <td>Juan Pérez</td>
                            <td>juan@email.com</td>
                            <td><span class="badge badge-usuario">Usuario</span></td>
                            <td style="color:#10b981;">✓ Verificado</td>
                            <td>15/01/2026 08:55</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

</body>
</html>
