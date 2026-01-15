<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Paciente</title>
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
        }

        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: #0b4f79;
            margin-bottom: 4px;
        }

        .page-subtitle {
            color: #6c7a89;
            font-size: 13px;
            margin-bottom: 18px;
        }

        /* ===== CARD PRINCIPAL ===== */
        .panel-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 18px 40px rgba(0,0,0,0.10);
            padding: 22px;
            max-width: 980px;
            width: 100%;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .panel-header h5 {
            margin: 0;
            font-weight: 800;
            color: #0b4f79;
            font-size: 14px;
        }

        /* ===== FORM ===== */
        .section-title {
            font-weight: 800;
            color: #0b4f79;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .form-label {
            font-size: 11px;
            color: #7a8a9a;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            margin-bottom: 6px;
        }

        .form-control, .form-select, textarea {
            border-radius: 10px;
            font-size: 13px;
            padding: 10px 12px;
            border: 1px solid #e7edf3;
            background: #f9fbfd;
        }

        .form-control:focus, .form-select:focus, textarea:focus {
            border-color: #e0b23f;
            box-shadow: 0 0 0 3px rgba(224,178,63,0.18);
            background: #fff;
        }

        .readonly-like {
            background: #f9fbfd;
        }

        .lopdp {
            font-size: 12px;
            color: #51606f;
        }

        /* ===== BOTONES ===== */
        .btn-gold {
            background: #e0b23f;
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 10px 26px;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 10px 22px rgba(224,178,63,0.25);
        }

        .btn-gold:hover {
            background: #c89b2d;
            color: #fff;
        }

        .btn-link-soft {
            color: #6c7a89;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
        }

        .btn-link-soft:hover {
            color: #0b4f79;
            text-decoration: underline;
        }

        /* ===== GRID FORM (2 columnas) ===== */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        @media (max-width: 992px) {
            .content { padding: 20px; }
            .form-grid { grid-template-columns: 1fr; }
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 14px;
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
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

        <a href="{{ url('/home') }}">👤 Mi Perfil</a>
        <a href="{{ route('citas.create') }}">📅 Citas</a>
        <a href="{{ route('pacientes.index') }}" class="active">👥 Pacientes</a>

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

        <div class="page-title">Registrar nuevo paciente</div>
        <div class="page-subtitle">Completa los datos del paciente para agregarlo al directorio.</div>

        @if (session('success'))
            <div class="alert alert-success text-center mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="panel-card">

            <div class="panel-header">
                <h5>Formulario de Registro</h5>
                <a href="{{ route('pacientes.index') }}" class="btn-link-soft">Volver al directorio</a>
            </div>

            <form method="POST" action="{{ route('pacientes.store') }}">
                @csrf

                <div class="form-grid">
                    <!-- COLUMNA IZQUIERDA -->
                    <div>
                        <div class="section-title">1. Datos del Paciente</div>

                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cédula / DNI</label>
<input type="text"
       class="form-control @error('cedula') is-invalid @enderror"
       id="cedula"
       name="cedula"
       value="{{ old('cedula', request('cedula')) }}"
       maxlength="20"
       required>

                            @error('cedula')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nombres" class="form-label">Nombres</label>
                            <input type="text"
                                   class="form-control @error('nombres') is-invalid @enderror"
                                   id="nombres"
                                   name="nombres"
                                   value="{{ old('nombres') }}"
                                   maxlength="100"
                                   required>
                            @error('nombres')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text"
                                   class="form-control @error('apellidos') is-invalid @enderror"
                                   id="apellidos"
                                   name="apellidos"
                                   value="{{ old('apellidos') }}"
                                   maxlength="100"
                                   required>
                            @error('apellidos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono / Celular</label>
                            <input type="text"
                                   class="form-control @error('telefono') is-invalid @enderror"
                                   id="telefono"
                                   name="telefono"
                                   value="{{ old('telefono') }}"
                                   maxlength="20"
                                   required>
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico (opcional)</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="ejemplo@correo.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- COLUMNA DERECHA -->
                    <div>
                        <div class="section-title">2. Información adicional</div>

                        <div class="mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                            <input type="date"
                                   class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                   id="fecha_nacimiento"
                                   name="fecha_nacimiento"
                                   value="{{ old('fecha_nacimiento') }}"
                                   required>
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección / Notas (opcional)</label>
                            <textarea class="form-control @error('direccion') is-invalid @enderror"
                                      id="direccion"
                                      name="direccion"
                                      rows="4"
                                      placeholder="Ej: Calle / Sector / Referencias...">{{ old('direccion') }}</textarea>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input @error('consentimiento_lopdp') is-invalid @enderror"
                                   type="checkbox"
                                   id="consentimiento_lopdp"
                                   name="consentimiento_lopdp"
                                   value="1"
                                   {{ old('consentimiento_lopdp') ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label lopdp" for="consentimiento_lopdp">
                                El paciente acepta la <strong>política de tratamiento de datos (LOPDP)</strong>.
                            </label>
                            @error('consentimiento_lopdp')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('pacientes.index') }}" class="btn-link-soft">Cancelar</a>

                    <button type="submit" class="btn-gold">
                        Guardar Paciente
                    </button>
                </div>

            </form>

        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
