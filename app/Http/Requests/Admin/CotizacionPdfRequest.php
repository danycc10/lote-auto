<?php

namespace App\Http\Requests\Admin;

use App\Enums\AutoEstatus;
use App\Models\Auto;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CotizacionPdfRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('contratos.ver') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'auto_id' => [
                'required',
                'integer',
                Rule::exists('autos', 'id')->where(
                    fn ($query) => $query
                        ->where('activo', true)
                        ->where('estatus', AutoEstatus::Disponible->value),
                ),
            ],
            'enganche' => ['nullable', 'numeric', 'min:0'],
            'plazo' => ['required', 'integer', 'min:1', 'max:120'],
            'tasa' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'cliente' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $auto = Auto::find($this->integer('auto_id'));

                if ($auto && $this->float('enganche') > (float) $auto->precio_financiado) {
                    $validator->errors()->add('enganche', 'El enganche no puede superar el precio del vehículo.');
                }
            },
        ];
    }
}
