<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Paciente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/recepcionista/paciente/create.css'])
</head>

<body>

    <div class="wrapper">


        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <a href="{{ url('home') }}">👤 Mi Perfil</a>
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

                        <div>
                            <div class="section-title">1. Datos del Paciente</div>

                            <div class="mb-3">
                                <label for="cedula" class="form-label">Cédula / DNI</label>
                                <input type="text"
                                    class="form-control @error('cedula') is-invalid @enderror"
                                    id="cedula"
                                    name="cedula"
                                    value="{{ old('cedula', request('cedula')) }}"
                                    maxlength="10"
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
                                    maxlength="10"
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