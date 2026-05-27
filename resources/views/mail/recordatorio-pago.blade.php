@php
    $logoPath = \App\Models\Configuracion::obtener('branding.logo_url', '');
    $logoSrc  = $logoPath ? url(\Illuminate\Support\Facades\Storage::url($logoPath)) : null;
    $colorPrimario   = \App\Models\Configuracion::obtener('branding.color_primario', '#3b82f6');
    $colorSecundario = \App\Models\Configuracion::obtener('branding.color_secundario', '#10b981');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $asunto }}</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f8fafc; margin: 0; padding: 0; color: #1e293b; }
    .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
    .header  { background: #0f172a; padding: 28px 32px; text-align: center; }
    .header img { max-height: 56px; max-width: 180px; display: block; margin: 0 auto 10px; object-fit: contain; }
    .header h1 { color: #ffffff; font-size: 18px; margin: 0; font-weight: 700; letter-spacing: -.3px; }
    .header p  { color: #94a3b8; font-size: 13px; margin: 6px 0 0; }
    .divider { height: 3px; background: linear-gradient(to right, {{ $colorPrimario }}, {{ $colorSecundario }}); }
    .body    { padding: 32px; }
    .body p  { font-size: 15px; line-height: 1.65; color: #334155; margin: 0 0 16px; white-space: pre-line; }
    .alert   { background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px; padding: 14px 18px; margin: 20px 0; }
    .alert p { font-size: 13px; color: #92400e; margin: 0; }
    .footer  { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 32px; text-align: center; }
    .footer p { font-size: 12px; color: #94a3b8; margin: 0; line-height: 1.6; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        @if($logoSrc)
            <img src="{{ $logoSrc }}" alt="{{ config('app.name') }}">
        @else
            <h1>{{ config('app.name') }}</h1>
        @endif
        <p>Notificación de cobranza</p>
    </div>
    <div class="divider"></div>
    <div class="body">
        <p>{{ $cuerpo }}</p>
        <div class="alert">
            <p>Si ya realizó su pago, por favor ignore este mensaje o contáctenos para confirmarlo.</p>
        </div>
    </div>
    <div class="footer">
        <p>
            Este correo fue enviado desde {{ config('app.name') }}.<br>
            Generado el {{ now()->format('d/m/Y H:i') }}.
        </p>
    </div>
</div>
</body>
</html>
