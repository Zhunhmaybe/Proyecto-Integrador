<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historia Clínica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS propio -->
    @vite(['resources/css/doctor/historia_clinica/index.css'])
</head>

<body>

<div class="container">
    <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <a href="{{ route('doctor.dashboard') }}">🧑⚕️Mi perfil</a>
            <a href="{{ route('doctor.pacientes.index') }}" >🧑Pacientes</a>
            <a href="{{ route('doctor.citas.index') }}">📅 Citas</a>
            <a href="{{ route('doctor.historia.index') }}" class="active">📋Historial Clinico</a>
            <a href="{{ route('profile.2fa') }}">🔐 Seguridad 2FA</a>

            <div class="user">
                <strong>{{ Auth::user()->nombre }}</strong><br>
                <small>{{ Auth::user()->nombre_rol }}</small>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
                </form>
            </div>
        </aside>

    <div class="panel">

        <h3 class="text-center">🩺 Historia Clínica</h3>

        {{-- ================= ALERTAS ================= --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- ================= BUSCAR PACIENTE ================= --}}
        <div class="search-box">
            <form method="GET" action="{{ route('doctor.historia.create') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label>Cédula del Paciente</label>
                        <input type="text" name="cedula" class="form-control"
                               value="{{ request('cedula') }}">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary mt-4">🔍 Buscar</button>
                    </div>
                </div>
            </form>

            @if(session('paciente_no_encontrado'))
                <div class="alert alert-danger mt-3">
                    Paciente con cédula <b>{{ session('paciente_no_encontrado') }}</b> no encontrado
                </div>
            @endif
        </div>

        {{-- ================= LISTADO HISTORIAS ================= --}}
        @if(isset($historias) && $historias->count())
            <span class="section-title">📋 Historias Clínicas Registradas</span>

            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>N° Historia</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Profesional</th>
                </tr>
                </thead>
                <tbody>
                @foreach($historias as $h)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $h->numero_historia }}</td>
                        <td>{{ $h->fecha_atencion->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge {{ $h->estado_historia == 'abierta' ? 'badge-abierta' : 'badge-cerrada' }}">
                                {{ ucfirst($h->estado_historia) }}
                            </span>
                        </td>
                        <td>{{ $h->profesional->nombre ?? '—' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        {{-- ================= FORMULARIO CREAR ================= --}}
        @if($paciente)
            <span class="section-title">➕ Nueva Historia Clínica</span>

            <form method="POST" action="{{ route('doctor.historia.store') }}">
                @csrf

                <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

                {{-- DATOS GENERALES --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>N° Historia</label>
                        <input type="text" name="numero_historia" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Fecha Atención</label>
                        <input type="date" name="fecha_atencion" class="form-control"
                               value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Estado</label>
                        <select name="estado_historia" class="form-select">
                            <option value="abierta">Abierta</option>
                            <option value="cerrada">Cerrada</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Motivo de Consulta</label>
                    <textarea name="motivo_consulta" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Enfermedad Actual</label>
                    <textarea name="enfermedad_actual" class="form-control"></textarea>
                </div>

                {{-- ANTECEDENTES --}}
                <span class="section-title">Antecedentes Personales</span>

                <div class="row">
                    @foreach(['cardiopatias'=>'Cardiopatías','diabetes'=>'Diabetes','hipertension'=>'Hipertensión','tuberculosis'=>'Tuberculosis'] as $campo => $label)
                        <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="{{ $campo }}" value="1">
                            <label class="form-check-label">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    <label>Alergias</label>
                    <input type="text" name="alergias" class="form-control">
                </div>

                {{-- CONSTANTES --}}
                <span class="section-title">Constantes Vitales</span>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Temperatura</label>
                        <input type="text" name="temperatura" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Presión Arterial</label>
                        <input type="text" name="presion_arterial" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Pulso</label>
                        <input type="text" name="pulso" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Frecuencia Resp.</label>
                        <input type="text" name="frecuencia_respiratoria" class="form-control">
                    </div>
                </div>

                {{-- OBSERVACIONES --}}
                <span class="section-title">Observaciones</span>
                <textarea name="observaciones" class="form-control"></textarea>

                {{-- BOTONES --}}
                <div class="text-center mt-4">
                    <button class="btn btn-primary">💾 Guardar Historia</button>
                    <a href="{{ route('doctor.historia.create') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        @endif

    </div>
</div>

</body>
</html>
