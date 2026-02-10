<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/admin/usuarios/index.css'])
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

                <a href="{{ route('admin.especialidades.index') }}" class="nav-link icon-especialidades">
                    Especialidades
                </a>

                <a href="{{ route('admin.usuarios.index') }}" class="nav-link icon-users active">
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

        <!-- CONTENT -->
        <main class="content">

            <div class="page-card">

                <!-- HERO -->
                <div class="page-hero">
                    <div class="title">
                        <div style="font-size:28px;">👥</div>
                        <div>
                            <h2>Usuarios</h2>
                            <p>Listado y gestión de usuarios del sistema</p>
                        </div>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-wrap">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuarios as $u)
                                <tr>
                                    <td class="fw-semibold">{{ $u->nombre }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        <span class="badge-role">
                                            {{ $u->nombre_rol }}
                                        </span>
                                    </td>
                                    <td><a href="{{ route('admin.usuarios.edit', $u->id) }}"
                                            class="btn btn-sm btn-warning">
                                            Editar / Rol
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty">
                                        No hay usuarios registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
