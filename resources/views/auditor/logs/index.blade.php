@extends('layouts.auditor')

@section('title', 'Logs de Auditoría')
@section('page-title', 'Logs de Auditoría')

@section('content')
    <style>
        .filter-card {
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1.5rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-text);
        }

        .form-control {
            padding: 0.75rem;
            background: rgba(17, 24, 39, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            color: var(--light-text);
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background: rgba(107, 114, 128, 0.2);
            color: var(--light-text);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(107, 114, 128, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 1rem;
            font-weight: 600;
            color: var(--gray-text);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(17, 24, 39, 0.3);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-create {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .badge-update {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .badge-delete {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .badge-login {
            background: rgba(139, 92, 246, 0.2);
            color: #8b5cf6;
        }

        .badge-logout {
            background: rgba(156, 163, 175, 0.2);
            color: #9ca3af;
        }

        .badge-access_denied {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            background: rgba(31, 41, 55, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            color: var(--light-text);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination a:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: var(--primary-color);
        }

        .pagination .active {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .details-btn {
            padding: 0.5rem 1rem;
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .details-btn:hover {
            background: rgba(59, 130, 246, 0.3);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: rgba(31, 41, 55, 0.95);
            border-radius: 1rem;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .close-btn {
            background: none;
            border: none;
            color: var(--light-text);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        .detail-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .detail-label {
            font-weight: 600;
            color: var(--gray-text);
            width: 150px;
        }

        .detail-value {
            flex: 1;
        }

        pre {
            background: rgba(17, 24, 39, 0.5);
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            font-size: 0.875rem;
        }
    </style>

    <!-- Filtros -->
    <div class="filter-card">
        <form method="GET" action="{{ route('auditor.logs.index') }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="ID, IP, tabla..."
                        value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Acción</label>
                    <select name="accion" class="form-control">
                        <option value="">Todas</option>
                        @foreach($acciones as $accion)
                            <option value="{{ $accion }}" {{ request('accion') == $accion ? 'selected' : '' }}>{{ $accion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tabla</label>
                    <select name="tabla_afectada" class="form-control">
                        <option value="">Todas</option>
                        @foreach($tablas as $tabla)
                            <option value="{{ $tabla }}" {{ request('tabla_afectada') == $tabla ? 'selected' : '' }}>{{ $tabla }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Usuario</label>
                    <select name="usuario_id" class="form-control">
                        <option value="">Todos</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ request('usuario_id') == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                </div>
            </div>

            <div class="filter-actions">
                <a href="{{ route('auditor.logs.index') }}" class="btn btn-secondary">Limpiar</a>
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('auditor.logs.export', request()->all()) }}" class="btn btn-success">Exportar CSV</a>
            </div>
        </form>
    </div>

    <!-- Tabla de Logs -->
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
                    @forelse($logs as $log)
                        <tr>
                            <td>#{{ $log->id }}</td>
                            <td>{{ $log->usuario->nombre ?? 'Sistema' }}</td>
                            <td><span class="badge badge-{{ strtolower($log->accion) }}">{{ $log->accion }}</span></td>
                            <td>{{ $log->tabla_afectada ?? 'N/A' }}</td>
                            <td>{{ $log->registro_id ?? 'N/A' }}</td>
                            <td style="font-family: monospace; font-size: 0.875rem;">{{ $log->ip_address ?? 'N/A' }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <button class="details-btn" onclick="showDetails({{ $log->id }})">Ver Detalles</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--gray-text); padding: 2rem;">
                                No se encontraron registros
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="pagination">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- Modal de Detalles -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detalles del Log</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div id="modalBody"></div>
        </div>
    </div>

    @push('scripts')
        <script>
            const logsData = @json($logs->items());

            function showDetails(logId) {
                const log = logsData.find(l => l.id === logId);
                if (!log) return;

                const modalBody = document.getElementById('modalBody');
                modalBody.innerHTML = `
                        <div class="detail-row">
                            <div class="detail-label">ID:</div>
                            <div class="detail-value">#${log.id}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Usuario:</div>
                            <div class="detail-value">${log.usuario ? log.usuario.nombre : 'Sistema'}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Acción:</div>
                            <div class="detail-value"><span class="badge badge-${log.accion.toLowerCase()}">${log.accion}</span></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Tabla:</div>
                            <div class="detail-value">${log.tabla_afectada || 'N/A'}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Registro ID:</div>
                            <div class="detail-value">${log.registro_id || 'N/A'}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">IP:</div>
                            <div class="detail-value">${log.ip_address || 'N/A'}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">User Agent:</div>
                            <div class="detail-value" style="font-size: 0.75rem;">${log.user_agent || 'N/A'}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Fecha:</div>
                            <div class="detail-value">${new Date(log.created_at).toLocaleString('es-ES')}</div>
                        </div>
                        ${log.valores_anteriores ? `
                        <div style="margin-top: 1rem;">
                            <div class="detail-label" style="margin-bottom: 0.5rem;">Valores Anteriores:</div>
                            <pre>${JSON.stringify(log.valores_anteriores, null, 2)}</pre>
                        </div>
                        ` : ''}
                        ${log.valores_nuevos ? `
                        <div style="margin-top: 1rem;">
                            <div class="detail-label" style="margin-bottom: 0.5rem;">Valores Nuevos:</div>
                            <pre>${JSON.stringify(log.valores_nuevos, null, 2)}</pre>
                        </div>
                        ` : ''}
                    `;

                document.getElementById('detailsModal').classList.add('active');
            }

            function closeModal() {
                document.getElementById('detailsModal').classList.remove('active');
            }

            // Cerrar modal al hacer clic fuera
            document.getElementById('detailsModal').addEventListener('click', function (e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        </script>
    @endpush
@endsection