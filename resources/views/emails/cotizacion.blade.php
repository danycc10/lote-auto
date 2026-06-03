<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
body      { font-family: Arial, sans-serif; font-size: 14px; color: #1e293b; margin: 0; background: #f1f5f9; }
.wrap     { max-width: 560px; margin: 30px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
.top      { background: #6366f1; padding: 28px 32px; text-align: center; }
.top h1   { color: #fff; margin: 0; font-size: 22px; }
.top p    { color: #c7d2fe; margin: 4px 0 0; font-size: 13px; }
.body     { padding: 28px 32px; }
.body p   { line-height: 1.6; color: #475569; margin: 0 0 14px; }
.kpis     { display: flex; gap: 10px; margin: 20px 0; }
.kpi      { flex: 1; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; text-align: center; }
.kpi-l    { font-size: 11px; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; }
.kpi-v    { font-size: 18px; font-weight: bold; color: #4f46e5; }
.car-box  { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 16px; margin-bottom: 20px; }
.car-box h2 { margin: 0 0 4px; font-size: 16px; color: #1e293b; }
.car-box p  { margin: 2px 0; color: #64748b; font-size: 13px; }
.note     { font-size: 12px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 14px; margin-top: 20px; }
.footer   { background: #f8fafc; padding: 16px 32px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="wrap">
    <div class="top">
        <h1>{{ $empresa['nombre'] }}</h1>
        <p>Cotización de financiamiento</p>
    </div>
    <div class="body">
        @if($nombreCliente)
        <p>Hola <strong>{{ $nombreCliente }}</strong>,</p>
        @else
        <p>Hola,</p>
        @endif

        <p>Te adjuntamos la cotización del siguiente vehículo:</p>

        <div class="car-box">
            <h2>{{ $auto->marca?->nombre }} {{ $auto->modelo?->nombre }} {{ $auto->anio }}</h2>
            @if($auto->placas)<p>Placas: {{ $auto->placas }}</p>@endif
            <p style="font-size:15px; font-weight:bold; color:#4f46e5; margin-top:6px;">
                Precio: ${{ number_format((float)$auto->precio_financiado, 2) }}
            </p>
        </div>

        <div class="kpis">
            <div class="kpi">
                <div class="kpi-l">Enganche</div>
                <div class="kpi-v" style="color:#475569;">${{ number_format($enganche, 2) }}</div>
            </div>
            <div class="kpi">
                <div class="kpi-l">Plazo</div>
                <div class="kpi-v" style="color:#475569;">{{ $plazo }} meses</div>
            </div>
            <div class="kpi">
                <div class="kpi-l">Mensualidad</div>
                <div class="kpi-v">${{ number_format($cuotaMensual, 2) }}</div>
            </div>
        </div>

        <p>El PDF con la tabla de pagos completa está adjunto a este correo.</p>

        <p class="note">
            Esta cotización tiene validez de {{ $validezDias }} días a partir del {{ $fechaGeneracion }}.
            Los precios pueden cambiar sin previo aviso. Sujeto a disponibilidad.
        </p>
    </div>
    <div class="footer">
        {{ $empresa['nombre'] }}
        @if($empresa['telefono']) &nbsp;·&nbsp; {{ $empresa['telefono'] }} @endif
        @if($empresa['direccion']) &nbsp;·&nbsp; {{ $empresa['direccion'] }} @endif
    </div>
</div>
</body>
</html>
