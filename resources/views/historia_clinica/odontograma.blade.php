@extends('layouts.app')

@section('title', 'Odontograma Interactivo')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/odontograma.css') }}">
<style>
    .odontograma-wrapper {
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    /* Estilos del Diente */
    .diente-container {
        display: inline-block;
        margin: 2px;
        text-align: center;
        position: relative;
    }
    .diente-svg {
        cursor: pointer;
        transition: transform 0.2s;
        filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.1));
    }
    .diente-svg:hover {
        transform: scale(1.1);
        z-index: 10;
    }
    .numero-pieza {
        font-size: 11px;
        font-weight: bold;
        color: #555;
        margin-bottom: 2px;
        display: block;
    }
    /* Caras del diente (hover) */
    .diente-svg polygon:hover, .diente-svg rect:hover, .diente-svg path:hover, .diente-svg circle:hover {
        opacity: 0.7;
    }
    
    /* Input Caras en el Modal (Visualmente botones) */
    .cara-checkbox:checked + .btn-cara {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    .btn-cara {
        border: 1px solid #ced4da;
        color: #495057;
        font-weight: 500;
        transition: all 0.2s;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" data-historia-id="{{ $historia->id }}">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0"><i class="fas fa-tooth"></i> Odontograma Digital</h3>
            <p class="text-muted mb-0">Paciente: <strong>{{ $historia->paciente->nombres }} {{ $historia->paciente->apellidos }}</strong></p>
        </div>
        <div>
            <a href="{{ route('historia_clinica.index', $historia->id) }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button type="button" class="btn btn-success" onclick="guardarOdontograma()">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </div>
    </div>

    <div class="row">
        {{-- LISTA DE TRATAMIENTOS APLICADOS (Izquierda) --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-list"></i> Tratamientos Registrados</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="lista-tratamientos" style="max-height: 600px; overflow-y: auto;">
                        <div class="text-center p-4 text-muted">
                            <i class="fas fa-info-circle mb-2"></i><br>
                            Haga clic en un diente para agregar un diagnóstico o tratamiento.
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <button class="btn btn-outline-danger btn-sm w-100" onclick="resetearOdontograma()">
                        <i class="fas fa-trash"></i> Limpiar Todo
                    </button>
                </div>
            </div>
        </div>

        {{-- ODONTOGRAMA VISUAL (Derecha) --}}
        <div class="col-md-9">
            <div class="odontograma-wrapper text-center">
                
                {{-- LEYENDA RÁPIDA --}}
                <div class="d-flex justify-content-center gap-3 mb-4 text-muted small">
                    <span class="d-flex align-items-center"><span style="width:12px;height:12px;background:red;margin-right:5px;border-radius:2px;"></span> Patología (Caries)</span>
                    <span class="d-flex align-items-center"><span style="width:12px;height:12px;background:blue;margin-right:5px;border-radius:2px;"></span> Restaurado/Bueno</span>
                    <span class="d-flex align-items-center"><span style="width:12px;height:12px;background:gold;margin-right:5px;border-radius:2px;"></span> En Proceso</span>
                    <span class="d-flex align-items-center"><span style="width:12px;height:12px;background:black;margin-right:5px;border-radius:2px;"></span> Perdido/Ausente</span>
                </div>

                {{-- ================= ADULTOS (11-48) ================= --}}
                {{-- SUPERIOR --}}
                <div class="mb-4">
                    {{-- Derecha (18-11) --}}
                    <span class="me-3"></span>
                    @foreach([18,17,16,15,14,13,12,11] as $pieza)
                        @include('historia_clinica.partials.diente_adulto', ['pieza' => $pieza])
                    @endforeach
                    <span class="mx-3 border-end"></span>
                    {{-- Izquierda (21-28) --}}
                    @foreach([21,22,23,24,25,26,27,28] as $pieza)
                        @include('historia_clinica.partials.diente_adulto', ['pieza' => $pieza])
                    @endforeach
                </div>

                {{-- ================= NIÑOS (51-85) ================= --}}
                <div class="mb-4 bg-light p-3 rounded d-inline-block">
                    {{-- Superior (55-65) --}}
                    <div class="mb-2">
                        @foreach([55,54,53,52,51] as $pieza)
                            @include('historia_clinica.partials.diente_nino', ['pieza' => $pieza])
                        @endforeach
                        <span class="mx-3 border-end"></span>
                        @foreach([61,62,63,64,65] as $pieza)
                            @include('historia_clinica.partials.diente_nino', ['pieza' => $pieza])
                        @endforeach
                    </div>
                    {{-- Inferior (85-75) --}}
                    <div>
                        @foreach([85,84,83,82,81] as $pieza)
                            @include('historia_clinica.partials.diente_nino', ['pieza' => $pieza])
                        @endforeach
                        <span class="mx-3 border-end"></span>
                        @foreach([71,72,73,74,75] as $pieza)
                            @include('historia_clinica.partials.diente_nino', ['pieza' => $pieza])
                        @endforeach
                    </div>
                </div>

                {{-- ================= ADULTOS INFERIOR (48-31) ================= --}}
                <div class="mt-4">
                    {{-- Derecha (48-41) --}}
                    <span class="me-3"></span>
                    @foreach([48,47,46,45,44,43,42,41] as $pieza)
                        @include('historia_clinica.partials.diente_adulto', ['pieza' => $pieza])
                    @endforeach
                    <span class="mx-3 border-end"></span>
                    {{-- Izquierda (31-38) --}}
                    @foreach([31,32,33,34,35,36,37,38] as $pieza)
                        @include('historia_clinica.partials.diente_adulto', ['pieza' => $pieza])
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

{{-- MODAL DE TRATAMIENTO (El que pediste en la foto) --}}
<div class="modal fade" id="modalTratamiento" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-tooth me-2"></i> Diente <span id="lbl-diente-seleccionado"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-tratamiento">
                    
                    {{-- 1. Categoría --}}
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">CATEGORÍA</label>
                        <select class="form-select" id="select-categoria" onchange="cargarTratamientos()">
                            <option value="">Seleccione...</option>
                            </select>
                    </div>

                    {{-- 2. Tratamiento --}}
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">TRATAMIENTO / DIAGNÓSTICO</label>
                        <select class="form-select" id="select-tratamiento" disabled>
                            <option value="">Seleccione categoría primero...</option>
                        </select>
                    </div>

                    {{-- 3. Estado (Rojo/Azul) --}}
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">ESTADO</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="estado_tratamiento" id="estado_malo" value="malo" checked>
                            <label class="btn btn-outline-danger" for="estado_malo">Por Realizar / Patología (Rojo)</label>

                            <input type="radio" class="btn-check" name="estado_tratamiento" id="estado_bueno" value="bueno">
                            <label class="btn btn-outline-primary" for="estado_bueno">Realizado / Buen Estado (Azul)</label>
                        </div>
                    </div>

                    {{-- 4. Caras Afectadas --}}
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">CARAS AFECTADAS</label>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="flex-fill">
                                <input type="checkbox" class="btn-check cara-checkbox" id="chk-vestibular" value="vestibular">
                                <label class="btn btn-cara w-100 btn-sm" for="chk-vestibular">V</label>
                            </div>
                            <div class="flex-fill">
                                <input type="checkbox" class="btn-check cara-checkbox" id="chk-distal" value="distal">
                                <label class="btn btn-cara w-100 btn-sm" for="chk-distal">D</label>
                            </div>
                            <div class="flex-fill">
                                <input type="checkbox" class="btn-check cara-checkbox" id="chk-mesial" value="mesial">
                                <label class="btn btn-cara w-100 btn-sm" for="chk-mesial">M</label>
                            </div>
                            <div class="flex-fill">
                                <input type="checkbox" class="btn-check cara-checkbox" id="chk-palatina" value="palatina">
                                <label class="btn btn-cara w-100 btn-sm" for="chk-palatina">P/L</label>
                            </div>
                            <div class="flex-fill">
                                <input type="checkbox" class="btn-check cara-checkbox" id="chk-oclusal" value="oclusal">
                                <label class="btn btn-cara w-100 btn-sm" for="chk-oclusal">O</label>
                            </div>
                        </div>
                        <div class="form-text small mt-2">* V: Vestibular, D: Distal, M: Mesial, P/L: Palatino/Lingual, O: Oclusal/Incisal</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">OBSERVACIONES</label>
                        <textarea class="form-control" id="txt-observacion" rows="2"></textarea>
                    </div>

                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary px-4" onclick="aplicarTratamiento()">Guardar</button>
            </div>
        </div>
    </div>
</div>

{{-- Token para AJAX --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@push('scripts')
{{-- JS con la lógica nueva --}}
<script src="{{ asset('assets/js/odontograma.js') }}"></script>
@endpush