<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Especialidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/admin/especialidades/index-espe.css'])
</head>
<body>

<div class="wrapper">

        {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png">
        </div>

            <a href="{{ route('admin.dashboard') }}" >Mi perfil</a>
            <a href="{{ route('pacientes.index') }}" >Pacientes</a>
            <a href="{{ route('admin.doctores.index') }}" >👤 Doctores</a>
            <a href="{{ route('admin.especialidades.index') }}" class="active">Especialidades</a>
            <a href="{{ route('admin.dashboard') }}" >Usuarios</a>
            <a href="{{ route('citas.create') }}">📅 Citas</a>
            <a href="{{ route('pacientes.index') }}">Roles</a>

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

        <div class="header-box mb-4">
            <div>
                <h4 class="fw-bold mb-1">➕ Nueva Especialidad</h4>
                <small class="text-muted">Registrar una nueva especialidad médica</small>
            </div>
        </div>

        <div class="panel mx-auto" style="max-width:500px">

            <form method="POST" action="{{ route('admin.especialidades.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre de la Especialidad</label>
                    <input type="text"
                           name="nombre"
                           class="form-control"
                           placeholder="Ej: Ortodoncia"
                           required>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.especialidades.index') }}"
                       class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                    <button class="btn btn-gold px-4">
                        Guardar
                    </button>
                </div>
            </form>

        </div>
    </main>
</div>

</body>
</html>
