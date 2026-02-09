<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Doctor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/admin/Doctores/create.css'])
</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
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

                <a href="{{ route('admin.doctores.index') }}" class="nav-link icon-doctores active">
                    Doctores
                </a>

                <a href="{{ route('admin.especialidades.index') }}" class="nav-link icon-especialidades">
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

    <!-- CONTENT -->
    <main class="content">

        <div class="profile-card">

            <!-- HEADER -->
            <div class="profile-header">
                <h4>👨‍⚕️ Crear Doctor</h4>
                <small>Registro de nuevo doctor en el sistema</small>
            </div>

            <!-- BODY -->
            <div class="profile-body">

                <form method="POST" action="{{ route('admin.doctores.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input name="nombre" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input name="email" type="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input name="tel" class="form-control" maxlength="10" inputmode="numeric">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Contraseña</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('admin.doctores.index') }}" class="btn btn-light">
                            Volver
                        </a>

                        <button class="btn btn-gold ms-2">
                            Guardar Doctor
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </main>
</div>

</body>
</html>
