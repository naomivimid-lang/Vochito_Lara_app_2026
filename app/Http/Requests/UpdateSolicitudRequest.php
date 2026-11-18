<?php

namespace App\Http\Requests;

use App\Models\Solicitud;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSolicitudRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estado'          => ['required', Rule::in(Solicitud::ESTADOS)],
            'respuesta_admin' => ['nullable', 'string', 'max:500'],
            'motivo'          => ['nullable', 'string', 'max:150'],
            'comentarios'     => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'estado.required' => 'Selecciona un estado válido.',
            'estado.in'       => 'El estado debe ser Pendiente, Aprobada o Rechazada.',
            'respuesta_admin.max' => 'La respuesta administrativa no puede superar los 500 caracteres.',
        ];
    }
}
