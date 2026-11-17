<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncidenciaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'idPedido' => 'required|string|regex:/^[Pp][Oo]-[0-9]{4}-[0-9]{4}$/i',
            'tipoIncidencia' => 'required|string|max:100',
            'causa' => 'required|string|min:5|max:1000',
            'nuevaFecha' => 'required|date',
        ];
    }

    /**
     * Custom error messages in Spanish.
     */
    public function messages(): array
    {
        return [
            'idPedido.required' => 'La orden de compra (PO) es obligatoria.',
            'idPedido.regex' => 'La orden de compra debe tener el formato PO-2026-0001.',
            'tipoIncidencia.required' => 'El tipo de incidencia es obligatorio.',
            'causa.required' => 'La causa de la incidencia es obligatoria.',
            'causa.min' => 'La causa debe tener al menos 5 caracteres.',
            'nuevaFecha.required' => 'La nueva fecha estimada de entrega es obligatoria.',
        ];
    }
}
