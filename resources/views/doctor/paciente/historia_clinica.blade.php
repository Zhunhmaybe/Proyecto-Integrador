<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historia Clínica del Paciente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS (puedes reutilizar el de citas o el que hicimos) -->
    @vite(['resources/css/doctor/historia_clinica/historia_clinica.css'])
</head>

<body>

    <div class="wrapper">

        {{-- ================= SIDEBAR ================= --}}
        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>
            <a href="{{ route('doctor.dashboard') }}" class="active">Mi perfil</a>
            <a href="{{ route('doctor.pacientes.index') }}">Pacientes</a>
            <a href="{{ route('doctor.citas.index') }}">📅 Citas</a>
            <a href="{{ route('doctor.historia.index') }}">Historial Clinico</a>
            <div class="user">
                <strong>{{ Auth::user()->nombre }}</strong><br>
                <small>{{ Auth::user()->nombre_rol }}</small>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
                </form>
            </div>
        </aside>

        {{-- ================= CONTENIDO ================= --}}
        <main class="content">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-0">Historia Clínica</h4>
                    <small class="text-muted">
                        {{ $paciente->nombres }} {{ $paciente->apellidos }} — {{ $paciente->cedula }}
                    </small>
                </div>

                <a href="{{ route('doctor.pacientes.index', ['paciente' => $paciente->id]) }}"
                    class="btn btn-outline-secondary">
                    ⬅ Volver
                </a>
            </div>

            <div class="panel">

                {{-- ================= SIN HISTORIAS ================= --}}
                @if ($paciente->historiasClinicas->isEmpty())
                    <div class="text-center text-muted py-5">
                        Este paciente no tiene historias clínicas registradas.
                    </div>
                @else
                    {{-- ================= LISTADO HISTORIAS ================= --}}
                    @foreach ($paciente->historiasClinicas as $historia)
                        <div class="cita-card">

                            {{-- CABECERA --}}
                            <div class="d-flex justify-content-between mb-2">
                                <strong>
                                    Historia #{{ $historia->numero_historia }}
                                </strong>

                                <span
                                    class="badge {{ $historia->estado_historia == 'abierta' ? 'badge-abierta' : 'badge-cerrada' }}">
                                    {{ ucfirst($historia->estado_historia) }}
                                </span>
                            </div>

                            {{-- DATOS GENERALES --}}
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <small class="text-muted">Fecha Atención</small><br>
                                    {{ \Carbon\Carbon::parse($historia->fecha_atencion)->format('d/m/Y') }}
                                </div>

                                <div class="col-md-4">
                                    <small class="text-muted">Profesional</small><br>
                                    {{ $historia->profesional->nombre ?? '—' }}
                                </div>
                            </div>

                            {{-- MOTIVO --}}
                            <div class="mt-2">
                                <small class="text-muted">Motivo de Consulta</small><br>
                                {{ $historia->motivo_consulta ?? '—' }}
                            </div>

                            {{-- ENFERMEDAD ACTUAL --}}
                            <div class="mt-2">
                                <small class="text-muted">Enfermedad Actual</small><br>
                                {{ $historia->enfermedad_actual ?? '—' }}
                            </div>

                            {{-- CONSTANTES --}}
                            <div class="mt-3">
                                <small class="text-muted">Constantes Vitales</small>
                                <div class="row">
                                    <div class="col-md-3">Temp: {{ $historia->temperatura ?? '—' }}</div>
                                    <div class="col-md-3">PA: {{ $historia->presion_arterial ?? '—' }}</div>
                                    <div class="col-md-3">Pulso: {{ $historia->pulso ?? '—' }}</div>
                                    <div class="col-md-3">FR: {{ $historia->frecuencia_respiratoria ?? '—' }}</div>
                                </div>
                            </div>

                            {{-- OBSERVACIONES --}}
                            <div class="mt-3">
                                <small class="text-muted">Observaciones</small><br>
                                {{ $historia->observaciones ?? '—' }}
                            </div>

                        </div>
                    @endforeach
                @endif

            </div>

        </main>
    </div>

</body>

</html>
