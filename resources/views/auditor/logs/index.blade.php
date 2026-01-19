@extends('layouts.auditor')

@section('title', 'Logs de Auditoría')
@section('page-title', 'Logs de Auditoría')

@section('content')
@push('styles')
@vite('resources/css/auditor/tables.css')
@endpush

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
    const logsData = @json($logs - > items());

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
    document.getElementById('detailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endpush
@endsection