<?php

namespace App\Models\historial_Clinico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicesSaludBucal extends Model
{
    use HasFactory;
    
    protected $table = 'indices_salud_bucal';

    protected $fillable = [
        'historia_id', 'cpo_cariados', 'cpo_perdidos', 'cpo_obturados',
        'ceo_cariados', 'ceo_extraccion', 'ceo_obturados',
        'placa_bacteriana', 'calculo_dental', 'gingivitis',
        'nivel_fluorosis', 'tipo_oclusion', 'profesional_id'
    ];
    
    public function calcularIndices() {
        // Lógica opcional para recalcular totales antes de guardar
    }
    
}