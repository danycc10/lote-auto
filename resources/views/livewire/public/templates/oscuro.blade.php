<div class="bg-[#06091a] text-white overflow-x-hidden" style="background-color:#06091a">

    <x-public-navbar :whatsapp="$whatsapp" />

    @if($anuncioActivo && $anuncioTexto)
    <div class="w-full py-2 px-4 text-center text-xs font-semibold text-white"
         style="background-color: var(--color-primario)">
        {{ $anuncioTexto }}
    </div>
    @endif

    {{-- HERO --}}
    <section id="inicio" class="relative min-h-screen flex items-center overflow-hidden pt-[68px]" aria-labelledby="hero-heading">
        <div class="absolute inset-0 bg-[#06091a] bg-dot-grid"></div>
        <div class="absolute -left-40 -top-40 h-[600px] w-[600px] rounded-full bg-blue-600/20 blur-[130px] pointer-events-none"></div>
        <div class="absolute -right-20 bottom-0 h-[400px] w-[400px] rounded-full bg-emerald-600/15 blur-[100px] pointer-events-none"></div>
        <div class="absolute right-1/4 top-1/3 h-[200px] w-[200px] rounded-full bg-blue-400/8 blur-[80px] pointer-events-none"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div>
                <div class="inline-flex items-center gap-2.5 rounded-full border border-emerald-500/25 bg-emerald-500/10 px-4 py-2 text-sm font-semibold text-emerald-300">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span></span>
                    {{ $badgeHero }}
                </div>
                <h1 id="hero-heading" class="mt-6 text-5xl sm:text-6xl lg:text-[4.25rem] font-black leading-[0.93] tracking-tight">
                    {{ $heroTitulo }}<br>
                    <span class="bg-clip-text text-transparent" style="background-image: linear-gradient(to right, var(--color-primario), var(--color-secundario))">{{ $heroAcento }}</span>
                </h1>
                <p class="mt-6 max-w-lg text-lg text-slate-300 leading-relaxed">{{ $heroDescripcion }}</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="#autos" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-7 py-4 text-base font-bold text-slate-950 shadow-xl transition hover:bg-slate-100 active:scale-[0.97]">
                        {{ $ctaHeroPrimario }}
                        <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="{{ $waCotizar }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2.5 rounded-xl border border-white/15 bg-white/[0.07] px-7 py-4 text-base font-bold text-white backdrop-blur-sm transition hover:bg-white/12 active:scale-[0.97]">
                        <svg class="h-5 w-5 shrink-0 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>
                        {{ $ctaHeroSecundario }}
                    </a>
                </div>
                <div class="mt-10 hidden sm:grid grid-cols-3 gap-3 max-w-lg">
                    @foreach([['value'=>$stat1Valor,'label'=>$stat1Label],['value'=>$stat2Valor,'label'=>$stat2Label],['value'=>$stat3Valor,'label'=>$stat3Label]] as $stat)
                    <div class="rounded-xl border border-white/[0.08] bg-white/[0.04] p-4">
                        <p class="text-2xl font-black tabular-nums">{{ $stat['value'] }}</p>
                        <p class="mt-1 text-xs text-slate-400 leading-snug">{{ $stat['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($heroAutos->count())
            <div x-data="{ active:0, total:{{ $heroAutos->count() }}, timer:null, start(){ this.timer=setInterval(()=>this.next(),4500) }, next(){ this.active=(this.active+1)%this.total }, prev(){ this.active=(this.active-1+this.total)%this.total } }" x-init="start()" class="relative">
                <div class="absolute -inset-6 rounded-[3rem] bg-blue-500/15 blur-3xl pointer-events-none"></div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/[0.04] shadow-2xl backdrop-blur-sm">
                    <div class="relative aspect-[16/10] lg:aspect-[4/3] max-h-[500px] overflow-hidden">
                        @foreach($heroAutos as $index => $heroAuto)
                        @php
                            $hm = $heroAuto->marca ?? null; $hmo = $heroAuto->modelo ?? null;
                            $hmn = is_object($hm) ? ($hm->nombre ?? '') : ($hm ?? '');
                            $hmon = is_object($hmo) ? ($hmo->nombre ?? '') : ($hmo ?? '');
                            $htit = trim($hmn.' '.$hmon) ?: 'Auto disponible';
                            $him = $heroAuto->imagenPortada?->ruta ?? $heroAuto->imagenes?->first()?->ruta ?? null;
                            $hiUrl = null;
                            if ($him) {
                                if (\Illuminate\Support\Str::startsWith($him,['http://','https://'])) $hiUrl=$him;
                                elseif (\Illuminate\Support\Str::startsWith($him,['storage/'])) $hiUrl=asset($him);
                                elseif (\Illuminate\Support\Str::startsWith($him,['/storage/'])) $hiUrl=asset(ltrim($him,'/'));
                                elseif (\Illuminate\Support\Str::startsWith($him,['public/'])) $hiUrl=asset('storage/'.\Illuminate\Support\Str::after($him,'public/'));
                                else $hiUrl=asset('storage/'.$him);
                            }
                            $hprecio = $heroAuto->precio_venta ?? $heroAuto->precio_contado ?? $heroAuto->precio_financiado ?? 0;
                        @endphp
                        @if($hiUrl)
                        <div x-show="active==={{ $index }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-[1.04]" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-400" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0">
                            <img src="{{ $hiUrl }}" alt="{{ $htit }}" class="h-full w-full object-cover" loading="eager">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
                                <div class="flex items-end justify-between gap-4">
                                    <div class="min-w-0">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/90 backdrop-blur-sm px-3 py-1 text-xs font-bold text-white"><svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg> Disponible</span>
                                        <h3 class="mt-2 text-2xl sm:text-3xl font-black text-white truncate drop-shadow">{{ $htit }}</h3>
                                        <p class="mt-1 text-sm text-slate-300">{{ $heroAuto->anio ?? '' }}@if(!empty($heroAuto->transmision)) · {{ ucfirst($heroAuto->transmision) }}@endif</p>
                                    </div>
                                    @if($hprecio)
                                    <div class="hidden sm:block shrink-0 rounded-xl bg-black/50 backdrop-blur-md border border-white/10 p-4 text-right">
                                        <p class="text-xs text-slate-400">Precio</p>
                                        <p class="text-xl font-black text-white tabular-nums">${{ number_format((float)$hprecio,0) }}</p>
                                        <p class="mt-1 text-xs text-slate-400">{{ number_format((float)($heroAuto->kilometraje??0)) }} km</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        <button type="button" @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 h-10 w-10 rounded-full bg-black/40 backdrop-blur-sm text-white flex items-center justify-center border border-white/10 transition hover:bg-black/60 active:scale-90 z-10" aria-label="Anterior">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/></svg>
                        </button>
                        <button type="button" @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 h-10 w-10 rounded-full bg-black/40 backdrop-blur-sm text-white flex items-center justify-center border border-white/10 transition hover:bg-black/60 active:scale-90 z-10" aria-label="Siguiente">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                        </button>
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-10">
                            @foreach($heroAutos as $index => $dot)
                            <button type="button" @click="active={{ $index }}" :class="active==={{ $index }} ? 'bg-white w-7' : 'bg-white/40 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 hidden md:flex flex-col items-center gap-2 text-slate-500 select-none">
            <span class="text-[10px] font-semibold tracking-[0.22em] uppercase">Explorar</span>
            <svg class="h-5 w-5 animate-bounce" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd"/></svg>
        </div>
    </section>

    {{-- TRUST BAR --}}
    <div class="border-y border-white/[0.06] bg-slate-950/80 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-white/[0.06]">
                @foreach([
                    ['value'=>'200+','label'=>'Autos entregados','icon'=>'<path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>','color'=>'text-emerald-400'],
                    ['value'=>'24h','label'=>'Respuesta garantizada','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>','color'=>'text-blue-400'],
                    ['value'=>'100%','label'=>'Proceso transparente','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>','color'=>'text-amber-400'],
                    ['value'=>'0','label'=>'Letra chica','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>','color'=>'text-violet-400'],
                ] as $m)
                <div class="flex items-center gap-3 py-4 md:py-3 px-4 md:px-6">
                    <div class="shrink-0 h-10 w-10 rounded-xl bg-white/[0.06] flex items-center justify-center">
                        <svg class="h-5 w-5 {{ $m['color'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">{!! $m['icon'] !!}</svg>
                    </div>
                    <div><p class="text-xl font-black tabular-nums leading-none">{{ $m['value'] }}</p><p class="text-xs text-slate-400 mt-1">{{ $m['label'] }}</p></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- BENEFICIOS --}}
    <section id="financiamiento" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center mb-14">
            <p class="text-xs font-semibold tracking-[0.22em] uppercase text-emerald-400">{{ $beneficiosEyebrow }}</p>
            <h2 class="mt-3 text-4xl md:text-5xl font-black tracking-tight">{{ $beneficiosTitulo }}</h2>
            <p class="mt-4 text-slate-400 max-w-xl mx-auto text-lg">{{ $beneficiosSubtitulo }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($beneficios as $b)
            <div class="group relative overflow-hidden rounded-2xl border border-white/[0.08] bg-white/[0.03] p-7 transition-all hover:border-white/[0.15] hover:bg-white/[0.05]">
                <div class="h-14 w-14 rounded-xl bg-white/[0.06] border border-white/8 flex items-center justify-center">
                    <svg class="h-7 w-7 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">{!! $b['icon'] !!}</svg>
                </div>
                <h3 class="mt-5 text-xl font-bold text-white">{{ $b['titulo'] }}</h3>
                <p class="mt-2 text-slate-400 leading-relaxed">{{ $b['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- PROCESO --}}
    <section id="proceso" class="relative overflow-hidden py-24">
        <div class="absolute inset-0 bg-slate-950/60"></div>
        <div class="absolute inset-0 bg-dot-grid opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-xs font-semibold tracking-[0.22em] uppercase text-emerald-400">{{ $procesoEyebrow }}</p>
                <h2 class="mt-3 text-4xl md:text-5xl font-black tracking-tight">{{ $procesoTitulo }}</h2>
                <p class="mt-4 text-slate-400 max-w-xl mx-auto">{{ $procesoSubtitulo }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($pasos as $i => $step)
                <div class="relative z-10 rounded-2xl border border-white/[0.08] bg-white/[0.03] p-6 text-center transition hover:border-white/[0.15] hover:bg-white/[0.05]">
                    <div class="mx-auto h-14 w-14 rounded-xl bg-gradient-to-br {{ ['from-blue-600 to-blue-400','from-emerald-600 to-emerald-400','from-amber-600 to-amber-400','from-violet-600 to-violet-400'][$i] }} flex items-center justify-center shadow-lg">
                        <svg class="h-7 w-7 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">{!! $step['icon'] !!}</svg>
                    </div>
                    <div class="mt-1 text-[10px] font-black tracking-widest text-slate-600 uppercase">Paso {{ $step['num'] }}</div>
                    <h3 class="mt-3 text-lg font-bold text-white">{{ $step['titulo'] }}</h3>
                    <p class="mt-2 text-sm text-slate-400 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AUTOS --}}
    <section id="autos" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-12">
            <div>
                <p class="text-xs font-semibold tracking-[0.22em] uppercase text-emerald-400">{{ $autosEyebrow }}</p>
                <h2 class="mt-3 text-4xl md:text-5xl font-black tracking-tight">{{ $autosTitulo }}</h2>
                <p class="mt-3 text-slate-400 max-w-xl leading-relaxed">{{ $autosDescripcion }}</p>
            </div>
            <a href="{{ $catalogoUrl }}" class="shrink-0 inline-flex items-center justify-center gap-2 rounded-xl border border-white/15 bg-white/[0.06] px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-white/10">
                Ver catálogo completo
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
            </a>
        </div>
        @if($autosDestacados->count())
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($autosDestacados as $auto)
            @php
                $mn = $auto->marca ?? null; $mo = $auto->modelo ?? null;
                $mnombre = is_object($mn) ? ($mn->nombre ?? '') : ($mn ?? '');
                $monombre = is_object($mo) ? ($mo->nombre ?? '') : ($mo ?? '');
                $tituloA = trim(($mnombre?:'Marca').' '.($monombre?:'Modelo'));
                $precioC = (float)($auto->precio_contado ?? $auto->precio_venta ?? $auto->precio ?? 0);
                $precioF = (float)($auto->precio_financiado ?? 0);
                $precioM = $precioC ?: $precioF;
                $img = $auto->imagenPortada?->ruta ?? null; $imgUrl = null;
                if ($img) {
                    if (\Illuminate\Support\Str::startsWith($img,['http://','https://'])) $imgUrl=$img;
                    elseif (\Illuminate\Support\Str::startsWith($img,['storage/'])) $imgUrl=asset($img);
                    elseif (\Illuminate\Support\Str::startsWith($img,['/storage/'])) $imgUrl=asset(ltrim($img,'/'));
                    elseif (\Illuminate\Support\Str::startsWith($img,['public/'])) $imgUrl=asset('storage/'.\Illuminate\Support\Str::after($img,'public/'));
                    else $imgUrl=asset('storage/'.$img);
                }
                $msgWa = $waBase.urlencode('Hola, me interesa el '.$tituloA.' '.($auto->anio??''));
                $detUrl = route('public.autos.show', $auto->uuid);
            @endphp
            <article class="group overflow-hidden rounded-2xl border border-white/[0.08] bg-[#0e1725]">
                <a href="{{ $detUrl }}" class="block relative aspect-[16/10] overflow-hidden bg-slate-900">
                    @if($imgUrl)
                    <img src="{{ $imgUrl }}" alt="{{ $tituloA }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0e1725]/70 via-transparent to-transparent"></div>
                    @else
                    <div class="h-full w-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                        <svg class="h-12 w-12 text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    </div>
                    @endif
                    <div class="absolute left-3 top-3">
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/90 backdrop-blur-sm px-3 py-1 text-xs font-bold text-white">
                            <span class="h-1.5 w-1.5 rounded-full bg-white"></span>Disponible
                        </span>
                    </div>
                </a>
                <div class="p-5">
                    <p class="text-xs text-slate-500 font-medium">{{ $auto->anio ?? '' }}</p>
                    <a href="{{ $detUrl }}" class="block mt-1 group/t"><h3 class="text-xl font-bold text-white leading-tight group-hover/t:text-blue-300 transition-colors">{{ $tituloA }}</h3></a>
                    <div class="mt-5 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-semibold tracking-[0.15em] uppercase text-slate-500">Precio</p>
                            <p class="text-2xl font-black text-white tabular-nums">${{ number_format($precioM,0) }}</p>
                            @if($precioF > 0 && $precioF !== $precioC)<p class="mt-1 text-sm text-emerald-400 font-semibold">Financiado: ${{ number_format($precioF,0) }}</p>@endif
                        </div>
                        <div class="shrink-0 rounded-xl bg-white/[0.06] border border-white/[0.07] px-3 py-2 text-right">
                            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wide">Km</p>
                            <p class="text-sm font-bold text-slate-200 tabular-nums">{{ number_format((float)($auto->kilometraje??0)) }}</p>
                        </div>
                    </div>
                    @if($auto->transmision || $auto->color || $auto->tipo_combustible)
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach(array_filter([$auto->transmision,$auto->color,$auto->tipo_combustible]) as $chip)
                        <span class="rounded-lg bg-white/[0.06] border border-white/[0.07] px-3 py-1.5 text-xs text-slate-300">{{ ucfirst($chip) }}</span>
                        @endforeach
                    </div>
                    @endif
                    <div class="mt-5 flex gap-2">
                        <a href="{{ $detUrl }}" class="flex-1 flex items-center justify-center gap-1.5 rounded-xl border border-white/[0.12] bg-white/[0.05] px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Ver detalles</a>
                        <a href="{{ $msgWa }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-1.5 rounded-xl px-4 py-3 text-sm font-bold text-white transition hover:opacity-90" style="background-color: var(--color-secundario)">
                            <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        <div class="mt-10 text-center md:hidden">
            <a href="{{ $catalogoUrl }}" class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/[0.06] px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-white/10">Ver catálogo completo</a>
        </div>
        @else
        <div class="rounded-2xl border border-dashed border-white/[0.1] bg-white/[0.02] p-16 text-center">
            <h3 class="text-xl font-bold text-white">Pronto habrá autos disponibles</h3>
            <p class="mt-2 text-slate-400">Registra autos en el sistema y aparecerán aquí automáticamente.</p>
        </div>
        @endif
    </section>

    {{-- CTA BANNER --}}
    <section class="relative overflow-hidden py-20 sm:py-28">
        <div class="absolute inset-0 bg-gradient-to-br from-[#06091a] via-blue-950/40 to-[#06091a]"></div>
        <div class="absolute inset-0" style="background:radial-gradient(ellipse 80% 60% at 50% 50%,rgba(16,185,129,.12) 0%,transparent 70%)"></div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <p class="text-xs font-semibold tracking-[0.22em] uppercase text-emerald-400">{{ $ctaEyebrow }}</p>
            <h2 class="mt-4 text-4xl sm:text-5xl font-black tracking-tight">{{ $ctaTitulo }}<br class="hidden sm:block"><span class="text-slate-400">{{ $ctaSubtitulo }}</span></h2>
            <p class="mt-5 text-lg text-slate-300 leading-relaxed">{{ $ctaDescripcion }}</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                @foreach(array_filter([$trust1,$trust2,$trust3,$trust4]) as $badge)
                <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/[0.06] px-4 py-1.5 text-sm text-slate-300">
                    <svg class="h-3.5 w-3.5 shrink-0 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                    {{ $badge }}
                </span>
                @endforeach
            </div>
            <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="#autos" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-8 py-4 text-base font-bold text-slate-950 shadow-xl transition hover:bg-slate-100">{{ $ctaHeroPrimario }}</a>
                <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-8 py-4 text-base font-bold text-white shadow-xl transition hover:opacity-90" style="background-color: var(--color-secundario)">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>
                    Cotizar ahora
                </a>
            </div>
        </div>
    </section>

    {{-- CONTACTO --}}
    <section id="contacto" class="relative overflow-hidden bg-slate-950 py-24">
        <div class="absolute inset-0 bg-dot-grid opacity-40"></div>
        <div class="absolute right-0 top-0 h-[350px] w-[350px] rounded-full bg-emerald-600/10 blur-[100px] pointer-events-none"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <div>
                    <p class="text-xs font-semibold tracking-[0.22em] uppercase text-emerald-400">Contacto</p>
                    <h2 class="mt-3 text-4xl md:text-5xl font-black tracking-tight">{{ $contactoTitulo }}<br><span class="text-slate-400">{{ $contactoSubtitulo }}</span></h2>
                    <p class="mt-5 text-lg text-slate-300 leading-relaxed max-w-md">{{ $contactoDescripcion }}</p>
                    <div class="mt-8 space-y-3">
                        <div class="flex items-center gap-4 rounded-xl border border-white/[0.08] bg-white/[0.03] p-4">
                            <div class="shrink-0 h-11 w-11 rounded-xl bg-emerald-500/15 flex items-center justify-center"><svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg></div>
                            <div><p class="text-xs text-slate-500 font-medium uppercase tracking-wide">WhatsApp</p><p class="text-base font-bold text-white">+52 {{ $whatsapp }}</p></div>
                        </div>
                        <div class="flex items-center gap-4 rounded-xl border border-white/[0.08] bg-white/[0.03] p-4">
                            <div class="shrink-0 h-11 w-11 rounded-xl bg-blue-500/15 flex items-center justify-center"><svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                            <div><p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Horario</p><p class="text-base font-bold text-white">{{ $horario }}</p></div>
                        </div>
                        <div class="flex items-center gap-4 rounded-xl border border-white/[0.08] bg-white/[0.03] p-4">
                            <div class="shrink-0 h-11 w-11 rounded-xl bg-violet-500/15 flex items-center justify-center"><svg class="h-5 w-5 text-violet-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></div>
                            <div><p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Ubicación</p><p class="text-base font-bold text-white">{{ $direccion }}</p></div>
                        </div>
                    </div>
                    <div class="mt-6 space-y-3">
                        <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="flex w-full items-center gap-4 rounded-xl px-6 py-4 font-bold text-white transition hover:opacity-90 group" style="background-color: var(--color-secundario)">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-white/20 flex items-center justify-center"><svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg></div>
                            <div><p class="font-bold leading-none">Información general</p><p class="mt-0.5 text-sm text-emerald-100 font-normal">Sobre autos disponibles</p></div>
                            <svg class="h-5 w-5 ml-auto shrink-0 transition group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                        </a>
                        <a href="{{ $waCotizar }}" target="_blank" rel="noopener noreferrer" class="flex w-full items-center gap-4 rounded-xl border border-white/[0.1] bg-white/[0.05] px-6 py-4 font-bold text-white transition hover:bg-white/10 group">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-white/10 flex items-center justify-center"><svg class="h-5 w-5 text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg></div>
                            <div><p class="font-bold leading-none">Cotizar mi auto</p><p class="mt-0.5 text-sm text-slate-400 font-normal">Planes de financiamiento</p></div>
                            <svg class="h-5 w-5 ml-auto shrink-0 text-slate-500 transition group-hover:translate-x-1 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                        </a>
                    </div>
                </div>
                <livewire:public.formulario-contacto />
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-[#04070f] border-t border-white/[0.06]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <a href="{{ $homeUrl }}" class="inline-flex items-center gap-3">
                        @if($logoUrl)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($logoUrl) }}" alt="{{ config('app.name') }}" class="h-10 w-auto max-w-[120px] object-contain">
                        @else
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(to bottom right, var(--color-primario), var(--color-secundario))">
                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/><path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/><path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/></svg>
                        </div>
                        @endif
                        <div>
                            <p class="font-black text-white text-[15px] leading-none">{{ config('app.name','AutoLote') }}</p>
                            <p class="text-xs mt-0.5" style="color: var(--color-secundario);opacity:.75">{{ $tagline }}</p>
                        </div>
                    </a>
                    <p class="mt-4 text-sm text-slate-500 leading-relaxed">{{ $descripcionFooter }}</p>
                    <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="mt-5 inline-flex items-center gap-2.5 rounded-xl border border-white/10 bg-white/[0.05] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">
                        <svg class="h-4 w-4 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>
                        Escríbenos
                    </a>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-[0.18em] uppercase text-slate-500 mb-4">Navegación</p>
                    <ul class="space-y-2">
                        @foreach([['#inicio','Inicio'],['#financiamiento','Financiamiento'],['#proceso','Proceso'],['#autos','Autos'],['#contacto','Contacto'],[$catalogoUrl,'Ver catálogo']] as $link)
                        <li><a href="{{ $link[0] }}" class="text-sm text-slate-400 hover:text-white transition">{{ $link[1] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-[0.18em] uppercase text-slate-500 mb-4">Contacto</p>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li class="flex items-start gap-2"><svg class="h-4 w-4 mt-0.5 shrink-0 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>+52 {{ $whatsapp }}</li>
                        <li class="flex items-start gap-2"><svg class="h-4 w-4 mt-0.5 shrink-0 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $horario }}</li>
                        <li class="flex items-start gap-2"><svg class="h-4 w-4 mt-0.5 shrink-0 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>{{ $direccion }}</li>
                    </ul>
                </div>
            </div>
            <div class="mt-10 pt-8 border-t border-white/[0.06] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-600">
                <p>&copy; {{ date('Y') }} {{ config('app.name','AutoLote') }}. Todos los derechos reservados.</p>
                <p>{{ $tagline }}</p>
            </div>
        </div>
    </footer>
</div>
