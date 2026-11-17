<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePedidoRequest extends FormRequest
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
            'cliente' => 'required|string|min:3|max:150',
            'producto' => 'required|string|min:2|max:150',
            'cantidad' => 'required|integer|min:1|max:1000000',
            'entrega' => 'required|date|after:now',
            'po' => 'nullable|string|max:50|unique:pedidos,po',
        ];
    }

    /**
     * Custom error messages in Spanish.
     */
    public function messages(): array
    {
        return [
            'cliente.required' => 'El nombre del cliente es obligatorio.',
            'cliente.min' => 'El nombre del cliente debe tener al menos 3 caracteres.',
            'producto.required' => 'El producto es obligatorio.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser mayor a 0.',
            'entrega.required' => 'La fecha estimada de entrega es obligatoria.',
            'entrega.after' => 'La fecha estimada de entrega debe ser posterior a la fecha y hora actual.',
            'po.unique' => 'Ya existe una orden de compra registrada con ese número de PO.',
        ];
    }
}
