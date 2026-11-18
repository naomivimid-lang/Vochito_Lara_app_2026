<?php

namespace App\Http\Requests;

use App\Models\Solicitud;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSolicitudRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo'        => ['required', Rule::in(Solicitud::TIPOS)],
            'po'          => ['required', 'string', 'regex:/^[Pp][Oo]-([0-9]{4}-)?[0-9]{4}$/i'],
            'email'       => ['required', 'email', 'max:150'],
            'motivo'      => ['nullable', 'string', 'max:150'],
            'comentarios' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.required'        => 'El tipo de solicitud es obligatorio.',
            'tipo.in'              => 'El tipo de solicitud debe ser cancelación o modificación.',
            'po.required'          => 'La orden de compra (PO) es obligatoria.',
            'po.regex'             => 'La orden de compra debe tener el formato PO-2026-0001 o PO-0001.',
            'email.required'       => 'El correo electrónico es obligatorio.',
            'email.email'          => 'El correo electrónico ingresado no es válido.',
            'comentarios.required' => 'Los comentarios o cambios solicitados son obligatorios.',
            'comentarios.max'      => 'Los comentarios no pueden superar los 1000 caracteres.',
        ];
    }
}
