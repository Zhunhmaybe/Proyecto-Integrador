<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Citas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS del auditor -->
    <link rel="stylesheet" href="tables.css">

    <!-- Bootstrap (opcional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/auditor/tables/citas.css'])
</head>

<body>

    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
        </div>

        <a href="{{ route('auditor.dashboard') }}">Mi perfil</a>
        <a href="{{ route('auditor.logs.index') }}">LOGS</a>
        <a href="{{ route('auditor.tables.citas') }}" class="active">Citas</a>
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
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-value">120</div>
            <div class="stat-label">Total de Citas</div>
        </div>

        <div class="stat-box">
            <div class="stat-value">60</div>
            <div class="stat-label">Pendiente</div>
        </div>

        <div class="stat-box">
            <div class="stat-value">40</div>
            <div class="stat-label">Confirmada</div>
        </div>

        <div class="stat-box">
            <div class="stat-value">20</div>
            <div class="stat-label">Cancelada</div>
        </div>
    </div>

    <!-- ====== FILTROS ====== -->
    <div class="filter-card">
        <form method="GET" action="#">
            <div class="filter-grid">

                <div class="form-group">
                    <label class="form-label">Buscar</label>
                    <input type="text" class="form-control" placeholder="Motivo, estado...">
                </div>

                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select class="form-control">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control">
                </div>

            </div>

            <div class="filter-actions">
                <a href="#" class="btn btn-secondary">Limpiar</a>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>
    </div>

    <!-- ====== TABLA DE CITAS ====== -->
    <div class="content-card">
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
                        <td>Odontología General</td>
                        <td>19/01/2026 10:30</td>
                        <td>Dolor dental</td>
                        <td><span class="badge badge-pendiente">Pendiente</span></td>
                        <td>15/01/2026</td>
                    </tr>

                    <tr>
                        <td>#11</td>
                        <td>Ana Torres</td>
                        <td>Ortodoncia</td>
                        <td>18/01/2026 09:00</td>
                        <td>Control mensual</td>
                        <td><span class="badge badge-confirmada">Confirmada</span></td>
                        <td>14/01/2026</td>
                    </tr>

                    <tr>
                        <td>#10</td>
                        <td>Carlos Mena</td>
                        <td>Endodoncia</td>
                        <td>17/01/2026 15:45</td>
                        <td>N/A</td>
                        <td><span class="badge badge-cancelada">Cancelada</span></td>
                        <td>13/01/2026</td>
                    </tr>

                    <!-- Si no hay citas -->
                    <!--
                <tr>
                    <td colspan="7" style="text-align:center; padding:2rem; color:#888;">
                        No se encontraron citas
                    </td>
                </tr>
                -->

                </tbody>
            </table>
        </div>

        <!-- ====== PAGINACIÓN (HTML) ====== -->
        <div class="pagination">
            <a href="#" class="page-link">«</a>
            <a href="#" class="page-link active">1</a>
            <a href="#" class="page-link">2</a>
            <a href="#" class="page-link">3</a>
            <a href="#" class="page-link">»</a>
        </div>
    </div>

</body>

</html>