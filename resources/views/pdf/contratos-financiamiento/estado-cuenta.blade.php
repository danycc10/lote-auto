<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Estado de cuenta {{ $contrato->folio }}</title>
<style>
@page { margin: 18px; }
body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color:#111; }
h1 { margin:0; font-size:18px; }
p { margin:2px 0; }
.box { border:1px solid #d1d5db; padding:8px; margin:10px 0; }
table { width:100%; border-collapse: collapse; }
th, td { border:1px solid #d1d5db; padding:6px; }
th { background:#f3f4f6; }
.text-right { text-align:right; }
.text-center { text-align:center; }
</style>
</head>
<body>
<h1>Estado de cuenta</h1>
<p>Contrato: {{ $contrato->folio }}</p>
<p>Cliente: {{ $contrato->cliente?->nombre_completo ?: '—' }}</p>
<p>Auto: {{ $contrato->auto?->nombre_completo ?: '—' }}</p>
<div class="box">
    <table>
        <tr>
            <td><strong>Total contrato:</strong> ${{ number_format((float) ($estado['resumen']['total_contrato'] ?? 0), 2) }}</td>
            <td><strong>Total pagado:</strong> ${{ number_format((float) ($estado['resumen']['total_pagado'] ?? 0), 2) }}</td>
            <td><strong>Saldo actual:</strong> ${{ number_format((float) ($estado['resumen']['saldo_actual'] ?? 0), 2) }}</td>
        </tr>
        <tr>
            <td><strong>Monto vencido:</strong> ${{ number_format((float) ($estado['resumen']['monto_vencido'] ?? 0), 2) }}</td>
            <td><strong>Capital pendiente:</strong> ${{ number_format((float) ($estado['resumen']['capital_pendiente'] ?? 0), 2) }}</td>
            <td><strong>Interés pendiente:</strong> ${{ number_format((float) ($estado['resumen']['interes_pendiente'] ?? 0), 2) }}</td>
        </tr>
    </table>
</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Vencimiento</th>
            <th>Monto</th>
            <th>Pagado</th>
            <th>Saldo</th>
            <th>Estatus</th>
        </tr>
    </thead>
    <tbody>
        @foreach(($estado['cuotas'] ?? collect()) as $cuota)
            <tr>
                <td class="text-center">{{ $cuota->numero }}</td>
                <td class="text-center">{{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}</td>
                <td class="text-right">${{ number_format((float) $cuota->monto, 2) }}</td>
                <td class="text-right">${{ number_format((float) $cuota->monto_pagado, 2) }}</td>
                <td class="text-right">${{ number_format((float) $cuota->saldo, 2) }}</td>
                <td class="text-center">{{ ucfirst($cuota->estatus) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
