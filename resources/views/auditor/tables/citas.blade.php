@extends('layouts.auditor')

@section('title', 'Citas')
@section('page-title', 'Tabla de Citas')

@section('content')
@push('styles')
@vite('resources/css/auditor/tables.css')
@endpush

<!-- Estadísticas -->
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-value">{{ number_format($citas->total()) }}</div>
        <div class="stat-label">Total de Citas</div>
    </div>
    @foreach($estados as $estado)
    <div class="stat-box">
        <div class="stat-value">{{ number_format(\App\Models\Citas::where('estado', $estado)->count()) }}</div>
        <div class="stat-label">{{ ucfirst($estado) }}</div>
    </div>
    @endforeach
</div>

<!-- Filtros -->
<div class="filter-card">
    <form method="GET" action="{{ route('auditor.tables.citas') }}">
        <div class="filter-grid">
            <div class="form-group">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Motivo, estado..."
                    value="{{ request('search') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-control">
                    <option value="">Todos</option>
                    @foreach($estados as $estado)
                    <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                        {{ ucfirst($estado) }}
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
            <a href="{{ route('auditor.tables.citas') }}" class="btn btn-secondary">Limpiar</a>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>
</div>

<!-- Tabla de Citas -->
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
                @forelse($citas as $cita)
                <tr>
                    <td>#{{ $cita->id }}</td>
                    <td>{{ $cita->paciente ? $cita->paciente->nombre . ' ' . $cita->paciente->apellido : 'N/A' }}</td>
                    <td>{{ $cita->especialidad ? $cita->especialidad->nombre : 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->fecha_inicio)->format('d/m/Y H:i') }}</td>
                    <td>{{ $cita->motivo ?? 'N/A' }}</td>
                    <td><span class="badge badge-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td>
                    <td>{{ $cita->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--gray-text); padding: 2rem;">
                        No se encontraron citas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="pagination">
        {{ $citas->links() }}
    </div>
</div>
@endsection