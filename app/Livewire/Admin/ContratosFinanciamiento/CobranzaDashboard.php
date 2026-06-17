<?php

namespace App\Livewire\Admin\ContratosFinanciamiento;

use App\Enums\CuotaEstatus;
use App\Enums\PagoEstatus;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Models\PagoFinanciamiento;
use Livewire\Component;

class CobranzaDashboard extends Component
{
    public function render()
    {
        $hoy = now()->startOfDay();
        $mesInicio = now()->startOfMonth();
        $mesFin = now()->endOfMonth();

        $contratosActivos = ContratoFinanciamiento::whereIn('estatus', ['activo', 'atrasado'])->count();
        $contratosAtrasados = ContratoFinanciamiento::where('estatus', 'atrasado')->count();
        $saldoPendiente = (float) ContratoFinanciamiento::whereIn('estatus', ['activo', 'atrasado'])->sum('saldo_actual');
        $cobradoMes = (float) PagoFinanciamiento::where('estatus', PagoEstatus::Aplicado->value)->whereBetween('fecha_pago', [$mesInicio, $mesFin])->sum('monto_aplicado');
        $pagosHoy = (float) PagoFinanciamiento::where('estatus', PagoEstatus::Aplicado->value)->whereDate('fecha_pago', $hoy)->sum('monto_aplicado');

        $cuotasVencidas = CuotaFinanciamiento::query()
            ->with('contrato.cliente', 'contrato.auto.marca', 'contrato.auto.modelo')
            ->whereIn('estatus', CuotaEstatus::conSaldo())
            ->whereDate('fecha_vencimiento', '<', $hoy)
            ->orderBy('fecha_vencimiento')
            ->limit(20)
            ->get();

        $moraTotal = (float) $cuotasVencidas->sum('saldo');

        return view('livewire.admin.contratos-financiamiento.cobranza-dashboard', [
            'contratosActivos' => $contratosActivos,
            'contratosAtrasados' => $contratosAtrasados,
            'saldoPendiente' => $saldoPendiente,
            'cobradoMes' => $cobradoMes,
            'pagosHoy' => $pagosHoy,
            'moraTotal' => $moraTotal,
            'cuotasVencidas' => $cuotasVencidas,
        ])->layout('layouts.app');
    }
}
