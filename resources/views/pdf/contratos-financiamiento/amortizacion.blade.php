<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Amortización {{ $contrato->folio }}</title>
<style>
@page { margin: 18px; }
body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color:#111; }
.header { text-align:center; margin-bottom: 10px; }
.header h1 { margin:0; font-size: 18px; }
.header p { margin:2px 0; color:#555; }
table { width:100%; border-collapse: collapse; }
th, td { border:1px solid #d1d5db; padding:6px; }
th { background:#f3f4f6; }
.text-right { text-align:right; }
.text-center { text-align:center; }
.box { border:1px solid #d1d5db; padding:8px; margin-bottom:10px; }
</style>
</head>
<body>
<div class="header">
    <h1>Hoja de amortización</h1>
    <p>Contrato: {{ $contrato->folio }}</p>
    <p>Cliente: {{ $contrato->cliente?->nombre_completo ?: '—' }}</p>
    <p>Auto: {{ $contrato->auto?->nombre_completo ?: '—' }}</p>
</div>
<div class="box">
    <table>
        <tr>
            <td><strong>Fecha contrato:</strong> {{ optional($contrato->fecha_contrato)->format('d/m/Y') ?: '—' }}</td>
            <td><strong>Frecuencia:</strong> {{ ucfirst($contrato->frecuencia) }}</td>
            <td><strong>Plazo:</strong> {{ $contrato->plazo }}</td>
            <td><strong>Tasa:</strong> {{ number_format((float) $contrato->tasa_interes, 2) }}%</td>
        </tr>
        <tr>
            <td><strong>Total pagar:</strong> ${{ number_format((float) $contrato->total_pagar, 2) }}</td>
            <td><strong>Total pagado:</strong> ${{ number_format((float) $contrato->total_pagado, 2) }}</td>
            <td><strong>Saldo actual:</strong> ${{ number_format((float) $contrato->saldo_actual, 2) }}</td>
            <td><strong>Cuota:</strong> ${{ number_format((float) $contrato->monto_cuota, 2) }}</td>
        </tr>
    </table>
</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Vencimiento</th>
            <th>Capital</th>
            <th>Interés</th>
            <th>Extra</th>
            <th>Cuota</th>
            <th>Pagado</th>
            <th>Saldo</th>
            <th>Estatus</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cuotas as $cuota)
            <tr>
                <td class="text-center">{{ $cuota->numero }}</td>
                <td class="text-center">{{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}</td>
                <td class="text-right">${{ number_format((float) $cuota->monto_capital, 2) }}</td>
                <td class="text-right">${{ number_format((float) $cuota->monto_interes, 2) }}</td>
                <td class="text-right">${{ number_format((float) $cuota->monto_extra, 2) }}</td>
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
