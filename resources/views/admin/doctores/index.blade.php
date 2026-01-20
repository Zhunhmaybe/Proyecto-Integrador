<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Doctores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/admin/Doctores/index.css'])
</head>

<body>

    <div class="wrapper">

        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <a href="{{ route('admin.dashboard') }}" >Mi perfil</a>
            <a href="{{ route('admin.pacientes.index') }}">Pacientes</a>
            <a href="{{ route('admin.doctores.index') }}" class="active">👤 Doctores</a>
            <a href="{{ route('admin.especialidades.index') }}">Especialidades</a>
            <a href="{{ route('admin.usuarios.index') }}">Usuarios</a>
            <a href="{{ route('admin.citas.create') }}">📅 Citas</a>
            <a href="{{ route('admin.roles.index') }}">Roles</a>


            <div class="user">
                <strong>{{ Auth::user()->nombre }}</strong><br>
                <small>{{ Auth::user()->nombre_rol }}</small>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
                </form>
            </div>
        </aside>

        <main class="content">

            <div class="profile-card">

                <!-- HEADER -->
                <div class="profile-header">
                    <div>
                        <h4>👨‍⚕️ Doctores</h4>
                        <small>Listado y gestión de doctores del sistema</small>
                    </div>

                    <div class="ms-auto">
                        <a href="{{ route('admin.doctores.create') }}" class="btn-edit">
                            + Nuevo Doctor
                        </a>
                    </div>
                </div>

                <!-- BODY -->
                <div class="profile-body">

                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($doctores as $d)
                                <tr>
                                    <td class="fw-semibold">
                                        {{ $d->nombre }}
                                    </td>

                                    <td>{{ $d->email }}</td>

                                    <td>{{ $d->tel ?? '—' }}</td>

                                    <td>
                                        <span class="badge {{ $d->estado ? 'bg-success' : 'bg-danger' }}">
                                            {{ $d->estado ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('admin.doctores.edit', $d->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            ✏️ Editar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </main>

    </div>
</body>

</html>
