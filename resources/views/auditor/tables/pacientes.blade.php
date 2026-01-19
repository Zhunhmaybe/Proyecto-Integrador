@extends('layouts.auditor')

@section('title', 'Pacientes')
@section('page-title', 'Tabla de Pacientes')

@section('content')
@push('styles')
@vite('resources/css/auditor/tables.css')
@endpush

<!-- Estadísticas -->
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-value">{{ number_format($pacientes->total()) }}</div>
        <div class="stat-label">Total de Pacientes</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">{{ number_format(\App\Models\Paciente::whereDate('created_at', today())->count()) }}
        </div>
        <div class="stat-label">Registrados Hoy</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">
            {{ number_format(\App\Models\Paciente::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count()) }}
        </div>
        <div class="stat-label">Este Mes</div>
    </div>
</div>

<!-- Filtros -->
<div class="filter-card">
    <form method="GET" action="{{ route('auditor.tables.pacientes') }}">
        <div class="filter-grid">
            <div class="form-group">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Nombre, apellido, email, teléfono..."
                    value="{{ request('search') }}">
            </div>
        </div>

        <div class="filter-actions">
            <a href="{{ route('auditor.tables.pacientes') }}" class="btn btn-secondary">Limpiar</a>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>
</div>

<!-- Tabla de Pacientes -->
<div class="content-card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Dirección</th>
                    <th>Registrado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pacientes as $paciente)
                <tr>
                    <td>#{{ $paciente->id }}</td>
                    <td>{{ $paciente->nombre }} {{ $paciente->apellido }}</td>
                    <td>{{ $paciente->email ?? 'N/A' }}</td>
                    <td>{{ $paciente->telefono ?? 'N/A' }}</td>
                    <td>{{ $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td>{{ $paciente->direccion ?? 'N/A' }}</td>
                    <td>{{ $paciente->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--gray-text); padding: 2rem;">
                        No se encontraron pacientes
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="pagination">
        {{ $pacientes->links() }}
    </div>
</div>
@endsection