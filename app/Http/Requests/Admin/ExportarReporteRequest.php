<?php

namespace App\Http\Requests\Admin;

use App\Enums\TipoReporte;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ExportarReporteRequest extends FormRequest
{
    private const MAX_RANGE_DAYS = 366;

    public function authorize(): bool
    {
        return $this->user()?->can('reportes.ver') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $requiresDates = $this->string('tipo')->toString() !== TipoReporte::Inventario->value;

        return [
            'tipo' => ['required', Rule::enum(TipoReporte::class)],
            'desde' => [Rule::requiredIf($requiresDates), 'nullable', 'date_format:Y-m-d', 'before_or_equal:hasta'],
            'hasta' => [Rule::requiredIf($requiresDates), 'nullable', 'date_format:Y-m-d', 'after_or_equal:desde'],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (
                    $validator->errors()->has('desde')
                    || $validator->errors()->has('hasta')
                    || ! $this->filled(['desde', 'hasta'])
                ) {
                    return;
                }

                $desde = CarbonImmutable::createFromFormat('Y-m-d', $this->string('desde')->toString());
                $hasta = CarbonImmutable::createFromFormat('Y-m-d', $this->string('hasta')->toString());

                if ($desde->diffInDays($hasta) > self::MAX_RANGE_DAYS) {
                    $validator->errors()->add(
                        'hasta',
                        'El rango del reporte no puede superar '.self::MAX_RANGE_DAYS.' días.',
                    );
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tipo.required' => 'Selecciona un tipo de reporte.',
            'tipo.enum' => 'El tipo de reporte seleccionado no es válido.',
            'desde.required' => 'La fecha inicial es obligatoria para este reporte.',
            'desde.date_format' => 'La fecha inicial debe tener el formato AAAA-MM-DD.',
            'desde.before_or_equal' => 'La fecha inicial debe ser anterior o igual a la fecha final.',
            'hasta.required' => 'La fecha final es obligatoria para este reporte.',
            'hasta.date_format' => 'La fecha final debe tener el formato AAAA-MM-DD.',
            'hasta.after_or_equal' => 'La fecha final debe ser posterior o igual a la fecha inicial.',
        ];
    }
}
