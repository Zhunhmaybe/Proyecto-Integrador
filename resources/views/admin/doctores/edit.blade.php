<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Doctor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        /* ===== LAYOUT ===== */
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
            justify-content: center;
            align-items: flex-start;
        }

        /* ===== PANEL ===== */
        .panel {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,.12);
            padding: 35px;
            max-width: 700px;
            width: 100%;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0b23f;
            display: inline-block;
            padding-bottom: 5px;
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

        input, select {
            border-radius: 10px !important;
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
            <a href="{{ route('admin.usuarios.index') }}">Usuarios</a>
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

        <div class="panel">

            <h4 class="section-title">✏️ Editar Doctor</h4>

            {{-- ERRORES --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.doctores.update', $doctor->id) }}" class="mt-4">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input
                        name="nombre"
                        class="form-control"
                        value="{{ $doctor->nombre }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ $doctor->email }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input
                        type="text"
                        name="tel"
                        class="form-control"
                        value="{{ $doctor->tel }}"
                        required
                        maxlength="10"
                        inputmode="numeric"
                        pattern="[0-9]{10}"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        title="Debe contener exactamente 10 números">
                </div>

                <div class="mb-4">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="1" {{ $doctor->estado ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !$doctor->estado ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.doctores.index') }}" class="btn btn-light">
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
