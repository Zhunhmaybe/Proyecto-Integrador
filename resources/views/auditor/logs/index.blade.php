<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Logs de Auditoría</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="tables.css">

    <!-- Bootstrap (opcional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/auditor/logs/index.css'])
</head>

<body>
    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
        </div>

        <a href="{{ route('auditor.dashboard') }}" >Mi perfil</a>
        <a href="{{ route('auditor.logs.index') }}" class="active">LOGS</a>
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

    <!-- ====== FILTROS ====== -->
    <div class="filter-card">
        <form method="GET" action="#">
            <div class="filter-grid">

                <div class="form-group">
                    <label class="form-label">Buscar</label>
                    <input type="text" class="form-control" placeholder="ID, IP, tabla...">
                </div>

                <div class="form-group">
                    <label class="form-label">Acción</label>
                    <select class="form-control">
                        <option value="">Todas</option>
                        <option value="INSERT">INSERT</option>
                        <option value="UPDATE">UPDATE</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tabla</label>
                    <select class="form-control">
                        <option value="">Todas</option>
                        <option value="usuarios">usuarios</option>
                        <option value="citas">citas</option>
                        <option value="roles">roles</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Usuario</label>
                    <select class="form-control">
                        <option value="">Todos</option>
                        <option value="1">Juan Pérez</option>
                        <option value="2">Ana Torres</option>
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
                <a href="#" class="btn btn-success">Exportar CSV</a>
            </div>
        </form>
    </div>

    <!-- ====== TABLA DE LOGS ====== -->
    <div class="content-card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Tabla</th>
                        <th>Registro ID</th>
                        <th>IP</th>
                        <th>Fecha</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>#25</td>
                        <td>Juan Pérez</td>
                        <td><span class="badge badge-update">UPDATE</span></td>
                        <td>usuarios</td>
                        <td>12</td>
                        <td style="font-family:monospace;">192.168.1.10</td>
                        <td>19/01/2026 11:15:30</td>
                        <td>
                            <button class="details-btn" onclick="showDetails(25)">Ver Detalles</button>
                        </td>
                    </tr>

                    <tr>
                        <td>#24</td>
                        <td>Sistema</td>
                        <td><span class="badge badge-insert">INSERT</span></td>
                        <td>citas</td>
                        <td>45</td>
                        <td>N/A</td>
                        <td>19/01/2026 10:40:12</td>
                        <td>
                            <button class="details-btn" onclick="showDetails(24)">Ver Detalles</button>
                        </td>
                    </tr>

                    <!-- Sin registros -->
                    <!--
                <tr>
                    <td colspan="8" style="text-align:center; padding:2rem; color:#888;">
                        No se encontraron registros
                    </td>
                </tr>
                -->

                </tbody>
            </table>
        </div>

        <!-- ====== PAGINACIÓN ====== -->
        <div class="pagination">
            <a href="#" class="page-link">«</a>
            <a href="#" class="page-link active">1</a>
            <a href="#" class="page-link">2</a>
            <a href="#" class="page-link">3</a>
            <a href="#" class="page-link">»</a>
        </div>
    </div>

    <!-- ====== MODAL DE DETALLES ====== -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detalles del Log</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div id="modalBody"></div>
        </div>
    </div>

    <!-- ====== SCRIPT ====== -->
    <script>
        const logsData = [{
                id: 25,
                usuario: {
                    nombre: "Juan Pérez"
                },
                accion: "UPDATE",
                tabla_afectada: "usuarios",
                registro_id: 12,
                ip_address: "192.168.1.10",
                user_agent: "Chrome / Windows",
                created_at: "2026-01-19T11:15:30",
                valores_anteriores: {
                    nombre: "Juan",
                    estado: "inactivo"
                },
                valores_nuevos: {
                    nombre: "Juan Pérez",
                    estado: "activo"
                }
            },
            {
                id: 24,
                usuario: null,
                accion: "INSERT",
                tabla_afectada: "citas",
                registro_id: 45,
                ip_address: null,
                user_agent: null,
                created_at: "2026-01-19T10:40:12"
            }
        ];

        function showDetails(logId) {
            const log = logsData.find(l => l.id === logId);
            if (!log) return;

            const modalBody = document.getElementById('modalBody');

            modalBody.innerHTML = `
            <div class="detail-row"><strong>ID:</strong> #${log.id}</div>
            <div class="detail-row"><strong>Usuario:</strong> ${log.usuario ? log.usuario.nombre : 'Sistema'}</div>
            <div class="detail-row"><strong>Acción:</strong>
                <span class="badge badge-${log.accion.toLowerCase()}">${log.accion}</span>
            </div>
            <div class="detail-row"><strong>Tabla:</strong> ${log.tabla_afectada || 'N/A'}</div>
            <div class="detail-row"><strong>Registro ID:</strong> ${log.registro_id || 'N/A'}</div>
            <div class="detail-row"><strong>IP:</strong> ${log.ip_address || 'N/A'}</div>
            <div class="detail-row"><strong>User Agent:</strong> ${log.user_agent || 'N/A'}</div>
            <div class="detail-row"><strong>Fecha:</strong>
                ${new Date(log.created_at).toLocaleString('es-ES')}
            </div>

            ${log.valores_anteriores ? `
                    <h6 style="margin-top:1rem;">Valores Anteriores</h6>
                    <pre>${JSON.stringify(log.valores_anteriores, null, 2)}</pre>
                ` : ''}

            ${log.valores_nuevos ? `
                    <h6>Valores Nuevos</h6>
                    <pre>${JSON.stringify(log.valores_nuevos, null, 2)}</pre>
                ` : ''}
        `;

            document.getElementById('detailsModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.remove('active');
        }

        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>

</body>

</html>
