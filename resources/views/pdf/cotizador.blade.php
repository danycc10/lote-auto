<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Cotización</title>
<style>
@page { margin: 24px 28px; }
body  { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1e293b; margin: 0; }

/* Header */
.header          { display: table; width: 100%; margin-bottom: 14px; border-bottom: 2px solid #6366f1; padding-bottom: 10px; }
.header-logo     { display: table-cell; vertical-align: middle; width: 90px; }
.header-logo img { max-width: 80px; max-height: 50px; }
.header-info     { display: table-cell; vertical-align: middle; padding-left: 12px; }
.header-info h1  { margin: 0 0 2px; font-size: 16px; color: #4f46e5; }
.header-info p   { margin: 1px 0; color: #64748b; font-size: 9px; }
.header-right    { display: table-cell; vertical-align: middle; text-align: right; width: 160px; }
.header-right p  { margin: 1px 0; color: #64748b; font-size: 9px; }
.badge           { display: inline-block; background: #6366f1; color: #fff; border-radius: 4px; padding: 3px 8px; font-size: 10px; font-weight: bold; }

/* Datos auto y cliente */
.two-col          { display: table; width: 100%; margin-bottom: 10px; border-spacing: 6px 0; }
.col              { display: table-cell; vertical-align: top; }
.box              { border: 1px solid #e2e8f0; border-radius: 6px; padding: 9px 11px; background: #f8fafc; }
.box-title        { font-size: 8px; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
.box h2           { margin: 0 0 3px; font-size: 13px; color: #1e293b; }
.box p            { margin: 2px 0; color: #475569; }

/* Resumen financiero */
.resumen          { display: table; width: 100%; margin-bottom: 12px; border-spacing: 6px 0; }
.kpi              { display: table-cell; text-align: center; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 6px; }
.kpi-label        { font-size: 8px; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; }
.kpi-value        { font-size: 15px; font-weight: bold; color: #4f46e5; }
.kpi-value.big    { font-size: 18px; }
.kpi-value.green  { color: #16a34a; }
.kpi-value.gray   { color: #475569; }

/* Tabla amortización */
.section-title    { font-size: 9px; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin: 10px 0 5px; }
table             { width: 100%; border-collapse: collapse; font-size: 9px; }
th                { background: #6366f1; color: #fff; padding: 5px 6px; text-align: right; font-weight: bold; }
th:first-child, th:nth-child(2) { text-align: center; }
td                { padding: 4px 6px; border-bottom: 1px solid #f1f5f9; text-align: right; }
td:first-child    { text-align: center; color: #64748b; }
td:nth-child(2)   { text-align: center; }
tr:nth-child(even) td { background: #f8fafc; }
tr:last-child td  { font-weight: bold; border-bottom: 2px solid #6366f1; }

/* Footer */
.footer           { margin-top: 14px; border-top: 1px solid #e2e8f0; padding-top: 8px; color: #94a3b8; font-size: 8px; text-align: center; }
</style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <div class="header-logo">
        @if($empresa['logo'])
        <img src="{{ storage_path('app/public/' . ltrim($empresa['logo'], 'storage/')) }}" alt="logo">
        @endif
    </div>
    <div class="header-info">
        <h1>{{ $empresa['nombre'] }}</h1>
        @if($empresa['direccion'])<p>{{ $empresa['direccion'] }}</p>@endif
        @if($empresa['horario'])<p>{{ $empresa['horario'] }}</p>@endif
        @if($empresa['telefono'])<p>Tel/WhatsApp: {{ $empresa['telefono'] }}</p>@endif
    </div>
    <div class="header-right">
        <p><span class="badge">COTIZACIÓN</span></p>
        <p style="margin-top:5px;">Fecha: {{ $fechaGeneracion }}</p>
        <p>Válida por {{ $validezDias }} días</p>
        @if($nombreCliente)<p style="margin-top:4px; font-weight:bold; color:#1e293b;">{{ $nombreCliente }}</p>@endif
    </div>
</div>

{{-- Auto --}}
<div class="two-col">
    <div class="col" style="width:65%">
        <div class="box">
            <div class="box-title">Vehículo</div>
            <h2>{{ $auto->marca?->nombre ?? '' }} {{ $auto->modelo?->nombre ?? '' }} {{ $auto->anio }}</h2>
            @if($auto->placas)<p>Placas: <strong>{{ $auto->placas }}</strong></p>@endif
            @if($auto->vin)<p>VIN: {{ $auto->vin }}</p>@endif
            @if($auto->color)<p>Color: {{ $auto->color }}</p>@endif
            @if($auto->kilometraje)<p>Kilometraje: {{ number_format($auto->kilometraje) }} km</p>@endif
        </div>
    </div>
    <div class="col" style="width:35%">
        <div class="box" style="background:#eef2ff; border-color:#c7d2fe;">
            <div class="box-title" style="color:#6366f1;">Precio de venta</div>
            <h2 style="font-size:18px; color:#4f46e5;">${{ number_format((float)$auto->precio_financiado, 2) }}</h2>
            @if($auto->precio_contado && $auto->precio_contado != $auto->precio_financiado)
            <p style="color:#94a3b8;">Contado: ${{ number_format((float)$auto->precio_contado, 2) }}</p>
            @endif
        </div>
    </div>
</div>

{{-- KPIs --}}
<div class="resumen">
    <div class="kpi">
        <div class="kpi-label">Enganche</div>
        <div class="kpi-value gray">${{ number_format($enganche, 2) }}</div>
    </div>
    <div class="kpi">
        <div class="kpi-label">Monto financiado</div>
        <div class="kpi-value gray">${{ number_format($montoFinanciado, 2) }}</div>
    </div>
    <div class="kpi">
        <div class="kpi-label">Plazo</div>
        <div class="kpi-value gray">{{ $plazo }} meses</div>
    </div>
    @if($tasaAnual > 0)
    <div class="kpi">
        <div class="kpi-label">Tasa anual</div>
        <div class="kpi-value gray">{{ number_format($tasaAnual, 2) }}%</div>
    </div>
    @endif
    <div class="kpi" style="background:#eef2ff; border-color:#c7d2fe;">
        <div class="kpi-label" style="color:#6366f1;">Mensualidad</div>
        <div class="kpi-value big">${{ number_format($cuotaMensual, 2) }}</div>
    </div>
    <div class="kpi">
        <div class="kpi-label">Total a pagar</div>
        <div class="kpi-value green">${{ number_format($totalPagar, 2) }}</div>
    </div>
</div>

{{-- Tabla amortización --}}
<div class="section-title">Tabla de pagos</div>
<table>
    <thead>
        <tr>
            <th style="text-align:center; width:30px;">#</th>
            <th style="text-align:center; width:60px;">Fecha</th>
            <th>Capital</th>
            @if($tasaAnual > 0)<th>Interés</th>@endif
            <th>Cuota</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tablaAmortizacion as $fila)
        <tr>
            <td>{{ $fila['numero'] }}</td>
            <td>{{ $fila['fecha'] }}</td>
            <td>${{ number_format($fila['capital'], 2) }}</td>
            @if($tasaAnual > 0)<td>${{ number_format($fila['interes'], 2) }}</td>@endif
            <td><strong>${{ number_format($fila['cuota'], 2) }}</strong></td>
            <td>${{ number_format($fila['saldo'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Esta cotización es informativa y tiene una vigencia de {{ $validezDias }} días a partir del {{ $fechaGeneracion }}.
    Los precios pueden cambiar sin previo aviso. Sujeto a disponibilidad.
</div>

</body>
</html>
