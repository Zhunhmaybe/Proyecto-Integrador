<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Recepcionista</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: #0b4f79;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .sidebar .logo img {
            width: 110px;
        }

        .sidebar a {
            color: #cfe6f5;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }

        .sidebar .user {
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 15px;
            font-size: 13px;
        }

        /* ===== CONTENT ===== */
        .content {
            flex: 1;
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ===== CARD ===== */
        .profile-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            max-width: 900px;
            width: 100%;
            overflow: hidden;
        }

        .profile-header {
            background: #b7d8ee;
            padding: 22px 30px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #ddd;
            border: 3px solid #f2b705;
            position: relative;
        }

        .profile-header h4 {
            margin: 0;
            font-weight: bold;
        }

        .profile-header small {
            color: #555;
        }

        .profile-body {
            padding: 30px;
        }

        .form-label {
            font-size: 13px;
            color: #555;
        }

        .form-control, .form-select {
            border-radius: 8px;
            font-size: 14px;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }

        .btn-save {
            background: #e0b23f;
            color: white;
            border-radius: 20px;
            padding: 8px 40px;
            border: none;
        }

        .btn-save:hover {
            background: #c89b2d;
        }

        .btn-cancel {
            border-radius: 20px;
            padding: 8px 40px;
        }
    </style>
</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
        </div>

        <a href="{{ route('home') }}" class="active">👤 Mi Perfil</a>
        <a href="{{ route('citas.create') }}">📅 Citas</a>
        <a href="{{ route('pacientes.index') }}">👥 Pacientes</a>

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
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-cancel">
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
