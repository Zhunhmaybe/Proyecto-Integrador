@extends('layouts.auditor')

@section('title', 'Dashboard - Auditoría')
@section('page-title', 'Dashboard de Auditoría')
<div class="user">
    <strong>{{ Auth::user()->nombre }}</strong><br>
    <small>{{ Auth::user()->nombre_rol }}</small>

    <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
    </form>
</div>
@section('content')
@push('styles')
@vite('resources/css/auditor/dashboard.css')
@endpush

<!-- Estadísticas -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($stats['total_logs']) }}</div>
                <div class="stat-label">Total de Logs</div>
            </div>
            <div class="stat-icon blue">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($stats['logs_today']) }}</div>
                <div class="stat-label">Logs Hoy</div>
            </div>
            <div class="stat-icon green">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                <div class="stat-label">Usuarios</div>
            </div>
            <div class="stat-icon purple">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($stats['total_citas']) }}</div>
                <div class="stat-label">Citas Totales</div>
            </div>
            <div class="stat-icon orange">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="chart-container">
    <div class="chart-card">
        <h3 class="chart-title">Acciones por Tipo</h3>
        @php
        $maxActionTotal = $actionsByType->max('total');
        @endphp
        @foreach($actionsByType as $action)
        <div class="chart-item">
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="badge badge-{{ strtolower($action->accion) }}">{{ $action->accion }}</span>
                    <span style="font-weight: 600;">{{ number_format($action->total) }}</span>
                </div>
                <div class="chart-bar" style="width: {{ $maxActionTotal > 0 ? ($action->total / $maxActionTotal) * 100 : 0 }}%;">
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="chart-card">
        <h3 class="chart-title">Tablas Más Afectadas</h3>
        @php
        $maxTableTotal = $tableActivity->max('total');
        @endphp
        @foreach($tableActivity as $table)
        <div class="chart-item">
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>{{ $table->tabla_afectada }}</span>
                    <span style="font-weight: 600;">{{ number_format($table->total) }}</span>
                </div>
                <div class="chart-bar" style="width: {{ $maxTableTotal > 0 ? ($table->total / $maxTableTotal) * 100 : 0 }}%;">
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Últimas Acciones -->
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
                @forelse($recentLogs as $log)
                <tr>
                    <td>#{{ $log->id }}</td>
                    <td>{{ $log->usuario->nombre ?? 'Sistema' }}</td>
                    <td><span class="badge badge-{{ strtolower($log->accion) }}">{{ $log->accion }}</span></td>
                    <td>{{ $log->tabla_afectada ?? 'N/A' }}</td>
                    <td style="font-family: monospace; font-size: 0.875rem;">{{ $log->ip_address ?? 'N/A' }}</td>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--gray-text); padding: 2rem;">
                        No hay registros de auditoría disponibles
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Usuarios Más Activos -->
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
                @forelse($activeUsers as $userActivity)
                <tr>
                    <td>{{ $userActivity->usuario->nombre ?? 'N/A' }}</td>
                    <td>{{ $userActivity->usuario->email ?? 'N/A' }}</td>
                    <td><strong>{{ number_format($userActivity->total) }}</strong> acciones</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: var(--gray-text); padding: 2rem;">
                        No hay datos de actividad de usuarios
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection