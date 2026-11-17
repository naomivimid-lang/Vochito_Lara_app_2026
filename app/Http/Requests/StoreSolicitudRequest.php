<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudRequest extends FormRequest
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
            'tipo' => 'required|in:cancelacion,modificacion',
            'po' => 'required|string|regex:/^[Pp][Oo]-[0-9]{4}-[0-9]{4}$/i',
            'email' => 'required|email|max:150',
            'motivo' => 'nullable|string|max:150',
            'comentarios' => 'required|string|max:1000',
        ];
    }

    /**
     * Custom error messages in Spanish.
     */
    public function messages(): array
    {
        return [
            'tipo.required' => 'El tipo de solicitud es obligatorio.',
            'tipo.in' => 'El tipo de solicitud debe ser cancelación o modificación.',
            'po.required' => 'La orden de compra (PO) es obligatoria.',
            'po.regex' => 'La orden de compra debe tener el formato PO-2026-0001.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico ingresado no es válido.',
            'comentarios.required' => 'Los comentarios o cambios solicitados son obligatorios.',
        ];
    }
}
