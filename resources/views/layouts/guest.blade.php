<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') — {{ config('app.name') }}@else{{ config('app.name', 'AutoLote') }} · Autos Financiados @endif</title>

    <meta name="description" content="@yield('meta_description', 'Encuentra tu auto ideal con planes de financiamiento accesibles. Proceso claro, respuesta en 24 horas. ' . config('app.name'))">
    <meta name="robots" content="index, follow">

    {{-- Open Graph --}}
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="{{ config('app.name') }}">
    <meta property="og:title"       content="@yield('og_title', config('app.name') . ' · Autos Financiados')">
    <meta property="og:description" content="@yield('og_description', 'Encuentra tu auto ideal con planes de financiamiento accesibles. Proceso claro, respuesta en 24 horas.')">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:image"       content="@yield('og_image', asset('favicon.ico'))">

    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Structured Data: AutoDealer --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'AutoDealer',
        'name'     => config('app.name'),
        'url'      => url('/'),
        'description' => 'Autos seminuevos con financiamiento directo. Sin buró de crédito. Proceso claro y rápido.',
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet">

    @php
        $colorPrimario   = \App\Models\Configuracion::obtener('branding.color_primario',  '#3b82f6');
        $colorSecundario = \App\Models\Configuracion::obtener('branding.color_secundario', '#10b981');
        $gtmId   = \App\Models\Configuracion::obtener('branding.gtm_id',   '');
        $gaId    = \App\Models\Configuracion::obtener('branding.ga_id',    '');
        $pixelId = \App\Models\Configuracion::obtener('branding.pixel_id', '');
    @endphp
    <style>:root{--color-primario:{{ $colorPrimario }};--color-secundario:{{ $colorSecundario }};}</style>

    {{-- Google Tag Manager --}}
    @if($gtmId)
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ e($gtmId) }}');</script>
    @endif

    {{-- Google Analytics (only if GTM is not configured) --}}
    @if($gaId && !$gtmId)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ e($gaId) }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ e($gaId) }}');</script>
    @endif

    {{-- Meta / Facebook Pixel --}}
    @if($pixelId)
    <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{{ e($pixelId) }}');fbq('track','PageView');</script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ e($pixelId) }}&ev=PageView&noscript=1" alt=""/></noscript>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    @stack('head')
</head>

<body class="font-sans antialiased overflow-x-hidden">
    {{-- Google Tag Manager (noscript) --}}
    @if($gtmId)
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ e($gtmId) }}" height="0" width="0" style="display:none;visibility:hidden" title="GTM"></iframe></noscript>
    @endif

    {{ $slot }}

    @livewireScripts
    @stack('scripts')
</body>
</html>
