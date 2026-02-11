<?php

namespace App\Http\Controllers\Historial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\HistoriaClinica;
use App\Models\Paciente;
use App\Models\Historial_Clinico\Odontograma;
use App\Models\Historial_Clinico\IndicesSaludBucal;
use App\Models\Diagnostico;
use App\Models\Tratamiento;
use Barryvdh\DomPDF\Facade\Pdf;


class HistoriaClinicaController extends Controller
{
    /**
     * 1. Listado principal de Historias Clínicas
     */
    public function index()
    {
        $historias = HistoriaClinica::with(['paciente', 'profesional'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('historia_clinica.index', compact('historias'));
    }

    /**
     * 2. Formulario para crear nueva HC (Parte 1: Texto)
     */
    public function create(Request $request)
    {
        // Si venimos de la lista de pacientes, traemos el ID
        $paciente_id = $request->query('paciente_id');

        if (!$paciente_id) {
            // Si no hay paciente, redirigir o mostrar error (o lista de selección)
            return redirect()->back()->with('error', 'Debe seleccionar un paciente primero.');
        }

        $paciente = Paciente::findOrFail($paciente_id);

        return view('historia_clinica.create', compact('paciente'));
    }

    /**
     * 3. Guardar la HC inicial y crear el esqueleto del Odontograma
     */
    public function store(Request $request)
    {
        // Validaciones básicas del formulario de texto
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha_atencion' => 'required|date',
            'motivo_consulta' => 'required|string',
            'enfermedad_actual' => 'nullable|string',
            // ... Agrega aquí validaciones para antecedentes si es necesario
        ]);

        DB::beginTransaction(); // Usamos transacción para asegurar que se cree TODO o nada

        try {
            // A. Generar número de historia correlativo (Ej: HC-001-005)
            $count = HistoriaClinica::where('paciente_id', $request->paciente_id)->count();
            $numeroHistoria = 'HC-' . str_pad($request->paciente_id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            // B. Guardar cabecera de Historia
            $historia = new HistoriaClinica();
            $historia->fill($request->all()); // Llena campos como temperatura, presion, antecedentes, etc.
            $historia->numero_historia = $numeroHistoria;
            $historia->estado_historia = 'abierta';
            $historia->profesional_id = Auth::id(); // Usuario logueado
            $historia->save();

            // C. Inicializar Índices de Salud Bucal (vacíos)
            IndicesSaludBucal::create([
                'historia_id' => $historia->id,
                'profesional_id' => Auth::id()
            ]);

            // D. Inicializar ODONTOGRAMA (Crear las 52 piezas dentales en estado "sano")
            $this->inicializarDientes($historia->id);

            DB::commit();

            // Redirigir a la vista de edición del Odontograma (Paso 2)
            return redirect()->route('historia_clinica.odontograma', $historia->id)
                ->with('success', 'Historia creada. Ahora actualice el odontograma.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear historia: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * 4. Vista visual completa (Resumen)
     */
    public function show($id)
    {
        $historia = HistoriaClinica::with([
            'paciente',
            'odontograma',
            'indicesSaludBucal',
            'diagnosticos',
            'tratamientos',
            'profesional'
        ])->findOrFail($id);

        return view('historia_clinica.show', compact('historia'));
    }

    /**
     * 5. Vista interactiva del Odontograma
     */
    public function editarOdontograma($id)
    {
        // Cargamos la historia y sus relaciones necesarias para pintar el diagrama
        $historia = HistoriaClinica::with(['paciente', 'indicesSaludBucal'])->findOrFail($id);

        return view('historia_clinica.odontograma', compact('historia'));
    }

    /**
     * 6. GUARDAR ODONTOGRAMA (Recibe JSON desde Javascript)
     */
    public function guardarOdontograma(Request $request, $id)
    {
        // Validar que recibimos un JSON válido
        $request->validate([
            'odontograma' => 'required|array',
            'indices' => 'nullable|array'
        ]);

        DB::beginTransaction();

        try {
            $historia = HistoriaClinica::findOrFail($id);

            // A. Actualizar cada diente modificado
            // El JS envía un array con todas las piezas o solo las modificadas
            foreach ($request->odontograma as $dienteData) {
                // Buscamos el diente por historia y numero_pieza
                Odontograma::updateOrCreate(
                    [
                        'historia_id' => $historia->id,
                        'numero_pieza' => $dienteData['numero_pieza']
                    ],
                    [
                        'estado' => $dienteData['estado'],
                        'necesita_sellante' => $dienteData['necesita_sellante'] ?? false,
                        'movilidad' => $dienteData['movilidad'] ?? null,
                        'recesion' => $dienteData['recesion'] ?? null,
                        'observaciones' => $dienteData['observaciones'] ?? null,
                        'profesional_id' => Auth::id()
                    ]
                );
            }

            // B. Actualizar Índices CPO/ceo/Higiene
            if ($request->has('indices')) {
                $indices = IndicesSaludBucal::firstOrNew(['historia_id' => $historia->id]);
                $indices->fill($request->indices);
                $indices->profesional_id = Auth::id();
                $indices->save();
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Odontograma guardado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 7. API para obtener los datos actuales del odontograma (Para pintar el JS al cargar)
     */
    public function obtenerOdontogramaJSON($id)
    {
        $dientes = Odontograma::where('historia_id', $id)->get();
        return response()->json($dientes);
    }

    /**
     * 8. Agregar Diagnóstico (Modal)
     */
    public function agregarDiagnostico(Request $request, $id)
    {
        $request->validate([
            'tipo' => 'required',
            'descripcion' => 'required'
        ]);

        $historia = HistoriaClinica::findOrFail($id);

        $historia->diagnosticos()->create([
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha_diagnostico' => now()
        ]);

        return back()->with('success', 'Diagnóstico agregado.');
    }

    /**
     * 9. Agregar Tratamiento (Modal)
     */
    public function agregarTratamiento(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'procedimiento' => 'required',
        ]);

        $historia = HistoriaClinica::findOrFail($id);

        $historia->tratamientos()->create([
            'fecha' => $request->fecha,
            'procedimiento' => $request->procedimiento,
            'prescripcion' => $request->prescripcion,
            'firma_profesional' => Auth::user()->nombre ?? 'Doctor'
        ]);

        return back()->with('success', 'Tratamiento registrado.');
    }

    /**
     * 10. Generar PDF (Placeholder)
     */
    public function generarPDF($id)
    {
        $historia = HistoriaClinica::with([
            'paciente',
            'profesional',
            'odontograma',
            'diagnosticos',
            'tratamientos',
            'indicesSaludBucal',
            'examenesComplementarios'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('historia_clinica.pdf', compact('historia'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('historia_clinica_' . $historia->numero_historia . '.pdf');
    }

    // ==========================================
    // MÉTODOS PRIVADOS (Helpers)
    // ==========================================

    /**
     * Crea las filas en la base de datos para los dientes (18-11, 21-28, etc.)
     */
    private function inicializarDientes($historiaId)
    {
        // Array completo de piezas dentales (Adulto + Niño)
        $piezas = [
            // Permanentes
            18,
            17,
            16,
            15,
            14,
            13,
            12,
            11,
            21,
            22,
            23,
            24,
            25,
            26,
            27,
            28,
            31,
            32,
            33,
            34,
            35,
            36,
            37,
            38,
            41,
            42,
            43,
            44,
            45,
            46,
            47,
            48,
            // Temporales
            55,
            54,
            53,
            52,
            51,
            61,
            62,
            63,
            64,
            65,
            71,
            72,
            73,
            74,
            75,
            81,
            82,
            83,
            84,
            85
        ];

        $insertData = [];
        $now = now();
        $profesional = Auth::id();

        foreach ($piezas as $pieza) {
            $insertData[] = [
                'historia_id' => $historiaId,
                'numero_pieza' => $pieza,
                'tipo_denticion' => ($pieza >= 50) ? 'temporal' : 'permanente',
                'estado' => 'sano',
                'profesional_id' => $profesional,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        // Insert masivo para optimizar rendimiento
        Odontograma::insert($insertData);
    }
}
