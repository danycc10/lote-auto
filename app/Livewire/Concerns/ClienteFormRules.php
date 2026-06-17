<?php

namespace App\Livewire\Concerns;

use Illuminate\Validation\Rule;

trait ClienteFormRules
{
    /**
     * Reglas de validación compartidas entre crear y editar cliente.
     * $clienteId permite ignorar al propio registro en las reglas unique.
     */
    protected function reglasCliente(?int $clienteId = null): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'nullable|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:30',
            'correo' => ['nullable', 'email', 'max:255', Rule::unique('clientes', 'correo')->ignore($clienteId)],
            'curp' => ['nullable', 'string', 'regex:/^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/i', Rule::unique('clientes', 'curp')->ignore($clienteId)],
            'rfc' => ['nullable', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/iu'],
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:10',
            'ocupacion' => 'nullable|string|max:255',
            'ingreso_mensual' => 'nullable|numeric|min:0',
            'activo' => 'boolean',

            'ine' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'comprobante_domicilio' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ];
    }

    protected function mensajesCliente(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'correo.email' => 'Debes capturar un correo válido.',
            'correo.unique' => 'Ese correo ya está registrado.',
            'curp.regex' => 'La CURP no tiene un formato válido (18 caracteres).',
            'curp.unique' => 'Esa CURP ya está registrada.',
            'rfc.regex' => 'El RFC no tiene un formato válido (12 o 13 caracteres).',
            'ingreso_mensual.numeric' => 'El ingreso mensual debe ser numérico.',
            'ine.mimes' => 'El INE debe ser JPG, JPEG, PNG, WEBP o PDF.',
            'ine.max' => 'El archivo de INE no debe exceder 5 MB.',
            'comprobante_domicilio.mimes' => 'El comprobante debe ser JPG, JPEG, PNG, WEBP o PDF.',
            'comprobante_domicilio.max' => 'El comprobante no debe exceder 5 MB.',
        ];
    }
}
