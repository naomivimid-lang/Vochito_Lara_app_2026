<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerarReporteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'desde' => ['required', 'date'],
            'hasta' => ['required', 'date', 'after_or_equal:desde'],
        ];
    }

    public function messages(): array
    {
        return [
            'desde.required'        => 'Elige la fecha inicial.',
            'desde.date'            => 'La fecha inicial no es válida.',
            'hasta.required'        => 'Elige la fecha final.',
            'hasta.date'            => 'La fecha final no es válida.',
            'hasta.after_or_equal'  => 'La fecha final debe ser igual o posterior a la inicial.',
        ];
    }
}
