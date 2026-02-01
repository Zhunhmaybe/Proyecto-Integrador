@extends('layouts.app')

@section('title', 'Historia Clínica - ' . $historia->paciente->nombres)

@section('content')
<div class="container-fluid py-4">
    
    {{-- Header con acciones --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">
                                <i class="fas fa-file-medical-alt text-primary"></i>
                                Historia Clínica Odontológica
                            </h2>
                            <p class="text-muted mb-0">
                                <span class="badge bg-primary">{{ $historia->numero_historia }}</span>
                                <span class="badge bg-{{ $historia->estado_historia === 'abierta' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($historia->estado_historia) }}
                                </span>
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('historia_clinica.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <a href="{{ route('historia_clinica.odontograma', $historia->id) }}" class="btn btn-warning">
                                <i class="fas fa-tooth"></i> Odontograma
                            </a>
                            <a href="{{ route('historia_clinica.pdf', $historia->id) }}" class="btn btn-danger" target="_blank">
                                <i class="fas fa-file-pdf"></i> Generar PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Columna Principal --}}
        <div class="col-md-8">
            
            {{-- Datos del Paciente --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">👤 DATOS DEL PACIENTE</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombres:</strong> {{ $historia->paciente->nombres }}</p>
                            <p><strong>Apellidos:</strong> {{ $historia->paciente->apellidos }}</p>
                            <p><strong>Cédula:</strong> {{ $historia->paciente->cedula }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha de Nacimiento:</strong> {{ $historia->paciente->fecha_nacimiento->format('d/m/Y') }}</p>
                            <p><strong>Edad:</strong> {{ $historia->paciente->fecha_nacimiento->age }} años</p>
                            <p><strong>Teléfono:</strong> {{ $historia->paciente->telefono }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Dirección:</strong> {{ $historia->paciente->direccion ?? 'No registrada' }}</p>
                            <p><strong>Email:</strong> {{ $historia->paciente->email ?? 'No registrado' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Motivo de Consulta y Enfermedad Actual --}}
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📋 CONSULTA</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">Motivo de Consulta:</h6>
                        <p>{{ $historia->motivo_consulta }}</p>
                    </div>
                    @if($historia->enfermedad_actual)
                    <div>
                        <h6 class="fw-bold">Enfermedad Actual:</h6>
                        <p>{{ $historia->enfermedad_actual }}</p>
                    </div>
                    @endif
                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Fecha de Atención:</strong> {{ $historia->fecha_atencion->format('d/m/Y') }} |
                            <strong>Profesional:</strong> {{ $historia->profesional->nombre ?? 'No asignado' }}
                        </small>
                    </div>
                </div>
            </div>

            {{-- Antecedentes --}}
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">🩺 ANTECEDENTES</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Antecedentes Personales:</h6>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled {{ $historia->cardiopatias ? 'checked' : '' }}>
                                <label>Cardiopatías</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled {{ $historia->diabetes ? 'checked' : '' }}>
                                <label>Diabetes</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled {{ $historia->hipertension ? 'checked' : '' }}>
                                <label>Hipertensión</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled {{ $historia->tuberculosis ? 'checked' : '' }}>
                                <label>Tuberculosis</label>
                            </div>
                        </div>
                    </div>
                    
                    @if($historia->alergias)
                    <div class="alert alert-danger">
                        <strong>⚠️ Alergias:</strong> {{ $historia->alergias }}
                    </div>
                    @endif

                    @if($historia->antecedentes_otros)
                    <p><strong>Otros:</strong> {{ $historia->antecedentes_otros }}</p>
                    @endif

                    <hr>

                    <h6 class="fw-bold mb-3">Antecedentes Familiares:</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled {{ $historia->fam_diabetes ? 'checked' : '' }}>
                                <label>Diabetes</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled {{ $historia->fam_hipertension ? 'checked' : '' }}>
                                <label>Hipertensión</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled {{ $historia->fam_cancer ? 'checked' : '' }}>
                                <label>Cáncer</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" disabled {{ $historia->fam_tuberculosis ? 'checked' : '' }}>
                                <label>Tuberculosis</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Examen Clínico --}}
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">🔍 EXAMEN DEL SISTEMA ESTOMATOGNÁTICO</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <strong>Labios:</strong> {{ $historia->labios ?? 'No evaluado' }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>Lengua:</strong> {{ $historia->lengua ?? 'No evaluado' }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>Paladar:</strong> {{ $historia->paladar ?? 'No evaluado' }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>Piso de Boca:</strong> {{ $historia->piso_boca ?? 'No evaluado' }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>Encías:</strong> {{ $historia->encias ?? 'No evaluado' }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>Carrillos:</strong> {{ $historia->carrillos ?? 'No evaluado' }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>Orofaringe:</strong> {{ $historia->orofaringe ?? 'No evaluado' }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>ATM:</strong> {{ $historia->atm ?? 'No evaluado' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Diagnósticos --}}
            <div class="card mb-4">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🎯 DIAGNÓSTICOS</h5>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modalNuevoDiagnostico">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    @if($historia->diagnosticos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historia->diagnosticos as $diagnostico)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $diagnostico->tipo === 'presuntivo' ? 'warning' : 'success' }}">
                                                {{ ucfirst($diagnostico->tipo) }}
                                            </span>
                                        </td>
                                        <td>{{ $diagnostico->descripcion }}</td>
                                        <td>{{ $diagnostico->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No hay diagnósticos registrados</p>
                    @endif
                </div>
            </div>

            {{-- Tratamientos --}}
            <div class="card mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">💊 TRATAMIENTOS Y PROCEDIMIENTOS</h5>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modalNuevoTratamiento">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    @if($historia->tratamientos->count() > 0)
                        @foreach($historia->tratamientos as $tratamiento)
                        <div class="mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-2">Sesión {{ $loop->iteration }} - {{ $tratamiento->fecha->format('d/m/Y') }}</h6>
                                <small class="text-muted">{{ $tratamiento->firma_profesional }}</small>
                            </div>
                            <p class="mb-1"><strong>Procedimiento:</strong> {{ $tratamiento->procedimiento }}</p>
                            @if($tratamiento->prescripcion)
                            <p class="mb-0"><strong>Prescripción:</strong> {{ $tratamiento->prescripcion }}</p>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0">No hay tratamientos registrados</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- Columna Lateral --}}
        <div class="col-md-4">
            
            {{-- Constantes Vitales --}}
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">❤️ CONSTANTES VITALES</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-thermometer-half text-danger"></i>
                        <strong>Temperatura:</strong> {{ $historia->temperatura ?? '-' }} °C
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-heartbeat text-danger"></i>
                        <strong>Presión Arterial:</strong> {{ $historia->presion_arterial ?? '-' }} mmHg
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-heart text-danger"></i>
                        <strong>Pulso:</strong> {{ $historia->pulso ?? '-' }} lpm
                    </div>
                    <div class="mb-0">
                        <i class="fas fa-lungs text-info"></i>
                        <strong>Frec. Respiratoria:</strong> {{ $historia->frecuencia_respiratoria ?? '-' }} rpm
                    </div>
                </div>
            </div>

            {{-- Resumen Odontograma --}}
            @if($historia->tieneOdontograma())
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🦷 RESUMEN ODONTOGRAMA</h5>
                </div>
                <div class="card-body">
                    @php
                        $cariados = $historia->odontograma->where('estado', 'caries')->count();
                        $obturados = $historia->odontograma->where('estado', 'obturado')->count();
                        $perdidos = $historia->odontograma->whereIn('estado', ['perdido_caries', 'perdido_otra'])->count();
                    @endphp
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>🔴 Cariados:</span>
                        <strong>{{ $cariados }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>🔵 Obturados:</span>
                        <strong>{{ $obturados }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>❌ Perdidos:</span>
                        <strong>{{ $perdidos }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total CPO:</span>
                        <strong class="text-primary">{{ $cariados + $obturados + $perdidos }}</strong>
                    </div>
                    
                    <a href="{{ route('historia_clinica.odontograma', $historia->id) }}" class="btn btn-sm btn-primary w-100 mt-3">
                        <i class="fas fa-tooth"></i> Ver Odontograma Completo
                    </a>
                </div>
            </div>
            @endif

            {{-- Exámenes Complementarios --}}
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">🔬 EXÁMENES COMPLEMENTARIOS</h5>
                </div>
                <div class="card-body">
                    @if($historia->examenesComplementarios->count() > 0)
                        @foreach($historia->examenesComplementarios as $examen)
                        <div class="mb-2 pb-2 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $examen->nombre_examen }}</strong>
                                <span class="badge bg-{{ $examen->estado === 'completado' ? 'success' : 'warning' }}">
                                    {{ ucfirst($examen->estado) }}
                                </span>
                            </div>
                            <small class="text-muted">{{ $examen->fecha_solicitud->format('d/m/Y') }}</small>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0 small">Sin exámenes</p>
                    @endif
                </div>
            </div>

            {{-- Observaciones --}}
            @if($historia->observaciones)
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">📝 OBSERVACIONES</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $historia->observaciones }}</p>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

{{-- Modal Nuevo Diagnóstico --}}
<div class="modal fade" id="modalNuevoDiagnostico" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Diagnóstico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('historia_clinica.agregar_diagnostico', $historia->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select" required>
                            <option value="presuntivo">Presuntivo</option>
                            <option value="definitivo">Definitivo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Nuevo Tratamiento --}}
<div class="modal fade" id="modalNuevoTratamiento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Tratamiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('historia_clinica.agregar_tratamiento', $historia->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Procedimiento</label>
                        <textarea name="procedimiento" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prescripción (opcional)</label>
                        <textarea name="prescripcion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection