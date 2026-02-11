<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historia Clínica Odontológica</title>

    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 30px;
        }

        h1,
        h2,
        h3 {
            margin: 0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .header h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            background: #f0f0f0;
            padding: 6px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            border: 1px solid #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }

        .no-border td {
            border: none;
            padding: 3px 0;
        }

        ul {
            margin: 5px 0 0 15px;
        }

        .firma {
            margin-top: 40px;
            text-align: center;
        }

        .firma-linea {
            margin-top: 50px;
            border-top: 1px solid #000;
            width: 300px;
            margin-left: auto;
            margin-right: auto;
        }

        .text-center {
            text-align: center;
        }

        .small {
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>HISTORIA CLÍNICA ODONTOLÓGICA</h2>
        <div class="small">
            Nº {{ $historia->numero_historia }} |
            Fecha: {{ $historia->fecha_atencion->format('d/m/Y') }}
        </div>
    </div>

    {{-- DATOS DEL PACIENTE --}}
    <div class="section">
        <div class="section-title">Datos del Paciente</div>
        <table class="no-border">
            <tr>
                <td width="50%"><strong>Paciente:</strong> {{ $historia->paciente->nombres }}
                    {{ $historia->paciente->apellidos }}</td>
                <td width="50%"><strong>Cédula:</strong> {{ $historia->paciente->cedula }}</td>
            </tr>
            <tr>
                <td><strong>Edad:</strong> {{ $historia->paciente->fecha_nacimiento->age }} años</td>
                <td><strong>Teléfono:</strong> {{ $historia->paciente->telefono }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Email:</strong> {{ $historia->paciente->email ?? 'No registrado' }}</td>
            </tr>
        </table>
    </div>

    {{-- PROFESIONAL --}}
    <div class="section">
        <div class="section-title">Profesional Responsable</div>
        <p>Dr. {{ $historia->profesional->nombre ?? 'No asignado' }}</p>
    </div>

    {{-- MOTIVO --}}
    <div class="section">
        <div class="section-title">Motivo de Consulta</div>
        <p>{{ $historia->motivo_consulta }}</p>
    </div>

    @if ($historia->enfermedad_actual)
        <div class="section">
            <div class="section-title">Enfermedad Actual</div>
            <p>{{ $historia->enfermedad_actual }}</p>
        </div>
    @endif

    {{-- ANTECEDENTES --}}
    <div class="section">
        <div class="section-title">Antecedentes Médicos</div>
        <ul>
            @if ($historia->cardiopatias)
                <li>Cardiopatías</li>
            @endif
            @if ($historia->diabetes)
                <li>Diabetes</li>
            @endif
            @if ($historia->hipertension)
                <li>Hipertensión</li>
            @endif
            @if ($historia->tuberculosis)
                <li>Tuberculosis</li>
            @endif

            @if (!$historia->cardiopatias && !$historia->diabetes && !$historia->hipertension && !$historia->tuberculosis)
                <li>Sin antecedentes personales registrados</li>
            @endif
        </ul>

        @if ($historia->alergias)
            <p><strong>Alergias:</strong> {{ $historia->alergias }}</p>
        @endif

        @if ($historia->antecedentes_otros)
            <p><strong>Otros:</strong> {{ $historia->antecedentes_otros }}</p>
        @endif
    </div>

    {{-- Examen --}}
    <div class="section">
        <div class="section-title">Examen Estomatognático</div>

        <table>
            <tr>
                <th>Labios</th>
                <th>Lengua</th>
                <th>Paladar</th>
                <th>Piso de Boca</th>
            </tr>
            <tr>
                <td>{{ $historia->labios ?? 'No evaluado' }}</td>
                <td>{{ $historia->lengua ?? 'No evaluado' }}</td>
                <td>{{ $historia->paladar ?? 'No evaluado' }}</td>
                <td>{{ $historia->piso_boca ?? 'No evaluado' }}</td>
            </tr>
            <tr>
                <th>Encías</th>
                <th>Carrillos</th>
                <th>Orofaringe</th>
                <th>ATM</th>
            </tr>
            <tr>
                <td>{{ $historia->encias ?? 'No evaluado' }}</td>
                <td>{{ $historia->carrillos ?? 'No evaluado' }}</td>
                <td>{{ $historia->orofaringe ?? 'No evaluado' }}</td>
                <td>{{ $historia->atm ?? 'No evaluado' }}</td>
            </tr>
        </table>
    </div>

    {{-- DIAGNOSTICOS --}}
    <div class="section">
        <div class="section-title">Diagnósticos</div>
        <table>
            <thead>
                <tr>
                    <th width="20%">Tipo</th>
                    <th width="55%">Descripción</th>
                    <th width="25%">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historia->diagnosticos as $d)
                    <tr>
                        <td>{{ ucfirst($d->tipo) }}</td>
                        <td>{{ $d->descripcion }}</td>
                        <td>{{ $d->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No hay diagnósticos registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Constantes Vitales --}}

    <div class="section">

        <div class="section-title">Constantes Vitales</div>
        <table>
            <tr>
                <th>Temperatura</th>
                <th>Presión Arterial</th>
                <th>Pulso</th>
                <th>Frecuencia Respiratoria</th>
            </tr>
            <tr>
                <td>{{ $historia->temperatura ?? '-' }} °C</td>
                <td>{{ $historia->presion_arterial ?? '-' }} mmHg</td>
                <td>{{ $historia->pulso ?? '-' }} lpm</td>
                <td>{{ $historia->frecuencia_respiratoria ?? '-' }} rpm</td>
            </tr>
        </table>
    </div>

    {{-- TRATAMIENTOS --}}
    <div class="section">
        <div class="section-title">Tratamientos y Procedimientos</div>
        <table>
            <thead>
                <tr>
                    <th width="20%">Fecha</th>
                    <th width="50%">Procedimiento</th>
                    <th width="30%">Profesional</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historia->tratamientos as $t)
                    <tr>
                        <td>{{ $t->fecha->format('d/m/Y') }}</td>
                        <td>{{ $t->procedimiento }}</td>
                        <td>{{ $t->firma_profesional }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No hay tratamientos registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($historia->observaciones)
        <div class="section">
            <div class="section-title">Observaciones</div>
            <p>{{ $historia->observaciones }}</p>
        </div>
    @endif

    <div class="firma">
        <div class="firma-linea"></div>
        <p>Firma Profesional</p>
    </div>

</body>

</html>
