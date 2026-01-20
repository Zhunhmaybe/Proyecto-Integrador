<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f4f7fb; font-family:'Segoe UI',sans-serif; }
        .panel {
            background:#fff;
            border-radius:18px;
            box-shadow:0 15px 35px rgba(0,0,0,.12);
            padding:30px;
            max-width:800px;
            margin:auto;
        }
        .btn-gold {
            background:#e0b23f;
            color:#fff;
            border-radius:25px;
            padding:10px 35px;
            border:none;
        }
        .btn-gold:hover { background:#c89b2d; }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="panel">

        <h4 class="fw-bold mb-4">✏️ Editar Cita</h4>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.citas.update',$cita->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Paciente</label>
                    <input class="form-control" disabled
                           value="{{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Doctor</label>
                    <select name="doctor_id" class="form-select" required>
                        @foreach($doctores as $d)
                            <option value="{{ $d->id }}"
                                {{ $cita->doctor_id == $d->id ? 'selected' : '' }}>
                                {{ $d->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Especialidad</label>
                    <select name="especialidad_id" class="form-select" required>
                        @foreach($especialidades as $e)
                            <option value="{{ $e->id }}"
                                {{ $cita->especialidad_id == $e->id ? 'selected' : '' }}>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="pendiente" {{ $cita->estado=='pendiente'?'selected':'' }}>Pendiente</option>
                        <option value="confirmada" {{ $cita->estado=='confirmada'?'selected':'' }}>Confirmada</option>
                        <option value="cancelada" {{ $cita->estado=='cancelada'?'selected':'' }}>Cancelada</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="datetime-local"
                           name="fecha_inicio"
                           class="form-control"
                           value="{{ $cita->fecha_inicio->format('Y-m-d\TH:i') }}"
                           required>
                </div>

                <div class="col-12">
                    <label class="form-label">Motivo</label>
                    <textarea name="motivo"
                              class="form-control"
                              rows="3">{{ $cita->motivo }}</textarea>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('admin.pacientes.index') }}" class="btn btn-light">
                    Volver
                </a>
                <button class="btn btn-gold ms-2">
                    Guardar Cambios
                </button>
            </div>

        </form>

    </div>

</div>

</body>
</html>
