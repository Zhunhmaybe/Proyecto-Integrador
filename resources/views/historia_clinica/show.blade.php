@extends('layouts.app')

@section('title', 'Historia Clínica - ' . $historia->paciente->nombres)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/historia_clinica/historia_clinica.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    <div class="header-banner">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h2>
                    <i class="fas fa-file-medical-alt"></i>
                    Historia Clínica Odontológica
                </h2>
                <div class="d-flex gap-2 mb-2">
                    <span class="badge bg-light text-dark">{{ $historia->numero_historia }}</span>
                    <span class="badge bg-{{ $historia->estado_historia === 'abierta' ? 'success' : 'secondary' }}">
                        {{ ucfirst($historia->estado_historia) }}
                    </span>
                </div>
                <p class="mb-0 opacity-90">
                    <i class="fas fa-user-md me-2"></i>
                    Dr. {{ $historia->profesional->nombre ?? 'No asignado' }} |
                    <i class="fas fa-calendar me-2 ms-3"></i>
                    {{ $historia->fecha_atencion->format('d/m/Y') }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('historia_clinica.index') }}" class="btn btn-light btn-action">
                    <i class="fas fa-arrow-left me-2"></i> Volver
                </a>
                <a href="{{ route('historia_clinica.odontograma', $historia->id) }}" class="btn btn-action btn-gradient-warning">
                    <i class="fas fa-tooth me-2"></i> Odontograma
                </a>
                <a href="{{ route('historia_clinica.pdf', $historia->id) }}" class="btn btn-action btn-gradient-danger" target="_blank">
                    <i class="fas fa-file-pdf me-2"></i> PDF
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="info-card">
                <div class="card-header-custom card-header-primary">
                    <i class="fas fa-user-circle"></i>
                    <span>DATOS DEL PACIENTE</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-box-icon icon-primary">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="info-box-content">
                                    <small>Paciente</small>
                                    <strong>{{ $historia->paciente->nombres }} {{ $historia->paciente->apellidos }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-box-icon icon-info">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="info-box-content">
                                    <small>Cédula</small>
                                    <strong>{{ $historia->paciente->cedula }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-box-icon icon-success">
                                    <i class="fas fa-birthday-cake"></i>
                                </div>
                                <div class="info-box-content">
                                    <small>Edad</small>
                                    <strong>{{ $historia->paciente->fecha_nacimiento->age }} años</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-box-icon icon-warning">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="info-box-content">
                                    <small>Teléfono</small>
                                    <strong>{{ $historia->paciente->telefono }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-box-icon icon-danger">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="info-box-content">
                                    <small>Email</small>
                                    <strong>{{ $historia->paciente->email ?? 'No registrado' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header-custom card-header-info">
                    <i class="fas fa-clipboard-list"></i>
                    <span>MOTIVO DE CONSULTA</span>
                </div>
                <div class="card-body">
                    <p class="mb-3 fs-6">{{ $historia->motivo_consulta }}</p>

                    @if($historia->enfermedad_actual)
                    <hr>
                    <h6 class="fw-bold mb-2"><i class="fas fa-notes-medical me-2 text-primary"></i>Enfermedad Actual:</h6>
                    <p class="mb-0">{{ $historia->enfermedad_actual }}</p>
                    @endif
                </div>
            </div>

            <div class="info-card">
                <div class="card-header-custom card-header-warning">
                    <i class="fas fa-heartbeat"></i>
                    <span>ANTECEDENTES MÉDICOS</span>
                </div>
                <div class="card-body">

                    <h6 class="fw-bold mb-3"><i class="fas fa-disease me-2 text-danger"></i>Antecedentes Personales:</h6>

                    {{-- Patologías como badges --}}
                    <div class="mb-3">
                        @if($historia->cardiopatias)
                        <span class="patologia-badge"><i class="fas fa-heartbeat"></i> Cardiopatías</span>
                        @endif
                        @if($historia->diabetes)
                        <span class="patologia-badge"><i class="fas fa-syringe"></i> Diabetes</span>
                        @endif
                        @if($historia->hipertension)
                        <span class="patologia-badge"><i class="fas fa-tachometer-alt"></i> Hipertensión</span>
                        @endif
                        @if($historia->tuberculosis)
                        <span class="patologia-badge"><i class="fas fa-lungs"></i> Tuberculosis</span>
                        @endif

                        @if(!$historia->cardiopatias && !$historia->diabetes && !$historia->hipertension && !$historia->tuberculosis)
                        <p class="text-muted mb-0"><i class="fas fa-check-circle text-success me-2"></i>Sin patologías registradas</p>
                        @endif
                    </div>

                    @if($historia->alergias)
                    <div class="alert alert-danger border-0 rounded-3">
                        <strong><i class="fas fa-exclamation-triangle me-2"></i>Alergias:</strong> {{ $historia->alergias }}
                    </div>
                    @endif

                    @if($historia->antecedentes_otros)
                    <p><strong>Otros:</strong> {{ $historia->antecedentes_otros }}</p>
                    @endif

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3"><i class="fas fa-users me-2 text-info"></i>Antecedentes Familiares:</h6>
                    <div>
                        @if($historia->fam_diabetes)
                        <span class="patologia-badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"><i class="fas fa-dna"></i> Diabetes</span>
                        @endif
                        @if($historia->fam_hipertension)
                        <span class="patologia-badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"><i class="fas fa-dna"></i> Hipertensión</span>
                        @endif
                        @if($historia->fam_cancer)
                        <span class="patologia-badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"><i class="fas fa-dna"></i> Cáncer</span>
                        @endif
                        @if($historia->fam_tuberculosis)
                        <span class="patologia-badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"><i class="fas fa-dna"></i> Tuberculosis</span>
                        @endif

                        @if(!$historia->fam_diabetes && !$historia->fam_hipertension && !$historia->fam_cancer && !$historia->fam_tuberculosis)
                        <p class="text-muted mb-0"><i class="fas fa-check-circle text-success me-2"></i>Sin antecedentes familiares registrados</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header-custom" style="background: linear-gradient(135deg, #8e44ad 0%);">
                    <i class="fas fa-tooth"></i>
                    <span>EXAMEN ESTOMATOGNÁTICO</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach([
                        ['label' => 'Labios', 'field' => 'labios', 'icon' => 'fa-lips'],
                        ['label' => 'Lengua', 'field' => 'lengua', 'icon' => 'fa-tongue'],
                        ['label' => 'Paladar', 'field' => 'paladar', 'icon' => 'fa-head-side-mask'],
                        ['label' => 'Piso de Boca', 'field' => 'piso_boca', 'icon' => 'fa-tooth'],
                        ['label' => 'Encías', 'field' => 'encias', 'icon' => 'fa-teeth'],
                        ['label' => 'Carrillos', 'field' => 'carrillos', 'icon' => 'fa-smile'],
                        ['label' => 'Orofaringe', 'field' => 'orofaringe', 'icon' => 'fa-throat'],
                        ['label' => 'ATM', 'field' => 'atm', 'icon' => 'fa-jaw-open']
                        ] as $item)
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <i class="fas {{ $item['icon'] }} text-primary fs-4 mb-2"></i>
                                <div class="small text-muted text-uppercase fw-bold mb-1">{{ $item['label'] }}</div>
                                <div class="fw-bold">{{ $historia->{$item['field']} ?? 'No evaluado' }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header-custom card-header-danger d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-diagnoses"></i>
                        <span>DIAGNÓSTICOS</span>
                    </div>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modalNuevoDiagnostico">
                        <i class="fas fa-plus me-1"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    @if($historia->diagnosticos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
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
                    <p class="text-muted text-center mb-0">
                        <i class="fas fa-info-circle me-2"></i>No hay diagnósticos registrados
                    </p>
                    @endif
                </div>
            </div>

            <div class="info-card">
                <div class="card-header-custom card-header-success d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-pills"></i>
                        <span>TRATAMIENTOS Y PROCEDIMIENTOS</span>
                    </div>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modalNuevoTratamiento">
                        <i class="fas fa-plus me-1"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    @if($historia->tratamientos->count() > 0)
                    @foreach($historia->tratamientos as $tratamiento)
                    <div class="treatment-item">
                        <div class="treatment-header">
                            <h6><i class="fas fa-calendar-check me-2 text-primary"></i>Sesión {{ $loop->iteration }} - {{ $tratamiento->fecha->format('d/m/Y') }}</h6>
                            <small><i class="fas fa-user-md me-1"></i>{{ $tratamiento->firma_profesional }}</small>
                        </div>
                        <p class="mb-1"><strong>Procedimiento:</strong> {{ $tratamiento->procedimiento }}</p>
                        @if($tratamiento->prescripcion)
                        <p class="mb-0 text-muted"><strong>Prescripción:</strong> {{ $tratamiento->prescripcion }}</p>
                        @endif
                    </div>
                    @endforeach
                    @else
                    <p class="text-muted text-center mb-0">
                        <i class="fas fa-info-circle me-2"></i>No hay tratamientos registrados
                    </p>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="info-card">
                <div class="card-header-custom card-header-success">
                    <i class="fas fa-stethoscope"></i>
                    <span>CONSTANTES VITALES</span>
                </div>
                <div class="card-body">
                    <div class="vital-stat">
                        <i class="fas fa-thermometer-half text-danger"></i>
                        <div class="value">{{ $historia->temperatura ?? '-' }} °C</div>
                        <div class="label">Temperatura</div>
                    </div>
                    <div class="vital-stat">
                        <i class="fas fa-heartbeat text-danger"></i>
                        <div class="value">{{ $historia->presion_arterial ?? '-' }}</div>
                        <div class="label">Presión Arterial (mmHg)</div>
                    </div>
                    <div class="vital-stat">
                        <i class="fas fa-heart text-danger"></i>
                        <div class="value">{{ $historia->pulso ?? '-' }}</div>
                        <div class="label">Pulso (lpm)</div>
                    </div>
                    <div class="vital-stat">
                        <i class="fas fa-lungs text-info"></i>
                        <div class="value">{{ $historia->frecuencia_respiratoria ?? '-' }}</div>
                        <div class="label">Frecuencia Respiratoria (rpm)</div>
                    </div>
                </div>
            </div>

            @if($historia->tieneOdontograma())
            <div class="info-card">
                <div class="card-header-custom card-header-primary">
                    <i class="fas fa-tooth"></i>
                    <span>RESUMEN ODONTOGRAMA</span>
                </div>
                <div class="card-body">
                    @php
                    $cariados = $historia->odontograma->where('estado', 'caries')->count();
                    $obturados = $historia->odontograma->where('estado', 'obturado')->count();
                    $perdidos = $historia->odontograma->whereIn('estado', ['perdido_caries', 'perdido_otra'])->count();
                    @endphp

                    <div class="odontograma-stat">
                        <span><i class="fas fa-circle text-danger me-2"></i>Cariados</span>
                        <strong class="text-danger">{{ $cariados }}</strong>
                    </div>
                    <div class="odontograma-stat">
                        <span><i class="fas fa-circle text-primary me-2"></i>Obturados</span>
                        <strong class="text-primary">{{ $obturados }}</strong>
                    </div>
                    <div class="odontograma-stat">
                        <span><i class="fas fa-circle text-dark me-2"></i>Perdidos</span>
                        <strong class="text-dark">{{ $perdidos }}</strong>
                    </div>
                    <div class="odontograma-stat">
                        <span class="fw-bold text-primary">TOTAL CPO</span>
                        <strong class="text-primary fs-3">{{ $cariados + $obturados + $perdidos }}</strong>
                    </div>

                    <a href="{{ route('historia_clinica.odontograma', $historia->id) }}" class="btn btn-action btn-gradient-primary w-100 mt-3">
                        <i class="fas fa-tooth me-2"></i> Ver Odontograma Completo
                    </a>
                </div>
            </div>
            @endif

            <div class="info-card">
                <div class="card-header-custom card-header-info">
                    <i class="fas fa-microscope"></i>
                    <span>EXÁMENES</span>
                </div>
                <div class="card-body">
                    @if($historia->examenesComplementarios->count() > 0)
                    @foreach($historia->examenesComplementarios as $examen)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $examen->nombre_examen }}</div>
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i>{{ $examen->fecha_solicitud->format('d/m/Y') }}</small>
                        </div>
                        <span class="badge bg-{{ $examen->estado === 'completado' ? 'success' : 'warning' }}">
                            {{ ucfirst($examen->estado) }}
                        </span>
                    </div>
                    @endforeach
                    @else
                    <p class="text-muted text-center mb-0 small">
                        <i class="fas fa-info-circle me-2"></i>Sin exámenes registrados
                    </p>
                    @endif
                </div>
            </div>

            @if($historia->observaciones)
            <div class="info-card">
                <div class="card-header-custom card-header-dark">
                    <i class="fas fa-comment-medical"></i>
                    <span>OBSERVACIONES</span>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $historia->observaciones }}</p>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

<div class="modal fade" id="modalNuevoDiagnostico" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-diagnoses me-2"></i>Agregar Diagnóstico</h5>
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
                    <button type="submit" class="btn btn-action btn-gradient-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevoTratamiento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-pills me-2"></i>Agregar Tratamiento</h5>
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
                    <button type="submit" class="btn btn-action btn-gradient-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection