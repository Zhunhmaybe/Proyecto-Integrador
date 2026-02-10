<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Especialidad</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/admin/especialidades/edit.css'])
</head>

<body>

<div class="wrapper">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
        </div>

        <nav>
            <a href="{{ route('admin.dashboard') }}" class="nav-link icon-profile">
                Mi perfil
            </a>

            <a href="{{ route('admin.pacientes.index') }}" class="nav-link icon-pacientes">
                Pacientes
            </a>

            <a href="{{ route('admin.doctores.index') }}" class="nav-link icon-doctores">
                Doctores
            </a>

            <a href="{{ route('admin.especialidades.index') }}"
               class="nav-link icon-especialidades active">
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

        <div class="panel">

            <div class="profile-header">
                <div>
                    <h4>✏️ Editar Especialidad</h4>
                </div>
            </div>

            <form method="POST"
                  action="{{ route('admin.especialidades.update', $especialidad->id) }}"
                  class="mt-4">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text"
                           name="nombre"
                           class="form-control"
                           value="{{ $especialidad->nombre }}"
                           required>
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.especialidades.index') }}"
                       class="btn btn-light">
                        Volver
                    </a>

                    <button class="btn btn-gold ms-2">
                        Guardar Cambios
                    </button>
                </div>

            </form>

        </div>

    </main>

</div>

</body>
</html>
