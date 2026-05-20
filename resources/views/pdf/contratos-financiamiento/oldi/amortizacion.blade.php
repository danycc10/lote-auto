<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Hoja de amortización {{ $contrato->folio }}</title>
    <style>
        @page { margin: 18px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111827; }
        .header { margin-bottom: 12px; }
        .header h1 { margin: 0 0 4px; font-size: 18px; }
        .meta { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .meta td { border: 1px solid #d1d5db; padding: 6px; }
        .tabla { width: 100%; border-collapse: collapse; }
        .tabla th, .tabla td { border: 1px solid #d1d5db; padding: 6px; }
        .tabla th { background: #f3f4f6; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .muted { color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hoja de amortización</h1>
        <div class="muted">Generado el {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table class="meta">
        <tr>
            <td><strong>Folio:</strong> {{ $contrato->folio ?: '—' }}</td>
            <td><strong>Fecha contrato:</strong> {{ optional($contrato->fecha_contrato)->format('d/m/Y') ?: '—' }}</td>
            <td><strong>Vendedor:</strong> {{ $contrato->vendedor?->name ?: '—' }}</td>
        </tr>
        <tr>
            <td><strong>Cliente:</strong> {{ $contrato->cliente?->nombre_completo ?: '—' }}</td>
            <td><strong>Unidad:</strong> {{ $contrato->auto?->nombre_completo ?: '—' }}</td>
            <td><strong>Inventario:</strong> {{ $contrato->auto?->codigo_inventario ?: '—' }}</td>
        </tr>
        <tr>
            <td><strong>Monto financiado:</strong> ${{ number_format((float) $contrato->monto_financiado, 2) }}</td>
            <td><strong>Total pagar:</strong> ${{ number_format((float) $contrato->total_pagar, 2) }}</td>
            <td><strong>Saldo actual:</strong> ${{ number_format((float) $contrato->saldo_actual, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Tasa:</strong> {{ number_format((float) $contrato->tasa_interes, 2) }}%</td>
            <td><strong>Plazo:</strong> {{ $contrato->plazo }}</td>
            <td><strong>Frecuencia:</strong> {{ ucfirst($contrato->frecuencia) }}</td>
        </tr>
    </table>

    <table class="tabla">
        <thead>
            <tr>
                <th>#</th>
                <th>Vencimiento</th>
                <th>Capital</th>
                <th>Interés</th>
                <th>Extra</th>
                <th>Total cuota</th>
                <th>Pagado</th>
                <th>Recargo</th>
                <th>Saldo</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cuotas as $cuota)
                <tr>
                    <td class="text-center">{{ $cuota->numero }}</td>
                    <td class="text-center">{{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}</td>
                    <td class="text-right">${{ number_format((float) $cuota->monto_capital, 2) }}</td>
                    <td class="text-right">${{ number_format((float) $cuota->monto_interes, 2) }}</td>
                    <td class="text-right">${{ number_format((float) $cuota->monto_extra, 2) }}</td>
                    <td class="text-right">${{ number_format((float) $cuota->monto, 2) }}</td>
                    <td class="text-right">${{ number_format((float) $cuota->monto_pagado, 2) }}</td>
                    <td class="text-right">${{ number_format((float) $cuota->recargo_aplicado, 2) }}</td>
                    <td class="text-right">${{ number_format((float) $cuota->saldo, 2) }}</td>
                    <td class="text-center">{{ ucfirst($cuota->estatus) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No hay cuotas generadas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
