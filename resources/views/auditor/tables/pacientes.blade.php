<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pacientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/auditor/tables/pacientes.css'])
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
            <a href="{{ route('auditor.tables.pacientes') }}" class="nav-link icon-pacientes active">Pacientes</a>
            <a href="{{ route('auditor.tables.users') }}" class="nav-link icon-users">Usuarios</a>
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
                <div class="stat-value">350</div>
                <div class="stat-label">Total de Pacientes</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">5</div>
                <div class="stat-label">Registrados Hoy</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">42</div>
                <div class="stat-label">Este Mes</div>
            </div>
        </div>

        <div class="filter-card">
            <form method="GET">
                <div class="filter-grid">
                    <div>
                        <label class="form-label">Buscar</label>
                        <input type="text" class="form-control"
                               placeholder="Nombre, apellido, email, teléfono...">
                    </div>
                </div>

                <div class="filter-actions">
                    <a href="#" class="btn btn-secondary">Limpiar</a>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>
        </div>

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
                        <tr>
                            <td>#101</td>
                            <td>Juan Pérez</td>
                            <td>juan@email.com</td>
                            <td>0987654321</td>
                            <td>15/06/1995</td>
                            <td>Av. Central 123</td>
                            <td>10/01/2026</td>
                        </tr>

                        <tr>
                            <td>#102</td>
                            <td>Ana Torres</td>
                            <td>ana@email.com</td>
                            <td>0991122334</td>
                            <td>22/03/1998</td>
                            <td>Cdla. Norte</td>
                            <td>12/01/2026</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

</body>
</html>
