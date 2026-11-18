<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'      => 'required|string|max:120|unique:productos,nombre',
            'descripcion' => 'nullable|string|max:1000',
            'precio'      => 'required|numeric|min:0|max:99999999.99',
            'stock'       => 'required|integer|min:0',
            'activo'      => 'boolean',
        ];
    }

    // Un checkbox sin marcar no se envía; así llega como false
    protected function prepareForValidation(): void
    {
        $this->merge(['activo' => $this->boolean('activo')]);
    }

    public function messages(): array
    {
        return [
            'nombre.required'  => 'El nombre del producto es obligatorio.',
            'nombre.max'       => 'El nombre no puede pasar de 120 caracteres.',
            'nombre.unique'    => 'Ya existe un producto con ese nombre.',
            'descripcion.max'  => 'La descripción no puede pasar de 1000 caracteres.',
            'precio.required'  => 'El precio es obligatorio.',
            'precio.numeric'   => 'El precio debe ser un número.',
            'precio.min'       => 'El precio no puede ser negativo.',
            'precio.max'       => 'El precio es demasiado alto.',
            'stock.required'   => 'El stock es obligatorio.',
            'stock.integer'    => 'El stock debe ser un número entero.',
            'stock.min'        => 'El stock no puede ser negativo.',
        ];
    }
}
