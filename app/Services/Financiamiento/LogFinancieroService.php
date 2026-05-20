<?php

namespace App\Services\Financiamiento;

use App\Models\LogFinanciero;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogFinancieroService
{
    public function registrar(
        string $tipo,
        string $titulo,
        ?string $descripcion = null,
        ?string $modulo = null,
        ?string $referencia = null,
        ?Model $modelo = null,
        ?float $monto = null,
        ?float $saldoAnterior = null,
        ?float $saldoNuevo = null,
        array $metadata = [],
        string $nivel = 'info'
    ): LogFinanciero {
        return LogFinanciero::create([
            'user_id' => Auth::id(),
            'tipo' => $tipo,
            'modulo' => $modulo,
            'referencia' => $referencia,
            'loggable_type' => $modelo ? get_class($modelo) : null,
            'loggable_id' => $modelo?->getKey(),
            'monto' => $monto,
            'saldo_anterior' => $saldoAnterior,
            'saldo_nuevo' => $saldoNuevo,
            'moneda' => 'MXN',
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'metadata' => $metadata ?: null,
            'nivel' => $nivel,
            'ip' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }

    public function pagoRegistrado(
        Model $pago,
        string $folioRecibo,
        float $monto,
        float $saldoAnterior,
        float $saldoNuevo,
        array $metadata = []
    ): LogFinanciero {
        return $this->registrar(
            tipo: 'pago_registrado',
            titulo: 'Pago registrado',
            descripcion: 'Se registró un pago por $' . number_format($monto, 2) . ' con recibo ' . $folioRecibo . '.',
            modulo: 'financiamiento',
            referencia: $folioRecibo,
            modelo: $pago,
            monto: $monto,
            saldoAnterior: $saldoAnterior,
            saldoNuevo: $saldoNuevo,
            metadata: $metadata,
            nivel: 'success'
        );
    }

    public function reciboCancelado(
        Model $recibo,
        string $folioRecibo,
        float $monto,
        float $saldoAnterior,
        float $saldoNuevo,
        ?string $motivo = null,
        array $metadata = []
    ): LogFinanciero {
        return $this->registrar(
            tipo: 'recibo_cancelado',
            titulo: 'Recibo cancelado',
            descripcion: 'Se canceló el recibo ' . $folioRecibo . ' por $' . number_format($monto, 2) . ($motivo ? '. Motivo: ' . $motivo : '.'),
            modulo: 'financiamiento',
            referencia: $folioRecibo,
            modelo: $recibo,
            monto: $monto,
            saldoAnterior: $saldoAnterior,
            saldoNuevo: $saldoNuevo,
            metadata: $metadata,
            nivel: 'warning'
        );
    }

    public function contratoLiquidado(
        Model $contrato,
        ?string $referencia = null,
        array $metadata = []
    ): LogFinanciero {
        return $this->registrar(
            tipo: 'contrato_liquidado',
            titulo: 'Contrato liquidado',
            descripcion: 'El contrato fue liquidado completamente.',
            modulo: 'financiamiento',
            referencia: $referencia,
            modelo: $contrato,
            monto: null,
            saldoAnterior: null,
            saldoNuevo: 0,
            metadata: $metadata,
            nivel: 'success'
        );
    }
}