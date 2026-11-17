<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactoRequest extends FormRequest
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
            'nombre' => 'required|string|min:3|max:150',
            'correo' => 'required|email|max:150',
            'telefono' => 'nullable|string|max:20',
            'asunto' => 'required|string|min:3|max:150',
            'mensaje' => 'required|string|min:5|max:2000',
        ];
    }

    /**
     * Custom error messages in Spanish.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre completo es obligatorio.',
            'nombre.min' => 'El nombre debe contener al menos 3 caracteres.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico no es válido.',
            'asunto.required' => 'El asunto es obligatorio.',
            'asunto.min' => 'El asunto debe tener al menos 3 caracteres.',
            'mensaje.required' => 'El mensaje es obligatorio.',
            'mensaje.min' => 'El mensaje debe contener al menos 5 caracteres.',
        ];
    }
}
