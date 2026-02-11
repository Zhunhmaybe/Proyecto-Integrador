<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/admin/usuarios/edit.css'])
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
                        <div style="font-size:28px;">✏️</div>
                        <div>
                            <h2>Editar Usuario</h2>
                            <p>Modificar datos y rol del usuario</p>
                        </div>
                    </div>
                </div>

                <!-- FORM -->
                <div class="form-wrap">

                    <form method="POST" action="{{ route('admin.usuarios.update', $user->id) }}">

                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control"
                                    value="{{ old('nombre', $user->nombre) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Rol</label>
                                <select name="rol" class="form-select" required>
                                    @foreach ($roles as $id => $rol)
                                        <option value="{{ $id }}" {{ $user->rol == $id ? 'selected' : '' }}>
                                            {{ $rol }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-12 d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline">
                                    ⬅ Volver
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    💾 Guardar cambios
                                </button>
                            </div>

                        </div>
                    </form>


                </div>

            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
