<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Recibo {{ $recibo->folio }}</title>
<style>
    @page {
        size: 80mm auto;
        margin: 3mm 4mm 6mm 4mm;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 8pt;
        color: #000;
        background: #fff;
        line-height: 1.4;
    }

    /* ── SEPARADORES ── */
    .sep-solid {
        border-top: 1px solid #000;
        margin: 5px 0;
    }

    .sep-dashed {
        border-top: 1px dashed #000;
        margin: 5px 0;
    }

    .sep-double {
        border-top: 3px double #000;
        margin: 5px 0;
    }

    /* ── ENCABEZADO ── */
    .header {
        text-align: center;
        padding-bottom: 6px;
    }

    .brand-name {
        font-size: 14pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .brand-sub {
        font-size: 7pt;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-top: 1px;
    }

    /* ── CANCELADO ── */
    .cancelled-banner {
        border: 2px solid #000;
        text-align: center;
        padding: 5px 4px;
        font-size: 10pt;
        font-weight: bold;
        letter-spacing: 2px;
        margin-bottom: 6px;
    }

    .cancelled-date {
        text-align: center;
        font-size: 7pt;
        margin-bottom: 5px;
    }

    /* ── FOLIO ── */
    .folio-block {
        border: 1px solid #000;
        text-align: center;
        padding: 5px 6px;
        margin: 5px 0;
    }

    .folio-label {
        font-size: 6pt;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .folio-number {
        font-size: 13pt;
        font-weight: bold;
        letter-spacing: 0.5px;
        margin-top: 1px;
    }

    /* ── TÍTULOS DE SECCIÓN ── */
    .section-title {
        font-size: 6.5pt;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-align: center;
        padding: 2px 0;
        margin-bottom: 4px;
    }

    /* ── TABLA DE DATOS ── */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table td {
        vertical-align: top;
        padding: 1.5px 0;
        font-size: 7.5pt;
        line-height: 1.4;
    }

    .dt-label {
        width: 36%;
        font-weight: bold;
    }

    .dt-value {
        width: 64%;
        word-wrap: break-word;
    }

    /* ── IMPORTE ── */
    .amount-label {
        font-size: 6.5pt;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-align: center;
        margin-bottom: 3px;
    }

    .amount-box {
        border: 2px solid #000;
        text-align: center;
        padding: 6px 4px 5px;
        margin: 0 2px;
    }

    .amount-value {
        font-family: DejaVu Sans Mono, monospace;
        font-size: 22pt;
        font-weight: bold;
        letter-spacing: 0;
    }

    /* ── SALDOS ── */
    .balance-table {
        width: 100%;
        border-collapse: collapse;
    }

    .balance-table td {
        font-size: 7.5pt;
        padding: 1.5px 0;
        vertical-align: middle;
    }

    .bl-value {
        text-align: right;
        font-family: DejaVu Sans Mono, monospace;
        font-size: 7.5pt;
    }

    .bl-total td {
        font-weight: bold;
        font-size: 8.5pt;
        padding-top: 3px;
    }

    .bl-total .bl-value {
        font-size: 8.5pt;
    }

    .liquidado-note {
        border: 1px solid #000;
        text-align: center;
        padding: 3px 5px;
        font-size: 7pt;
        font-weight: bold;
        margin-top: 5px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    /* ── OBSERVACIONES ── */
    .obs-text {
        font-size: 7.5pt;
        line-height: 1.4;
    }

    /* ── FIRMA ── */
    .signature-line {
        border-top: 1px solid #000;
        width: 60%;
        margin: 16px auto 3px;
    }

    .signature-label {
        font-size: 6.5pt;
        text-align: center;
    }

    /* ── PIE ── */
    .footer {
        text-align: center;
        padding-top: 4px;
    }

    .footer-thanks {
        font-size: 9pt;
        font-weight: bold;
    }

    .footer-note {
        font-size: 7pt;
        margin-top: 2px;
    }

    .footer-ts {
        font-size: 6pt;
        margin-top: 4px;
    }
</style>
</head>
<body>

    {{-- ── CANCELADO ── --}}
    @if($recibo->estatus === 'cancelado')
        <div class="cancelled-banner">*** RECIBO CANCELADO ***</div>
        @if($recibo->cancelado_at)
            <div class="cancelled-date">
                Cancelado: {{ $recibo->cancelado_at->format('d/m/Y H:i') }}
            </div>
        @endif
    @endif

    {{-- ── ENCABEZADO ── --}}
    @php
        $ticketLogoPath = \App\Models\Configuracion::obtener('branding.logo_ticket_url', '');
        $ticketLogoSrc  = $ticketLogoPath
            ? 'file://' . str_replace('\\', '/', \Illuminate\Support\Facades\Storage::disk('public')->path($ticketLogoPath))
            : null;
    @endphp
    <div class="header">
        @if($ticketLogoSrc)
            <img src="{{ $ticketLogoSrc }}" alt="{{ config('app.name') }}"
                 style="max-width:60mm; max-height:18mm; display:block; margin:0 auto 4px;">
        @else
            <div class="brand-name">{{ config('app.name') }}</div>
        @endif
        <div class="brand-sub">Recibo de pago</div>
    </div>

    <div class="sep-double"></div>

    {{-- ── FOLIO ── --}}
    <div class="folio-block">
        <div class="folio-label">Folio</div>
        <div class="folio-number">{{ $recibo->folio }}</div>
    </div>

    <div class="sep-dashed"></div>

    {{-- ── DATOS GENERALES ── --}}
    <div class="section-title">Datos generales</div>
    <table class="data-table">
        <tr>
            <td class="dt-label">Fecha:</td>
            <td class="dt-value">{{ optional($recibo->fecha_recibo)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="dt-label">Contrato:</td>
            <td class="dt-value">{{ $recibo->contrato?->folio ?? '—' }}</td>
        </tr>
        <tr>
            <td class="dt-label">Cliente:</td>
            <td class="dt-value">{{ $recibo->cliente?->nombre_completo ?? '—' }}</td>
        </tr>
        <tr>
            <td class="dt-label">Vehículo:</td>
            <td class="dt-value">{{ $recibo->contrato?->auto?->nombre_completo ?? '—' }}</td>
        </tr>
        <tr>
            <td class="dt-label">Cuota:</td>
            <td class="dt-value">#{{ $recibo->cuota?->numero ?? '—' }}</td>
        </tr>
    </table>

    <div class="sep-dashed"></div>

    {{-- ── IMPORTE ── --}}
    @if($recibo->concepto)
        <div style="text-align:center; font-size:7.5pt; font-style:italic; margin-bottom:5px;">
            {{ $recibo->concepto }}
        </div>
    @endif

    <div class="amount-label">Importe recibido</div>
    <div class="amount-box">
        <div class="amount-value">${{ number_format((float) $recibo->monto, 2) }}</div>
    </div>

    <div class="sep-dashed"></div>

    {{-- ── FORMA DE PAGO ── --}}
    <div class="section-title">Detalle del pago</div>
    <table class="data-table">
        <tr>
            <td class="dt-label">Forma:</td>
            <td class="dt-value">{{ ucfirst($recibo->pago?->forma_pago ?? '—') }}</td>
        </tr>
        @if($recibo->pago?->referencia)
            <tr>
                <td class="dt-label">Referencia:</td>
                <td class="dt-value">{{ $recibo->pago->referencia }}</td>
            </tr>
        @endif
        @if($recibo->pago?->capturadoPor)
            <tr>
                <td class="dt-label">Cobrador:</td>
                <td class="dt-value">{{ $recibo->pago->capturadoPor->name }}</td>
            </tr>
        @endif
    </table>

    <div class="sep-dashed"></div>

    {{-- ── SALDOS ── --}}
    <div class="section-title">Saldos del contrato</div>
    <table class="balance-table">
        <tr>
            <td>Saldo anterior:</td>
            <td class="bl-value">${{ number_format((float) $recibo->saldo_anterior, 2) }}</td>
        </tr>
        <tr>
            <td>Este pago:</td>
            <td class="bl-value">- ${{ number_format((float) $recibo->monto, 2) }}</td>
        </tr>
    </table>
    <div class="sep-solid" style="margin: 3px 0;"></div>
    <table class="balance-table">
        <tr class="bl-total">
            <td>Saldo restante:</td>
            <td class="bl-value">${{ number_format((float) $recibo->saldo_posterior, 2) }}</td>
        </tr>
    </table>

    @if((float) $recibo->saldo_posterior <= 0)
        <div class="liquidado-note">** Contrato liquidado **</div>
    @endif

    {{-- ── OBSERVACIONES ── --}}
    @if($recibo->observaciones)
        <div class="sep-dashed"></div>
        <div class="section-title">Observaciones</div>
        <div class="obs-text">{{ $recibo->observaciones }}</div>
    @endif

    <div class="sep-double"></div>

    {{-- ── FIRMA ── --}}
    <div class="signature-line"></div>
    <div class="signature-label">Firma del cobrador</div>

    <div class="sep-dashed" style="margin-top: 8px;"></div>

    {{-- ── PIE ── --}}
    <div class="footer">
        <div class="footer-thanks">¡Gracias por su pago!</div>
        <div class="footer-note">Conserve este comprobante.</div>
        <div class="footer-ts">Generado: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

</body>
</html>
