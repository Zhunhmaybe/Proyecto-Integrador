<?php

namespace App\Models\historial_Clinico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HistoriaClinica;

class Odontograma extends Model
{
    use HasFactory;

    protected $fillable = [
        'historia_id',
        'numero_pieza',
        'tipo_denticion',
        'estado',
        'necesita_sellante',
        'movilidad',
        'recesion', 
        'observaciones',
        'profesional_id'
    ];

    public function historia()
    {
        return $this->belongsTo(HistoriaClinica::class, 'historia_id');
    }
}
