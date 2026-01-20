<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Recepcionista</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/recepcionista/edit.css'])
</head>

<body>

    <div class="wrapper">


        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <a href="{{ route('admin.dashboard') }}" class="active">Mi perfil</a>
            <a href="{{ route('doctor.pacientes.index') }}">Pacientes</a>
            <a href="{{ route('citas.create') }}">📅 Citas</a>
            <a href="{{ route('admin.dashboard') }}">Historial Clinico</a>

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
            <h3 class="fw-bold mb-4">Editar Perfil</h3>

            <div class="profile-card">

                <div class="profile-header">
                    <div class="avatar"></div>
                    <div>
                        <h4>{{ Auth::user()->nombre }}</h4>
                        <small>{{ Auth::user()->nombre_rol }}</small>
                    </div>
                </div>

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

                    <form method="POST" action="{{ route('perfil.update') }}">
                        @csrf
                        @method('PUT')

                        <h5 class="fw-bold mb-4">Información Personal</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre Completo</label>
                                <input type="text"
                                    name="nombre"
                                    class="form-control"
                                    value="{{ old('nombre', Auth::user()->nombre) }}"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Correo Electrónico</label>
                                <input class="form-control"
                                    name="email"
                                    value="{{ Auth::user()->email }}"
                                    readonly>

                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="text"
                                    name="tel"
                                    class="form-control"
                                    maxlength="10"
                                    value="{{ old('tel', Auth::user()->tel) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select">
                                    <option value="1" {{ Auth::user()->estado == 1 ? 'selected' : '' }}>Activo</option>
                                    <option value="2" {{ Auth::user()->estado == 2 ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="actions">
                            <button type="submit" class="btn-save">Guardar Cambios</button>
                            <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary btn-cancel">
                                Cancelar
                            </a>
                        </div>

                    </form>
                </div>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>