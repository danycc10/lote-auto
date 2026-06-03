<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
body    { font-family: Arial, sans-serif; font-size: 14px; color: #1e293b; margin: 0; background: #f1f5f9; }
.wrap   { max-width: 540px; margin: 30px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
.top    { background: #4f46e5; padding: 28px 32px; text-align: center; }
.top h1 { color: #fff; margin: 0; font-size: 22px; }
.top p  { color: #c7d2fe; margin: 4px 0 0; font-size: 13px; }
.body   { padding: 28px 32px; }
.body p { line-height: 1.6; color: #475569; margin: 0 0 14px; }
.box    { background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 8px; padding: 16px; margin: 20px 0; }
.box h2 { margin: 0 0 10px; font-size: 14px; color: #4338ca; text-transform: uppercase; letter-spacing: .5px; }
.row    { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #e0e7ff; font-size: 13px; }
.row:last-child { border-bottom: none; }
.row .label { color: #64748b; }
.row .value { font-weight: bold; color: #1e293b; }
.badge  { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: bold; background: #4f46e5; color: #fff; }
.btn    { display: inline-block; background: #4f46e5; color: #fff; text-decoration: none; padding: 10px 24px; border-radius: 8px; font-weight: bold; font-size: 14px; margin-top: 16px; }
.footer { background: #f8fafc; padding: 16px 32px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="wrap">
    <div class="top">
        <h1>Nuevo prospecto desde la web</h1>
        <p>Recibiste una nueva solicitud de contacto</p>
    </div>
    <div class="body">
        <p>Se ha registrado un nuevo prospecto a través del formulario de contacto de tu sitio web. A continuación los datos del interesado:</p>

        <div class="box">
            <h2>Datos del prospecto</h2>
            <div class="row">
                <span class="label">Nombre</span>
                <span class="value">{{ $prospecto->nombre }}</span>
            </div>
            @if($prospecto->telefono)
            <div class="row">
                <span class="label">Teléfono</span>
                <span class="value">{{ $prospecto->telefono }}</span>
            </div>
            @endif
            @if($prospecto->correo)
            <div class="row">
                <span class="label">Correo</span>
                <span class="value">{{ $prospecto->correo }}</span>
            </div>
            @endif
            @if($prospecto->auto)
            <div class="row">
                <span class="label">Auto de interés</span>
                <span class="value">{{ $prospecto->auto->marca?->nombre }} {{ $prospecto->auto->modelo?->nombre }} {{ $prospecto->auto->anio }}</span>
            </div>
            @endif
            @if($prospecto->observaciones)
            <div class="row">
                <span class="label">Mensaje</span>
                <span class="value" style="max-width:300px; text-align:right;">{{ $prospecto->observaciones }}</span>
            </div>
            @endif
            <div class="row">
                <span class="label">Origen</span>
                <span class="value"><span class="badge">{{ ucfirst($prospecto->origen ?? 'web') }}</span></span>
            </div>
            <div class="row">
                <span class="label">Registrado</span>
                <span class="value">{{ $prospecto->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <p style="text-align:center;">
            <a href="{{ url('/admin/prospectos') }}" class="btn">Ver en el sistema →</a>
        </p>
    </div>
    <div class="footer">
        Este correo es generado automáticamente cuando un cliente llena el formulario de contacto en tu sitio web.
    </div>
</div>
</body>
</html>
