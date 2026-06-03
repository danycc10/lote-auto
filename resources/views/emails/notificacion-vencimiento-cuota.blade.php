@php
    use App\Models\Configuracion;
    use Illuminate\Support\Facades\Storage;

    $logoPath = Configuracion::obtener('branding.logo_url', '');
    $logoSrc  = $logoPath ? url(Storage::url($logoPath)) : null;
    $colorP   = Configuracion::obtener('branding.color_primario',   '#3b82f6');
    $colorS   = Configuracion::obtener('branding.color_secundario', '#10b981');
    $whatsapp = Configuracion::obtener('contact.whatsapp', '');
    $waUrl    = $whatsapp ? 'https://wa.me/' . preg_replace('/\D/', '', $whatsapp) : null;

    $contrato  = $cuota->contrato;
    $cliente   = $contrato?->cliente;
    $auto      = $contrato?->auto;
    $vence     = $cuota->fecha_vencimiento;
    $diasResta = (int) now()->startOfDay()->diffInDays($vence->startOfDay(), false);

    $esHoy = $tipo === 'vencimiento_hoy';
    $headerColor = $esHoy ? '#dc2626' : '#d97706';
    $headerTextColor = '#fff';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body    { font-family: Arial, sans-serif; font-size: 14px; color: #1e293b; margin: 0; background: #f1f5f9; }
.wrap   { max-width: 560px; margin: 30px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
.top    { padding: 28px 32px; text-align: center; }
.top h1 { color: {{ $headerTextColor }}; margin: 0; font-size: 20px; }
.top p  { color: rgba(255,255,255,.75); margin: 4px 0 0; font-size: 13px; }
.divider{ height: 3px; background: linear-gradient(to right, {{ $colorP }}, {{ $colorS }}); }
.body   { padding: 28px 32px; }
.body p { line-height: 1.65; color: #475569; margin: 0 0 14px; }
.amount { text-align: center; margin: 20px 0; }
.amount .num { font-size: 40px; font-weight: bold; }
.amount .sub { font-size: 12px; color: #94a3b8; margin-top: 2px; }
.box    { border-radius: 8px; padding: 16px; margin: 20px 0; }
.box h2 { margin: 0 0 10px; font-size: 13px; text-transform: uppercase; letter-spacing: .5px; }
.row    { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
.row:last-child { border-bottom: none; }
.row .label { color: #64748b; }
.row .value { font-weight: bold; color: #1e293b; }
.btn    { display: block; text-align: center; text-decoration: none; padding: 13px 24px; border-radius: 8px; font-weight: bold; font-size: 14px; margin: 8px 0; }
.footer { background: #f8fafc; padding: 16px 32px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="wrap">

    <div class="top" style="background: {{ $headerColor }}">
        @if($logoSrc)
            <img src="{{ $logoSrc }}" alt="{{ config('app.name') }}" style="max-height:48px; max-width:160px; display:block; margin:0 auto 10px; object-fit:contain;">
        @endif
        <h1>
            @if($esHoy)
                Tu pago vence hoy
            @else
                Recordatorio de pago
            @endif
        </h1>
        <p>
            @if($esHoy)
                Evita recargos, realiza tu pago hoy
            @else
                Tu cuota vence en {{ $diasResta }} {{ $diasResta === 1 ? 'día' : 'días' }}
            @endif
        </p>
    </div>

    <div class="divider"></div>

    <div class="body">
        <p>
            Hola <strong>{{ $cliente?->nombre_completo ?? 'Cliente' }}</strong>, te recordamos que tienes un pago pendiente de tu financiamiento.
        </p>

        {{-- Amount highlight --}}
        <div class="amount">
            <div class="num" style="color: {{ $headerColor }}">${{ number_format((float) ($cuota->saldo ?? $cuota->monto), 2) }}</div>
            <div class="sub">Saldo pendiente · Cuota #{{ $cuota->numero }}</div>
        </div>

        {{-- Detail box --}}
        <div class="box" style="background: {{ $esHoy ? '#fef2f2' : '#fffbeb' }}; border: 1px solid {{ $esHoy ? '#fecaca' : '#fde68a' }}">
            <h2 style="color: {{ $headerColor }}">Detalle del pago</h2>
            <div class="row">
                <span class="label">Contrato</span>
                <span class="value">{{ $contrato?->folio ?? '—' }}</span>
            </div>
            <div class="row">
                <span class="label">Vehículo</span>
                <span class="value">
                    {{ $auto?->marca?->nombre ?? '' }}
                    {{ $auto?->modelo?->nombre ?? '' }}
                    {{ $auto?->anio ?? '' }}
                </span>
            </div>
            <div class="row">
                <span class="label">Cuota</span>
                <span class="value">#{{ $cuota->numero }}</span>
            </div>
            <div class="row">
                <span class="label">Fecha de vencimiento</span>
                <span class="value">{{ \Carbon\Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y') }}</span>
            </div>
            <div class="row">
                <span class="label">Monto total cuota</span>
                <span class="value">${{ number_format((float) $cuota->monto, 2) }}</span>
            </div>
            @if((float) ($cuota->monto_pagado ?? 0) > 0)
            <div class="row">
                <span class="label">Ya pagado</span>
                <span class="value" style="color:#16a34a">${{ number_format((float) $cuota->monto_pagado, 2) }}</span>
            </div>
            @endif
            <div class="row">
                <span class="label">Saldo pendiente</span>
                <span class="value" style="color: {{ $headerColor }}">${{ number_format((float) ($cuota->saldo ?? $cuota->monto), 2) }}</span>
            </div>
        </div>

        @if($waUrl)
        <a href="{{ $waUrl }}" class="btn" style="background: #16a34a; color: #fff">
            Realizar pago por WhatsApp
        </a>
        @endif

        <p style="text-align:center; font-size:12px; color:#94a3b8; margin-top:8px;">
            Si ya realizaste tu pago, por favor ignora este mensaje o contáctanos para confirmarlo.
        </p>
    </div>

    <div class="footer">
        Este correo es una notificación automática de {{ config('app.name') }}.
        Generado el {{ now()->format('d/m/Y') }}.
    </div>

</div>
</body>
</html>
