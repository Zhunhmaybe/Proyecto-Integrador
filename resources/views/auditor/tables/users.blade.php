@extends('layouts.auditor')

@section('title', 'Usuarios')
@section('page-title', 'Tabla de Usuarios')

@section('content')
@push('styles')
@vite('resources/css/auditor/tables.css')
@endpush

<!-- Estadísticas -->
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-value">{{ number_format($users->total()) }}</div>
        <div class="stat-label">Total de Usuarios</div>
    </div>
    @foreach($roles as $role)
    <div class="stat-box">
        <div class="stat-value">{{ number_format(\App\Models\User::where('rol', $role)->count()) }}</div>
        <div class="stat-label">{{ ucfirst($role) }}s</div>
    </div>
    @endforeach
</div>

<!-- Filtros -->
<div class="filter-card">
    <form method="GET" action="{{ route('auditor.tables.users') }}">
        <div class="filter-grid">
            <div class="form-group">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Nombre, email..."
                    value="{{ request('search') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-control">
                    <option value="">Todos</option>
                    @foreach($roles as $role)
                    <option value="{{ $role }}" {{ request('rol') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="filter-actions">
            <a href="{{ route('auditor.tables.users') }}" class="btn btn-secondary">Limpiar</a>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>
</div>

<!-- Tabla de Usuarios -->
<div class="content-card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Email Verificado</th>
                    <th>Fecha de Registro</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>#{{ $user->id }}</td>
                    <td>{{ $user->nombre }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge badge-{{ $user->rol }}">{{ ucfirst($user->rol) }}</span></td>
                    <td>
                        @if($user->email_verified_at)
                        <span style="color: #10b981;">✓ Verificado</span>
                        @else
                        <span style="color: #ef4444;">✗ No verificado</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--gray-text); padding: 2rem;">
                        No se encontraron usuarios
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="pagination">
        {{ $users->links() }}
    </div>
</div>
@endsection