@extends('layouts.app')

@section('title', 'Historial Clínico')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/historia_clinica/index.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-primary">
                <i class="fas fa-notes-medical"></i> Gestión de Historias Clínicas
            </h3>
            <p class="text-muted mb-0">Listado general de atenciones y expedientes.</p>
        </div>

        <a href="{{ route('doctor.pacientes.index') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Historia (Desde Pacientes)
        </a>
    </div>

    {{-- Tarjeta Principal --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Buscador y Filtros (Visual) --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Buscar por paciente, cédula o número de historia...">
                    </div>
                </div>
            </div>

            {{-- Tabla de Historias --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">N° Historia</th>
                            <th scope="col">Paciente</th>
                            <th scope="col">Fecha Atención</th>
                            <th scope="col">Motivo</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historias as $historia)
                        <tr>
                            {{-- N° Historia --}}
                            <td>
                                <span class="fw-bold text-dark">#{{ $historia->numero_historia }}</span>
                            </td>

                            {{-- Paciente --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2 bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 35px; height: 35px; font-size: 14px;">
                                        {{ strtoupper(substr($historia->paciente->nombres, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $historia->paciente->nombres }} {{ $historia->paciente->apellidos }}</div>
                                        <small class="text-muted">{{ $historia->paciente->cedula }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Fecha --}}
                            <td>
                                <i class="far fa-calendar-alt text-muted me-1"></i>
                                {{ $historia->fecha_atencion->format('d/m/Y') }}
                            </td>

                            {{-- Motivo (Truncado) --}}
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $historia->motivo_consulta }}">
                                    {{ Str::limit($historia->motivo_consulta, 30) }}
                                </span>
                            </td>

                            {{-- Estado --}}
                            <td>
                                @if($historia->estado_historia === 'abierta')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                    Abierta
                                </span>
                                @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">
                                    Cerrada
                                </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    {{-- Ver Resumen --}}
                                    <a href="{{ route('historia_clinica.show', $historia->id) }}" class="btn btn-sm btn-outline-primary" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Editar Odontograma --}}
                                    <a href="{{ route('historia_clinica.odontograma', $historia->id) }}" class="btn btn-sm btn-outline-warning" title="Editar Odontograma">
                                        <i class="fas fa-tooth"></i>
                                    </a>

                                    {{-- PDF --}}
                                    <a href="{{ route('historia_clinica.pdf', $historia->id) }}" class="btn btn-sm btn-outline-danger" title="Generar PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                    <p class="mb-0">No se encontraron historias clínicas registradas.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $historias->links() }}
            </div>

        </div>
    </div>
</div>
@endsection