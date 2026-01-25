<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Roles del Sistema</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/admin/admin-panel.css'])
</head>

<body class="bg-light">

    <div class="wrapper">

        {{-- SIDEBAR --}}
        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <a href="{{ route('admin.dashboard') }}" >🧑‍💼Mi perfil</a>
            <a href="{{ route('admin.pacientes.index') }}" >🧑‍🦳Pacientes</a>
            <a href="{{ route('admin.doctores.index') }}" >🧑Doctores</a>
            <a href="{{ route('admin.especialidades.index') }}" >⚕️Especialidades</a>
            <a href="{{ route('admin.usuarios.index') }}" >👥Usuarios</a>
            <a href="{{ route('admin.citas.create') }}">📅Citas</a>
            <a href="{{ route('admin.roles.index') }}" class="active">🛡️Roles</a>
            <a href="{{ route('profile.2fa') }}">🔐Seguridad 2FA</a>

            <div class="user">
                <strong>{{ Auth::user()->nombre }}</strong><br>
                <small>{{ Auth::user()->nombre_rol }}</small>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
                </form>
            </div>
        </aside>

        {{-- CONTENIDO --}}
        <main class="content">

            <div class="panel">

                <div class="mb-4">
                    <h4 class="fw-bold">🛡 Roles del Sistema</h4>
                    <small class="text-muted">
                        Roles existentes según usuarios registrados
                    </small>
                </div>

                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID Rol</th>
                            <th>Nombre del Rol</th>
                            <th class="text-center">Usuarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $rol)
                            <tr>
                                <td>{{ $rol->rol }}</td>
                                <td>
                                    @php
                                        $nombre = match ($rol->rol) {
                                            0 => 'Doctor',
                                            1 => 'Administrador',
                                            2 => 'Auditor',
                                            3 => 'Recepcionista',
                                            4 => 'Usuario',
                                            default => 'Desconocido',
                                        };
                                    @endphp
                                    {{ $nombre }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">
                                        {{ $rol->total }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </main>
    </div>

</body>

</html>
