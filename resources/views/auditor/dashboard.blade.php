<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Auditoría</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS principal -->
    <link rel="stylesheet" href="dashboard.css">

    <!-- Si usas Bootstrap (opcional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/auditor/home.css'])
</head>

<body>

    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
        </div>

        <a href="{{ route('auditor.dashboard') }}" class="active">Mi perfil</a>
        <a href="{{ route('auditor.logs.index') }}">LOGS</a>
        <a href="{{ route('auditor.tables.citas') }}">Citas</a>
        <a href="{{ route('auditor.tables.pacientes') }}">Pacientes</a>
        <a href="{{ route('auditor.tables.users') }}">Usuarios</a>


        <div class="user">
            <strong>{{ Auth::user()->nombre }}</strong><br>
            <small>{{ Auth::user()->nombre_rol }}</small>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    <!-- ====== ESTADÍSTICAS ====== -->
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value">1,245</div>
                    <div class="stat-label">Total de Logs</div>
                </div>
                <div class="stat-icon blue">
                    <!-- Ícono -->
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value">58</div>
                    <div class="stat-label">Logs Hoy</div>
                </div>
                <div class="stat-icon green"></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value">32</div>
                    <div class="stat-label">Usuarios</div>
                </div>
                <div class="stat-icon purple"></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value">120</div>
                    <div class="stat-label">Citas Totales</div>
                </div>
                <div class="stat-icon orange"></div>
            </div>
        </div>

    </div>

    <!-- ====== GRÁFICOS ====== -->
    <div class="chart-container">

        <!-- Acciones por Tipo -->
        <div class="chart-card">
            <h3 class="chart-title">Acciones por Tipo</h3>

            <div class="chart-item">
                <div style="flex:1;">
                    <div class="chart-header">
                        <span class="badge badge-insert">INSERT</span>
                        <span>450</span>
                    </div>
                    <div class="chart-bar" style="width: 80%;"></div>
                </div>
            </div>

            <div class="chart-item">
                <div style="flex:1;">
                    <div class="chart-header">
                        <span class="badge badge-update">UPDATE</span>
                        <span>300</span>
                    </div>
                    <div class="chart-bar" style="width: 55%;"></div>
                </div>
            </div>

        </div>

        <!-- Tablas más afectadas -->
        <div class="chart-card">
            <h3 class="chart-title">Tablas Más Afectadas</h3>

            <div class="chart-item">
                <div style="flex:1;">
                    <div class="chart-header">
                        <span>usuarios</span>
                        <span>210</span>
                    </div>
                    <div class="chart-bar" style="width: 70%;"></div>
                </div>
            </div>

            <div class="chart-item">
                <div style="flex:1;">
                    <div class="chart-header">
                        <span>citas</span>
                        <span>150</span>
                    </div>
                    <div class="chart-bar" style="width: 50%;"></div>
                </div>
            </div>

        </div>

    </div>

    <!-- ====== ÚLTIMAS ACCIONES ====== -->
    <div class="content-card">
        <h3 class="chart-title">Últimas Acciones Registradas</h3>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Tabla</th>
                        <th>IP</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#12</td>
                        <td>Juan Pérez</td>
                        <td><span class="badge badge-update">UPDATE</span></td>
                        <td>usuarios</td>
                        <td>192.168.1.10</td>
                        <td>19/01/2026 10:32:12</td>
                    </tr>

                    <tr>
                        <td>#11</td>
                        <td>Sistema</td>
                        <td><span class="badge badge-insert">INSERT</span></td>
                        <td>citas</td>
                        <td>N/A</td>
                        <td>19/01/2026 09:58:45</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ====== USUARIOS MÁS ACTIVOS ====== -->
    <div class="content-card">
        <h3 class="chart-title">Usuarios Más Activos</h3>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Total de Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Juan Pérez</td>
                        <td>juan@email.com</td>
                        <td><strong>120</strong> acciones</td>
                    </tr>

                    <tr>
                        <td>Ana Torres</td>
                        <td>ana@email.com</td>
                        <td><strong>98</strong> acciones</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>