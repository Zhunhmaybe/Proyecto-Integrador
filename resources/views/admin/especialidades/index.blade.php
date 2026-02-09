<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Especialidades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/admin/especialidades/index-espe.css'])
</head>

<body>

    <div class="wrapper">

        {{-- SIDEBAR --}}
        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <nav>
                <a href="{{ route('admin.dashboard') }}" class="nav-link icon-profile ">
                    Mi perfil
                </a>

                <a href="{{ route('admin.pacientes.index') }}" class="nav-link icon-pacientes">
                    Pacientes
                </a>

                <a href="{{ route('admin.doctores.index') }}" class="nav-link icon-doctores ">
                    Doctores
                </a>

                <a href="{{ route('admin.especialidades.index') }}" class="nav-link icon-especialidades active">
                    Especialidades
                </a>

                <a href="{{ route('admin.usuarios.index') }}" class="nav-link icon-users">
                    Usuarios
                </a>

                <a href="{{ route('admin.citas.create') }}" class="nav-link icon-citas">
                    Citas
                </a>

                <a href="{{ route('admin.roles.index') }}" class="nav-link icon-roles">
                    Roles
                </a>

                <a href="{{ route('profile.2fa') }}" class="nav-link icon-seguridad">
                    Seguridad 2FA
                </a>
            </nav>

            <div class="user">
                <strong>{{ Auth::user()->nombre }}</strong><br>
                <small>{{ Auth::user()->nombre_rol }}</small>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
                </form>
            </div>
        </aside>

        {{-- CONTENT --}}
        <main class="content">

            {{-- HEADER --}}
            <div class="header-box mb-4">
                <div>
                    <h4 class="fw-bold mb-1">🦷 Especialidades</h4>
                    <small class="text-muted">Gestión de especialidades del sistema</small>
                </div>

                <a href="{{ route('admin.especialidades.create') }}" class="btn btn-gold">
                    + Nueva Especialidad
                </a>
            </div>

            {{-- PANEL --}}
            <div class="panel">

                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($especialidades as $e)
                            <tr>
                                <td>{{ $e->nombre }}</td>
                                <td class="text-end">

                                    <a href="{{ route('admin.especialidades.edit', $e->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        ✏️ Editar
                                    </a>

                                    <form method="POST" action="{{ route('admin.especialidades.destroy', $e->id) }}"
                                        class="d-inline" onsubmit="return confirm('¿Eliminar esta especialidad?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-outline-danger">
                                            🗑 Eliminar
                                        </button>
                                    </form>

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
