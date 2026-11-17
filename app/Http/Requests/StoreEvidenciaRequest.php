<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvidenciaRequest extends FormRequest
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
            'idPedidoEvidencia' => 'required|string|regex:/^[Pp][Oo]-[0-9]{4}-[0-9]{4}$/i',
            'recibe' => 'required|string|min:3|max:150',
            'fechaEntrega' => 'required|date',
            'fotoEntrega' => 'nullable|file|image|max:10240', // hasta 10MB
            'comentarios' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Custom error messages in Spanish.
     */
    public function messages(): array
    {
        return [
            'idPedidoEvidencia.required' => 'La orden de compra (PO) es obligatoria.',
            'idPedidoEvidencia.regex' => 'La orden de compra debe tener el formato PO-2026-0001.',
            'recibe.required' => 'El nombre de quien recibe es obligatorio.',
            'recibe.min' => 'El nombre debe tener al menos 3 caracteres.',
            'fechaEntrega.required' => 'La fecha y hora de entrega son obligatorias.',
            'fotoEntrega.image' => 'El archivo adjunto debe ser una imagen válida (jpeg, png, etc.).',
        ];
    }
}
