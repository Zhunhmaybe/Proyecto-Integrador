@extends('layouts.auditor')

@section('title', 'Usuarios')
@section('page-title', 'Tabla de Usuarios')

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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
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
            text-decoration: none;
            display: inline-block;
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

        .filter-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1rem;
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

        .badge-admin {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .badge-doctor {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .badge-auditor {
            background: rgba(139, 92, 246, 0.2);
            color: #8b5cf6;
        }

        .badge-recepcionista {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .badge-paciente {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
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

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-box {
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 0.75rem;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--gray-text);
            font-size: 0.875rem;
        }
    </style>

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