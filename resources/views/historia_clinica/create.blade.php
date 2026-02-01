@extends('layouts.app')

@section('title', 'Nueva Historia Clínica')

@section('content')
<div class="container py-4">
    
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="mb-0">
                        <i class="fas fa-file-medical text-primary"></i>
                        Nueva Historia Clínica Odontológica
                    </h2>
                    <p class="text-muted mb-0">Formulario basado en SNS-MSP/HCU-FORM.033/2021</p>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>¡Error!</strong> Por favor corrija los siguientes errores:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('historia_clinica.store') }}" method="POST">
        @csrf

        {{-- SECCIÓN A: DATOS DEL PACIENTE --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">A. DATOS DEL PACIENTE</h5>
            </div>
            <div class="card-body">
                <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombres:</strong> {{ $paciente->nombres }}</p>
                        <p><strong>Apellidos:</strong> {{ $paciente->apellidos }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Cédula:</strong> {{ $paciente->cedula }}</p>
                        <p><strong>Edad:</strong> {{ $paciente->fecha_nacimiento->age }} años</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de Atención *</label>
                        <input type="date" name="fecha_atencion" class="form-control" value="{{ old('fecha_atencion', date('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN B: MOTIVO DE CONSULTA --}}
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">B. MOTIVO DE CONSULTA</h5>
            </div>
            <div class="card-body">
                <textarea name="motivo_consulta" class="form-control" rows="3" required placeholder="Describa el motivo principal de la consulta...">{{ old('motivo_consulta') }}</textarea>
            </div>
        </div>

        {{-- SECCIÓN C: ENFERMEDAD ACTUAL --}}
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">C. ENFERMEDAD ACTUAL</h5>
            </div>
            <div class="card-body">
                <textarea name="enfermedad_actual" class="form-control" rows="4" placeholder="Descripción detallada de la enfermedad actual...">{{ old('enfermedad_actual') }}</textarea>
            </div>
        </div>

        {{-- SECCIÓN D: ANTECEDENTES PATOLÓGICOS PERSONALES --}}
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">D. ANTECEDENTES PATOLÓGICOS PERSONALES</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Alergias</label>
                        <textarea name="alergias" class="form-control" rows="2" placeholder="Especifique alergias conocidas...">{{ old('alergias') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Otros Antecedentes</label>
                        <textarea name="antecedentes_otros" class="form-control" rows="2" placeholder="Otros antecedentes relevantes...">{{ old('antecedentes_otros') }}</textarea>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="cardiopatias" id="cardiopatias" value="1" {{ old('cardiopatias') ? 'checked' : '' }}>
                            <label class="form-check-label" for="cardiopatias">Cardiopatías</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="diabetes" id="diabetes" value="1" {{ old('diabetes') ? 'checked' : '' }}>
                            <label class="form-check-label" for="diabetes">Diabetes</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="hipertension" id="hipertension" value="1" {{ old('hipertension') ? 'checked' : '' }}>
                            <label class="form-check-label" for="hipertension">Hipertensión</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="tuberculosis" id="tuberculosis" value="1" {{ old('tuberculosis') ? 'checked' : '' }}>
                            <label class="form-check-label" for="tuberculosis">Tuberculosis</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN E: ANTECEDENTES FAMILIARES --}}
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">E. ANTECEDENTES PATOLÓGICOS FAMILIARES</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="fam_diabetes" id="fam_diabetes" value="1" {{ old('fam_diabetes') ? 'checked' : '' }}>
                            <label class="form-check-label" for="fam_diabetes">Diabetes</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="fam_hipertension" id="fam_hipertension" value="1" {{ old('fam_hipertension') ? 'checked' : '' }}>
                            <label class="form-check-label" for="fam_hipertension">Hipertensión</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="fam_cancer" id="fam_cancer" value="1" {{ old('fam_cancer') ? 'checked' : '' }}>
                            <label class="form-check-label" for="fam_cancer">Cáncer</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="fam_tuberculosis" id="fam_tuberculosis" value="1" {{ old('fam_tuberculosis') ? 'checked' : '' }}>
                            <label class="form-check-label" for="fam_tuberculosis">Tuberculosis</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN F: CONSTANTES VITALES --}}
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">F. CONSTANTES VITALES</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Temperatura (°C)</label>
                        <input type="number" step="0.1" name="temperatura" class="form-control" placeholder="36.5" value="{{ old('temperatura') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Presión Arterial (mmHg)</label>
                        <input type="text" name="presion_arterial" class="form-control" placeholder="120/80" value="{{ old('presion_arterial') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Pulso (lpm)</label>
                        <input type="number" name="pulso" class="form-control" placeholder="70" value="{{ old('pulso') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Frecuencia Respiratoria (rpm)</label>
                        <input type="number" name="frecuencia_respiratoria" class="form-control" placeholder="16" value="{{ old('frecuencia_respiratoria') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN G: EXAMEN DEL SISTEMA ESTOMATOGNÁTICO --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">G. EXAMEN DEL SISTEMA ESTOMATOGNÁTICO</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Labios</label>
                        <input type="text" name="labios" class="form-control" placeholder="Normal" value="{{ old('labios') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Lengua</label>
                        <input type="text" name="lengua" class="form-control" placeholder="Normal" value="{{ old('lengua') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Paladar</label>
                        <input type="text" name="paladar" class="form-control" placeholder="Normal" value="{{ old('paladar') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Piso de Boca</label>
                        <input type="text" name="piso_boca" class="form-control" placeholder="Normal" value="{{ old('piso_boca') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Encías</label>
                        <input type="text" name="encias" class="form-control" placeholder="Normal" value="{{ old('encias') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Carrillos</label>
                        <input type="text" name="carrillos" class="form-control" placeholder="Normal" value="{{ old('carrillos') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Orofaringe</label>
                        <input type="text" name="orofaringe" class="form-control" placeholder="Normal" value="{{ old('orofaringe') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">ATM</label>
                        <input type="text" name="atm" class="form-control" placeholder="Normal" value="{{ old('atm') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- OBSERVACIONES --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">OBSERVACIONES GENERALES</h5>
            </div>
            <div class="card-body">
                <textarea name="observaciones" class="form-control" rows="4" placeholder="Observaciones adicionales...">{{ old('observaciones') }}</textarea>
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between">
                        <a href="{{ route('doctor.historia.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Crear Historia Clínica y Continuar al Odontograma
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    // Auto-guardar en localStorage cada 30 segundos
    setInterval(function() {
        const formData = new FormData(document.querySelector('form'));
        const data = Object.fromEntries(formData.entries());
        localStorage.setItem('historia_clinica_draft', JSON.stringify(data));
        console.log('Borrador guardado automáticamente');
    }, 30000);

    // Recuperar borrador al cargar
    document.addEventListener('DOMContentLoaded', function() {
        const draft = localStorage.getItem('historia_clinica_draft');
        if (draft && confirm('Se encontró un borrador guardado. ¿Desea recuperarlo?')) {
            const data = JSON.parse(draft);
            Object.keys(data).forEach(key => {
                const field = document.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = data[key] === '1';
                    } else {
                        field.value = data[key];
                    }
                }
            });
        }
    });

    // Limpiar borrador al enviar
    document.querySelector('form').addEventListener('submit', function() {
        localStorage.removeItem('historia_clinica_draft');
    });
</script>
@endpush