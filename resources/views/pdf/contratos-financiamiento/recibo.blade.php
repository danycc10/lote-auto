<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Recibo {{ $recibo->folio }}</title>
<style>
    @page {
        size: 80mm auto;
        margin: 3mm 3mm 5mm 3mm;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9px;
        color: #111;
        margin: 0;
        padding: 0;
        line-height: 1.3;
    }

    .ticket {
        width: 100%;
    }

    .center { text-align: center; }
    .right { text-align: right; }
    .bold { font-weight: bold; }
    .muted { color: #444; }
    .tiny { font-size: 7px; }
    .small { font-size: 8px; }

    .brand {
        text-align: center;
        margin-bottom: 6px;
    }

    .brand-name {
        font-size: 13px;
        font-weight: bold;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .brand-sub {
        font-size: 8px;
        margin-top: 2px;
    }

    .folio-box {
        border: 1px solid #000;
        padding: 5px 6px;
        text-align: center;
        margin: 6px 0;
    }

    .folio-box .title {
        font-size: 10px;
        font-weight: bold;
        margin-bottom: 2px;
    }

    .folio-box .folio {
        font-size: 11px;
        font-weight: bold;
        letter-spacing: .4px;
    }

    .divider {
        border-top: 1px dashed #000;
        margin: 6px 0;
    }

    .section {
        margin-bottom: 6px;
    }

    .section-title {
        text-align: center;
        font-size: 8px;
        font-weight: bold;
        padding: 2px 0;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        margin-bottom: 5px;
        letter-spacing: .3px;
    }

    .row {
        width: 100%;
        margin-bottom: 3px;
        clear: both;
    }

    .label {
        display: inline-block;
        width: 36%;
        vertical-align: top;
        font-weight: bold;
    }

    .value {
        display: inline-block;
        width: 62%;
        vertical-align: top;
        word-wrap: break-word;
    }

    .box {
        border: 1px solid #000;
        padding: 6px;
        margin-top: 4px;
    }

    .concepto {
        text-align: center;
        font-size: 8px;
        margin-bottom: 5px;
    }

    .monto-box {
        border: 1px solid #000;
        padding: 6px;
        margin: 6px 0;
    }

    .monto-label {
        text-align: center;
        font-size: 8px;
        font-weight: bold;
        margin-bottom: 3px;
    }

    .monto-value {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        letter-spacing: .4px;
    }

    .saldo-box {
        border: 1px solid #000;
        padding: 5px 6px;
        margin-top: 6px;
    }

    .saldo-line {
        width: 100%;
        margin-bottom: 3px;
    }

    .saldo-line:last-child {
        margin-bottom: 0;
    }

    .saldo-label {
        display: inline-block;
        width: 58%;
        font-weight: bold;
    }

    .saldo-value {
        display: inline-block;
        width: 40%;
        text-align: right;
    }

    .highlight {
        font-weight: bold;
        font-size: 10px;
    }

    .observaciones {
        border: 1px solid #000;
        padding: 5px 6px;
        margin-top: 6px;
        font-size: 8px;
        word-wrap: break-word;
    }

    .footer {
        margin-top: 8px;
        text-align: center;
    }

    .thanks {
        font-size: 9px;
        font-weight: bold;
    }

    .note {
        font-size: 7px;
        margin-top: 2px;
    }

    .legal {
        margin-top: 6px;
        font-size: 7px;
        text-align: center;
    }
</style>
</head>
<body>
<div class="ticket">

    {{-- ENCABEZADO --}}
    <div class="brand">
        <div class="brand-name">Lote Autos</div>
        <div class="brand-sub muted">Recibo de pago</div>
    </div>

    <div class="folio-box">
        <div class="title">FOLIO</div>
        <div class="folio">{{ $recibo->folio }}</div>
    </div>

    {{-- DATOS GENERALES --}}
    <div class="section">
        <div class="section-title">DATOS GENERALES</div>

        <div class="row">
            <span class="label">Fecha:</span>
            <span class="value">{{ optional($recibo->fecha_recibo)->format('d/m/Y') }}</span>
        </div>

        <div class="row">
            <span class="label">Contrato:</span>
            <span class="value">{{ $recibo->contrato?->folio ?? '—' }}</span>
        </div>

        <div class="row">
            <span class="label">Cliente:</span>
            <span class="value">{{ $recibo->cliente?->nombre_completo ?? '—' }}</span>
        </div>

        <div class="row">
            <span class="label">Vehículo:</span>
            <span class="value">{{ $recibo->contrato?->auto?->nombre_completo ?? '—' }}</span>
        </div>

        <div class="row">
            <span class="label">Cuota:</span>
            <span class="value">#{{ $recibo->cuota?->numero ?? '—' }}</span>
        </div>
    </div>

    {{-- PAGO --}}
    <div class="section">
        <div class="section-title">PAGO</div>

        <div class="row">
            <span class="label">Forma de pago:</span>
            <span class="value">{{ ucfirst($recibo->pago?->forma_pago ?? '—') }}</span>
        </div>

        @if($recibo->pago?->referencia)
            <div class="row">
                <span class="label">Referencia:</span>
                <span class="value">{{ $recibo->pago->referencia }}</span>
            </div>
        @endif

        <div class="box">
            <div class="concepto bold">
                {{ $recibo->concepto ?? 'Pago de financiamiento' }}
            </div>

            <div class="monto-box">
                <div class="monto-label">IMPORTE RECIBIDO</div>
                <div class="monto-value">${{ number_format((float) $recibo->monto, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- SALDOS --}}
    <div class="section">
        <div class="section-title">SALDOS</div>

        <div class="saldo-box">
            <div class="saldo-line">
                <span class="saldo-label">Saldo anterior</span>
                <span class="saldo-value">${{ number_format((float) $recibo->saldo_anterior, 2) }}</span>
            </div>

            <div class="saldo-line">
                <span class="saldo-label highlight">Saldo posterior</span>
                <span class="saldo-value highlight">${{ number_format((float) $recibo->saldo_posterior, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- OBSERVACIONES --}}
    @if($recibo->observaciones)
        <div class="section">
            <div class="section-title">OBSERVACIONES</div>
            <div class="observaciones">
                {{ $recibo->observaciones }}
            </div>
        </div>
    @endif

    <div class="divider"></div>

    {{-- PIE --}}
    <div class="footer">
        <div class="thanks">¡Gracias por su pago!</div>
        <div class="note">Conserve este comprobante</div>
    </div>

    <div class="legal muted">
        Documento generado automáticamente
    </div>
</div>
</body>
</html>