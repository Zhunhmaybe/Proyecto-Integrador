<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Auditoría</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/auditor/home.css'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f5f7fa;
        }
    </style>
</head>

<body>

    <div class="main-container">

        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <nav>
                <a href="{{ route('auditor.dashboard') }}" class="nav-link icon-profile active">Mi Perfil</a>
                <a href="{{ route('auditor.logs.index') }}" class="nav-link icon-logs ">Logs</a>
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

        <main class="main-content">

            <div style="margin-bottom: 24px;">
                <h1 style="font-size: 28px; font-weight: 700; color: #1a1a1a; margin-bottom: 8px;">Dashboard Auditoría</h1>
                <p style="color: #666; font-size: 14px;">Resumen de actividad y seguridad del sistema.</p>
            </div>
                <div class="stats-grid">

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">{{ number_format($totalLogs) }}</div>
                            <div class="stat-label">Total de Logs</div>
                        </div>
                        <div class="stat-icon blue">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">{{ number_format($logsHoy) }}</div>
                            <div class="stat-label">Logs Hoy</div>
                        </div>
                        <div class="stat-icon green">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">{{ number_format($totalUsuarios) }}</div>
                            <div class="stat-label">Total Usuarios</div>
                        </div>
                        <div class="stat-icon purple">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">{{ number_format($totalCitas) }}</div>
                            <div class="stat-label">Citas Totales</div>
                        </div>
                        <div class="stat-icon orange">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            <div class="chart-container">

                <div class="chart-card">
                    <h3 class="chart-title">Acciones por Tipo</h3>

                    @forelse($accionesPorTipo as $accion)
                    <div class="chart-item">
                        <div style="flex:1;">
                            <div class="chart-header">
                                <span class="badge badge-{{ strtolower($accion->accion) }}">{{ $accion->accion }}</span>
                                <span>{{ number_format($accion->total) }}</span>
                            </div>
                            <div class="chart-bar" style="width: {{ $maxAcciones > 0 ? number_format(($accion->total / $maxAcciones) * 100, 2) : 0 }}%;"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted" style="text-align: center; padding: 20px;">No hay datos de acciones disponibles</p>
                    @endforelse

                </div>

                <div class="chart-card">
                    <h3 class="chart-title">Tablas Más Afectadas</h3>

                    @forelse($tablasMasAfectadas as $tabla)
                    <div class="chart-item">
                        <div style="flex:1;">
                            <div class="chart-header">
                                <span style="text-transform: capitalize;">{{ $tabla->tabla_afectada }}</span>
                                <span>{{ number_format($tabla->total) }}</span>
                            </div>
                            <div class="chart-bar" style="width: {{ $maxTablas > 0 ? number_format(($tabla->total / $maxTablas) * 100, 2) : 0 }}%;"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted" style="text-align: center; padding: 20px;">No hay datos de tablas disponibles</p>
                    @endforelse

                </div>

            </div>

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
                            @forelse($ultimasAcciones as $log)
                            <tr>
                                <td><strong>#{{ $log->id }}</strong></td>
                                <td>{{ $log->usuario ? $log->usuario->nombre : 'Sistema' }}</td>
                                <td><span class="badge badge-{{ strtolower($log->accion) }}">{{ $log->accion }}</span></td>
                                <td style="text-transform: capitalize;">{{ $log->tabla_afectada ?? 'N/A' }}</td>
                                <td><code style="font-size: 12px; color: #666;">{{ $log->ip_address ?? 'N/A' }}</code></td>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding: 40px;">No hay logs registrados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

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
                            @forelse($usuariosMasActivos as $userStat)
                            <tr>
                                <td><strong>{{ $userStat->usuario ? $userStat->usuario->nombre : 'Desconocido' }}</strong></td>
                                <td>{{ $userStat->usuario ? $userStat->usuario->email : 'N/A' }}</td>
                                <td>
                                    <span style="background: #E3F2FD; color: #1565C0; padding: 4px 12px; border-radius: 12px; font-weight: 600; font-size: 13px;">
                                        {{ number_format($userStat->total_acciones) }} acciones
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted" style="padding: 40px;">No hay datos de usuarios activos</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

    </div>

    <script>
        // Animación de las barras de progreso al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const bars = document.querySelectorAll('.chart-bar');
            bars.forEach((bar, index) => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100 + (index * 50));
            });
        });
    </script>

</body>

</html>