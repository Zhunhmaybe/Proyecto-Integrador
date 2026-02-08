<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidarCedulaEcuatoriana implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $resultado = DB::select('SELECT validar_cedula_ec(?) as es_valida', [$value]);
        return $resultado[0]->es_valida;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La cédula ingresada no es válida o no existe.';
    }
}