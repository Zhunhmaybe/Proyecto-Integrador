<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Doctor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/admin/Doctores/edit.css'])
</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
        </div>

        <nav>
            <a href="{{ route('admin.dashboard') }}" class="nav-link icon-profile">Mi perfil</a>
            <a href="{{ route('admin.pacientes.index') }}" class="nav-link icon-pacientes">Pacientes</a>
            <a href="{{ route('admin.doctores.index') }}" class="nav-link icon-doctores active">Doctores</a>
            <a href="{{ route('admin.especialidades.index') }}" class="nav-link icon-especialidades">Especialidades</a>
            <a href="{{ route('admin.usuarios.index') }}" class="nav-link icon-users">Usuarios</a>
            <a href="{{ route('admin.citas.create') }}" class="nav-link icon-citas">Citas</a>
            <a href="{{ route('admin.roles.index') }}" class="nav-link icon-roles">Roles</a>
            <a href="{{ route('profile.2fa') }}" class="nav-link icon-seguridad">Seguridad 2FA</a>
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
                <div>
                    <h4>✏️ Editar Doctor</h4>
                    <small>Actualizar información del doctor</small>
                </div>
            </div>

            <!-- BODY -->
            <div class="profile-body">

                {{-- ERRORES --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.doctores.update', $doctor->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input name="nombre" class="form-control"
                               value="{{ $doctor->nombre }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ $doctor->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="tel" class="form-control"
                               value="{{ $doctor->tel }}"
                               maxlength="10"
                               inputmode="numeric"
                               pattern="[0-9]{10}"
                               oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="1" {{ $doctor->estado ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ !$doctor->estado ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <!-- BOTONES -->
                    <div class="action-buttons">
                        <a href="{{ route('admin.doctores.index') }}" class="btn btn-light">
                            Volver
                        </a>

                        <button type="submit" class="btn-edit">
                            Guardar Cambios
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </main>

</div>

</body>
</html>
