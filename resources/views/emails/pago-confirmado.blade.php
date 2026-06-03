<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
body    { font-family: Arial, sans-serif; font-size: 14px; color: #1e293b; margin: 0; background: #f1f5f9; }
.wrap   { max-width: 540px; margin: 30px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
.top    { background: #16a34a; padding: 28px 32px; text-align: center; }
.top h1 { color: #fff; margin: 0; font-size: 22px; }
.top p  { color: #bbf7d0; margin: 4px 0 0; font-size: 13px; }
.body   { padding: 28px 32px; }
.body p { line-height: 1.6; color: #475569; margin: 0 0 14px; }
.box    { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px; margin: 20px 0; }
.box h2 { margin: 0 0 10px; font-size: 14px; color: #15803d; text-transform: uppercase; letter-spacing: .5px; }
.row    { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #dcfce7; font-size: 13px; }
.row:last-child { border-bottom: none; }
.row .label { color: #64748b; }
.row .value { font-weight: bold; color: #1e293b; }
.amount { text-align: center; margin: 20px 0; }
.amount .num { font-size: 36px; font-weight: bold; color: #16a34a; }
.amount .sub { font-size: 12px; color: #94a3b8; margin-top: 2px; }
.footer { background: #f8fafc; padding: 16px 32px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="wrap">
    <div class="top">
        <h1>✓ Pago recibido</h1>
        <p>Recibo {{ $recibo->folio }}</p>
    </div>
    <div class="body">
        <p>Hola <strong>{{ $pago->contrato?->cliente?->nombre_completo ?? 'Cliente' }}</strong>,</p>
        <p>Tu pago ha sido registrado exitosamente. Aquí está el resumen:</p>

        <div class="amount">
            <div class="num">${{ number_format((float) $pago->monto, 2) }}</div>
            <div class="sub">Pagado el {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</div>
        </div>

        <div class="box">
            <h2>Detalle del pago</h2>
            <div class="row">
                <span class="label">Recibo</span>
                <span class="value">{{ $recibo->folio }}</span>
            </div>
            <div class="row">
                <span class="label">Contrato</span>
                <span class="value">{{ $pago->contrato?->folio ?? '—' }}</span>
            </div>
            <div class="row">
                <span class="label">Vehículo</span>
                <span class="value">
                    {{ $pago->contrato?->auto?->marca?->nombre ?? '' }}
                    {{ $pago->contrato?->auto?->modelo?->nombre ?? '' }}
                    {{ $pago->contrato?->auto?->anio ?? '' }}
                </span>
            </div>
            @if($pago->cuota)
            <div class="row">
                <span class="label">Cuota</span>
                <span class="value">#{{ $pago->cuota->numero }}</span>
            </div>
            @endif
            <div class="row">
                <span class="label">Forma de pago</span>
                <span class="value">{{ ucfirst($pago->forma_pago) }}</span>
            </div>
            <div class="row">
                <span class="label">Saldo anterior</span>
                <span class="value">${{ number_format((float) $recibo->saldo_anterior, 2) }}</span>
            </div>
            <div class="row">
                <span class="label">Saldo restante</span>
                <span class="value">${{ number_format((float) $recibo->saldo_posterior, 2) }}</span>
            </div>
        </div>

        @if((float) $recibo->saldo_posterior <= 0)
        <p style="text-align:center; font-size:15px; color:#16a34a; font-weight:bold;">
            ¡Felicidades! Tu contrato ha quedado <strong>liquidado</strong>.
        </p>
        @else
        <p style="color:#64748b; font-size:13px;">
            Tu saldo restante es <strong>${{ number_format((float) $recibo->saldo_posterior, 2) }}</strong>.
            Gracias por tu pago puntual.
        </p>
        @endif
    </div>
    <div class="footer">
        Este correo es una confirmación automática. Guárdalo como comprobante de tu pago.
    </div>
</div>
</body>
</html>
