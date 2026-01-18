<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Doctor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS BASE DEL SISTEMA --}}
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

        /* SIDEBAR */
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

        /* CONTENT */
        .content {
            flex: 1;
            padding: 30px 40px;
            display: flex;
            justify-content: center;
        }

        /* CARD */
        .profile-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,.12);
            max-width: 700px;
            width: 100%;
            overflow: hidden;
        }

        .profile-header {
            background: #b7d8ee;
            padding: 22px 30px;
        }

        .profile-header h4 {
            margin: 0;
            font-weight: bold;
        }

        .profile-body {
            padding: 30px;
        }

        .btn-gold {
            background: #e0b23f;
            color: #fff;
            border-radius: 25px;
            border: none;
            padding: 10px 35px;
        }

        .btn-gold:hover {
            background: #c89b2d;
        }

        input {
            border-radius: 8px !important;
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

            <a href="{{ route('admin.dashboard') }}">Mi perfil</a>
            <a href="{{ route('pacientes.index') }}">Pacientes</a>
            <a href="{{ route('admin.doctores.index') }}" class="active">👤 Doctores</a>
            <a href="{{ route('admin.especialidades.index') }}">Especialidades</a>
            <a href="{{ route('admin.dashboard') }}">Usuarios</a>
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
