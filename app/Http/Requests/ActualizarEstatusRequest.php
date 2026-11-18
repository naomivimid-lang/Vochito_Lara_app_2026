<?php

namespace App\Http\Requests;

use App\Models\Pedido;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActualizarEstatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estatus' => ['required', Rule::in(Pedido::ESTATUS)],
        ];
    }

    public function messages(): array
    {
        return [
            'estatus.required' => 'Selecciona un estatus.',
            'estatus.in'       => 'Selecciona un estatus válido.',
        ];
    }
}
