<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidarCedulaEcuatoriana implements Rule
{
    private $errorMessage = '';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 1. Verificar que sean 10 dígitos numéricos
        if (!preg_match('/^[0-9]{10}$/', $value)) {
            $this->errorMessage = 'La cédula debe tener 10 dígitos numéricos.';
            return false;
        }

        // 2. Verificar código de provincia (01-24, 30)
        $provincia = intval(substr($value, 0, 2));
        if (!($provincia >= 1 && $provincia <= 24) && $provincia != 30) {
            $this->errorMessage = 'El código de provincia (dos primeros dígitos) no es válido.';
            return false;
        }

        // 3. Verificar tercer dígito (debe ser menor a 6 para personas naturales)
        $tercerDigito = intval($value[2]);
        if ($tercerDigito >= 6) {
            $this->errorMessage = 'Esta cédula no corresponde a una persona natural (Tercer dígito inválido).';
            return false;
        }

        // 4. Algoritmo Módulo 10
        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $valor = intval($value[$i]) * $coeficientes[$i];
            if ($valor >= 10) {
                $valor -= 9;
            }
            $suma += $valor;
        }

        $digitoVerificador = intval($value[9]);
        
        // --- CORRECCIÓN MATEMÁTICA ---
        // Usamos el operador módulo (%) para obtener el residuo
        $residuo = $suma % 10;
        
        // Si el residuo es 0, el resultado es 0. Si no, es 10 - residuo.
        $resultado = ($residuo === 0) ? 0 : (10 - $residuo);

        // Ahora comparamos Entero con Entero
        if ($resultado !== $digitoVerificador) {
            $this->errorMessage = 'La cédula ingresada es incorrecta (Dígito verificador inválido).';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}