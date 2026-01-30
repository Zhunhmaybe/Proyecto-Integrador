<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Citas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/auditor/tables/citas.css'])
</head>

<body>

    <div class="main-container">

 
        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <nav>
                <a href="{{ route('auditor.dashboard') }}" class="nav-link icon-profile">Mi Perfil</a>
                <a href="{{ route('auditor.logs.index') }}" class="nav-link icon-logs">Logs</a>
                <a href="{{ route('auditor.tables.citas') }}" class="nav-link icon-citas active">Citas</a>
                <a href="{{ route('auditor.tables.pacientes') }}" class="nav-link icon-pacientes">Pacientes</a>
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

            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">120</div>
                    <div class="stat-label">Total de Citas</div>
                </div>

                <div class="stat-card">
                    <div class="stat-value">60</div>
                    <div class="stat-label">Pendiente</div>
                </div>

                <div class="stat-card">
                    <div class="stat-value">40</div>
                    <div class="stat-label">Confirmada</div>
                </div>

                <div class="stat-card">
                    <div class="stat-value">20</div>
                    <div class="stat-label">Cancelada</div>
                </div>
            </section>

            <section class="filter-card">
                <form>
                    <div class="filter-grid">
                        <div>
                            <label class="form-label">Buscar</label>
                            <input type="text" class="form-control" placeholder="Motivo, estado...">
                        </div>

                        <div>
                            <label class="form-label">Estado</label>
                            <select class="form-control">
                                <option value="">Todos</option>
                                <option>Pendiente</option>
                                <option>Confirmada</option>
                                <option>Cancelada</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control">
                        </div>

                        <div>
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control">
                        </div>
                    </div>

                    <div class="filter-actions">
                        <a href="#" class="btn btn-secondary">Limpiar</a>
                        <button class="btn btn-primary">Filtrar</button>
                    </div>
                </form>
            </section>

            <section class="content-card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Paciente</th>
                                <th>Especialidad</th>
                                <th>Fecha y Hora</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                                <th>Creada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#12</td>
                                <td>Juan Pérez</td>
                                <td>Odontología</td>
                                <td>19/01/2026 10:30</td>
                                <td>Dolor dental</td>
                                <td><span class="badge badge-pendiente">Pendiente</span></td>
                                <td>15/01/2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>

</body>

</html>