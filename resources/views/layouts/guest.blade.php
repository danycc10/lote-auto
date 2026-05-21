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

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    @stack('head')
</head>

<body class="font-sans antialiased overflow-x-hidden">
    {{ $slot }}

    @livewireScripts
    @stack('scripts')
</body>
</html>
