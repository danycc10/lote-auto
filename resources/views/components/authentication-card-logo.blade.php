@php
    $logoPath = \App\Models\Configuracion::obtener('branding.logo_url', '');
    $logoSrc  = $logoPath ? url(\Illuminate\Support\Facades\Storage::url($logoPath)) : null;
@endphp

<a href="/" class="flex items-center justify-center">
    @if($logoSrc)
        <img src="{{ $logoSrc }}" alt="{{ config('app.name') }}"
             class="max-h-16 max-w-[200px] object-contain">
    @else
        <span class="text-xl font-bold text-indigo-600">{{ config('app.name') }}</span>
    @endif
</a>
