<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente'     => 'required|string|min:3|max:120',
            'producto_id' => 'required|integer|exists:productos,id',
            'cantidad'    => 'required|integer|min:1|max:1000',
            'entrega'     => 'required|date|after:now',
        ];
    }

    public function messages(): array
    {
        return [
            'cliente.required'     => 'El nombre del cliente es obligatorio.',
            'cliente.min'          => 'El nombre del cliente debe tener al menos 3 caracteres.',
            'cliente.max'          => 'El nombre del cliente no puede pasar de 120 caracteres.',
            'producto_id.required' => 'Selecciona un producto.',
            'producto_id.exists'   => 'El producto seleccionado no existe.',
            'cantidad.required'    => 'La cantidad es obligatoria.',
            'cantidad.integer'     => 'La cantidad debe ser un número entero.',
            'cantidad.min'         => 'La cantidad debe ser mayor a 0.',
            'cantidad.max'         => 'La cantidad no puede pasar de 1000.',
            'entrega.required'     => 'La fecha de entrega es obligatoria.',
            'entrega.date'         => 'La fecha de entrega no es válida.',
            'entrega.after'        => 'La fecha de entrega debe ser futura.',
        ];
    }
}
