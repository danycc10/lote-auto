{{-- BOLD: Negro + Naranja/Ámbar, tipografía masiva, alto contraste --}}
<div class="bg-[#0a0a0a] text-white overflow-x-hidden">

    <x-public-navbar :whatsapp="$whatsapp" />

    @if($anuncioActivo && $anuncioTexto)
    <div class="w-full py-2 px-4 text-center text-xs font-semibold text-black bg-amber-400">{{ $anuncioTexto }}</div>
    @endif

    {{-- HERO --}}
    <section id="inicio" class="relative min-h-screen flex items-center pt-[68px] overflow-hidden">
        {{-- Diagonal accent --}}
        <div class="absolute inset-0 bg-[#0a0a0a]"></div>
        <div class="absolute right-0 top-0 w-1/2 h-full bg-amber-500/5 clip-diagonal pointer-events-none"></div>
        <div class="absolute top-20 right-20 w-[500px] h-[500px] rounded-full bg-amber-500/10 blur-[120px] pointer-events-none"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 bg-amber-500 text-black font-black text-xs px-3 py-1.5 rounded uppercase tracking-wider">
                    <span class="h-2 w-2 rounded-full bg-black animate-ping"></span>
                    {{ $badgeHero }}
                </div>
                <h1 class="mt-6 text-6xl sm:text-7xl lg:text-8xl font-black leading-[0.85] tracking-tighter uppercase">
                    {{ $heroTitulo }}<br>
                    <span class="text-amber-400">{{ $heroAcento }}</span>
                </h1>
                <p class="mt-8 text-lg text-zinc-400 leading-relaxed max-w-lg">{{ $heroDescripcion }}</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="#autos" class="inline-flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-black font-black px-8 py-4 text-base rounded uppercase tracking-wide transition active:scale-[0.97]">
                        {{ $ctaHeroPrimario }}
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="{{ $waCotizar }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 border border-zinc-700 hover:border-zinc-500 text-white font-black px-8 py-4 text-base rounded uppercase tracking-wide transition">
                        <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>
                        WhatsApp
                    </a>
                </div>
                <div class="mt-12 flex gap-8 border-t border-zinc-800 pt-8">
                    @foreach([[$stat1Valor,$stat1Label],[$stat2Valor,$stat2Label],[$stat3Valor,$stat3Label]] as $s)
                    <div>
                        <p class="text-3xl font-black text-amber-400">{{ $s[0] }}</p>
                        <p class="text-xs text-zinc-500 mt-1 uppercase tracking-wide">{{ $s[1] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($heroAutos->count())
            <div x-data="{ active:0, total:{{ $heroAutos->count() }}, start(){ setInterval(()=>{ this.active=(this.active+1)%this.total },5000) } }" x-init="start()" class="relative">
                <div class="overflow-hidden rounded border-2 border-zinc-800">
                    <div class="relative aspect-[4/3]">
                        @foreach($heroAutos as $index => $ha)
                        @php
                            $hm4=$ha->marca??null;$hmo4=$ha->modelo??null;
                            $hmn4=is_object($hm4)?($hm4->nombre??''):($hm4??'');
                            $hmon4=is_object($hmo4)?($hmo4->nombre??''):($hmo4??'');
                            $htit4=trim($hmn4.' '.$hmon4)?:'Auto disponible';
                            $him4=$ha->imagenPortada?->ruta??$ha->imagenes?->first()?->ruta??null;
                            $hiUrl4=null;
                            if($him4){if(\Illuminate\Support\Str::startsWith($him4,['http://','https://']))$hiUrl4=$him4;elseif(\Illuminate\Support\Str::startsWith($him4,['storage/']))$hiUrl4=asset($him4);elseif(\Illuminate\Support\Str::startsWith($him4,['/storage/']))$hiUrl4=asset(ltrim($him4,'/'));elseif(\Illuminate\Support\Str::startsWith($him4,['public/']))$hiUrl4=asset('storage/'.\Illuminate\Support\Str::after($him4,'public/'));else $hiUrl4=asset('storage/'.$him4);}
                            $hprecio4=$ha->precio_venta??$ha->precio_contado??$ha->precio_financiado??0;
                        @endphp
                        @if($hiUrl4)
                        <div x-show="active==={{ $index }}" x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-300" x-transition:leave-end="opacity-0" class="absolute inset-0">
                            <img src="{{ $hiUrl4 }}" alt="{{ $htit4 }}" class="h-full w-full object-cover" loading="eager">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                            <div class="absolute bottom-0 inset-x-0 p-6">
                                <div class="inline-block bg-amber-400 text-black font-black text-xs px-2 py-0.5 rounded uppercase mb-2">Disponible</div>
                                <h3 class="text-2xl font-black text-white uppercase">{{ $htit4 }}</h3>
                                <p class="text-amber-400 font-black text-xl mt-1">@if($hprecio4) ${{ number_format((float)$hprecio4,0) }} @endif</p>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        <div class="absolute top-4 right-4 flex gap-2 z-10">
                            @foreach($heroAutos as $index => $dot)
                            <button @click="active={{ $index }}" :class="active==={{ $index }}?'bg-amber-400 w-6':'bg-white/30 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
                {{-- Decorative corner --}}
                <div class="absolute -bottom-3 -right-3 w-24 h-24 border-r-2 border-b-2 border-amber-500/50 pointer-events-none rounded-br"></div>
            </div>
            @endif
        </div>
    </section>

    {{-- TRUST --}}
    <div class="border-y border-zinc-800 bg-zinc-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap justify-around gap-4 py-2">
                @foreach([['200+','Autos entregados'],['24h','Respuesta'],['100%','Transparencia'],['0','Letra chica']] as $m)
                <div class="text-center px-4">
                    <p class="text-2xl font-black text-amber-400">{{ $m[0] }}</p>
                    <p class="text-xs text-zinc-500 uppercase tracking-wide mt-1">{{ $m[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- BENEFICIOS --}}
    <section id="financiamiento" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="mb-14">
            <p class="text-xs font-black tracking-[0.3em] uppercase text-amber-400">{{ $beneficiosEyebrow }}</p>
            <h2 class="mt-3 text-5xl md:text-6xl font-black uppercase tracking-tight leading-none">{{ $beneficiosTitulo }}</h2>
            <p class="mt-4 text-zinc-400 max-w-xl text-lg">{{ $beneficiosSubtitulo }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-1">
            @foreach($beneficios as $i => $b)
            <div class="border border-zinc-800 hover:border-amber-500/50 bg-zinc-900/30 hover:bg-zinc-900 p-8 transition-all group">
                <div class="text-4xl font-black text-amber-400/30 group-hover:text-amber-400 transition-colors mb-4">0{{ $i+1 }}</div>
                <h3 class="text-xl font-black text-white uppercase">{{ $b['titulo'] }}</h3>
                <p class="mt-3 text-zinc-500 leading-relaxed">{{ $b['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- PROCESO --}}
    <section id="proceso" class="py-24 bg-zinc-900/40 border-y border-zinc-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-14">
                <p class="text-xs font-black tracking-[0.3em] uppercase text-amber-400">{{ $procesoEyebrow }}</p>
                <h2 class="mt-3 text-5xl md:text-6xl font-black uppercase tracking-tight leading-none">{{ $procesoTitulo }}</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($pasos as $i => $step)
                <div class="relative pl-6 border-l border-zinc-700 hover:border-amber-500 transition-colors">
                    <div class="text-5xl font-black text-amber-400/20 leading-none mb-2">{{ $i+1 }}</div>
                    <h3 class="text-lg font-black text-white uppercase">{{ $step['titulo'] }}</h3>
                    <p class="mt-2 text-sm text-zinc-500">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AUTOS --}}
    <section id="autos" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-12">
            <div>
                <p class="text-xs font-black tracking-[0.3em] uppercase text-amber-400">{{ $autosEyebrow }}</p>
                <h2 class="mt-2 text-5xl md:text-6xl font-black uppercase tracking-tight leading-none">{{ $autosTitulo }}</h2>
            </div>
            <a href="{{ $catalogoUrl }}" class="shrink-0 inline-flex items-center gap-2 border border-zinc-700 hover:border-amber-500 px-6 py-3 text-sm font-black uppercase tracking-wide text-white transition">
                Ver todo <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
            </a>
        </div>
        @if($autosDestacados->count())
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($autosDestacados as $auto)
            @php
                $mn5=$auto->marca??null;$mo5=$auto->modelo??null;
                $mnombre5=is_object($mn5)?($mn5->nombre??''):($mn5??'');
                $monombre5=is_object($mo5)?($mo5->nombre??''):($mo5??'');
                $tituloA5=trim(($mnombre5?:'Marca').' '.($monombre5?:'Modelo'));
                $precioC5=(float)($auto->precio_contado??$auto->precio_venta??$auto->precio??0);
                $precioF5=(float)($auto->precio_financiado??0);
                $precioM5=$precioC5?:$precioF5;
                $img5=$auto->imagenPortada?->ruta??null;$imgUrl5=null;
                if($img5){if(\Illuminate\Support\Str::startsWith($img5,['http://','https://']))$imgUrl5=$img5;elseif(\Illuminate\Support\Str::startsWith($img5,['storage/']))$imgUrl5=asset($img5);elseif(\Illuminate\Support\Str::startsWith($img5,['/storage/']))$imgUrl5=asset(ltrim($img5,'/'));elseif(\Illuminate\Support\Str::startsWith($img5,['public/']))$imgUrl5=asset('storage/'.\Illuminate\Support\Str::after($img5,'public/'));else $imgUrl5=asset('storage/'.$img5);}
                $msgWa5=$waBase.urlencode('Hola, me interesa el '.$tituloA5.' '.($auto->anio??''));
                $detUrl5=route('public.autos.show',$auto->uuid);
            @endphp
            <article class="group border border-zinc-800 hover:border-amber-500/50 bg-zinc-900/30 transition-all overflow-hidden">
                <a href="{{ $detUrl5 }}" class="block relative aspect-[16/10] overflow-hidden">
                    @if($imgUrl5)
                    <img src="{{ $imgUrl5 }}" alt="{{ $tituloA5 }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0 bg-black/30"></div>
                    @else
                    <div class="h-full w-full bg-zinc-800 flex items-center justify-center"><svg class="h-10 w-10 text-zinc-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg></div>
                    @endif
                    <div class="absolute top-3 left-3 bg-amber-400 text-black font-black text-xs px-2 py-0.5 uppercase">Disponible</div>
                </a>
                <div class="p-5">
                    <h3 class="text-xl font-black text-white uppercase">{{ $tituloA5 }} <span class="text-zinc-600">{{ $auto->anio??'' }}</span></h3>
                    <p class="mt-3 text-2xl font-black text-amber-400">${{ number_format($precioM5,0) }}</p>
                    @if($precioF5>0&&$precioF5!==$precioC5)<p class="text-sm text-zinc-500">Financiado: ${{ number_format($precioF5,0) }}</p>@endif
                    <p class="text-xs text-zinc-600 mt-1 uppercase tracking-wide">{{ number_format((float)($auto->kilometraje??0)) }} km</p>
                    <div class="mt-4 flex gap-2">
                        <a href="{{ $detUrl5 }}" class="flex-1 flex items-center justify-center border border-zinc-700 hover:border-zinc-500 py-2.5 text-sm font-black uppercase tracking-wide text-white transition">Ver más</a>
                        <a href="{{ $msgWa5 }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-1.5 bg-amber-400 hover:bg-amber-300 py-2.5 text-sm font-black uppercase tracking-wide text-black transition">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>WA
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </section>

    {{-- CTA --}}
    <section class="py-24 border-y border-zinc-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <p class="text-xs font-black tracking-[0.3em] uppercase text-amber-400">{{ $ctaEyebrow }}</p>
            <h2 class="mt-4 text-5xl sm:text-7xl font-black uppercase tracking-tight leading-none">{{ $ctaTitulo }}<br><span class="text-zinc-600">{{ $ctaSubtitulo }}</span></h2>
            <div class="mt-8 flex flex-wrap justify-center gap-2">
                @foreach(array_filter([$trust1,$trust2,$trust3,$trust4]) as $badge)
                <span class="inline-flex items-center gap-1.5 border border-zinc-700 px-4 py-1.5 text-sm text-zinc-300 font-medium">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>{{ $badge }}
                </span>
                @endforeach
            </div>
            <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="#autos" class="inline-flex items-center justify-center bg-amber-400 hover:bg-amber-300 text-black font-black px-10 py-4 text-base uppercase tracking-wide transition">{{ $ctaHeroPrimario }}</a>
                <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 border border-emerald-600 text-emerald-400 hover:bg-emerald-600 hover:text-white font-black px-10 py-4 text-base uppercase tracking-wide transition">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>WhatsApp
                </a>
            </div>
        </div>
    </section>

    {{-- CONTACTO --}}
    <section id="contacto" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <p class="text-xs font-black tracking-[0.3em] uppercase text-amber-400">Contacto</p>
                    <h2 class="mt-3 text-5xl font-black uppercase tracking-tight leading-none">{{ $contactoTitulo }}<br><span class="text-zinc-600">{{ $contactoSubtitulo }}</span></h2>
                    <p class="mt-5 text-zinc-400">{{ $contactoDescripcion }}</p>
                    <div class="mt-8 space-y-2">
                        <div class="border border-zinc-800 p-4 flex items-center gap-3"><span class="text-amber-400 font-black text-sm w-20 uppercase shrink-0">Horario</span><span class="text-zinc-300">{{ $horario }}</span></div>
                        <div class="border border-zinc-800 p-4 flex items-center gap-3"><span class="text-amber-400 font-black text-sm w-20 uppercase shrink-0">Lugar</span><span class="text-zinc-300">{{ $direccion }}</span></div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-black font-black py-3.5 uppercase tracking-wide transition">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>Escribir
                        </a>
                        <a href="{{ $waCotizar }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center border border-zinc-700 hover:border-zinc-500 text-white font-black py-3.5 uppercase tracking-wide transition">Cotizar</a>
                    </div>
                </div>
                <livewire:public.formulario-contacto />
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-zinc-800 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <p class="font-black text-2xl uppercase text-white">{{ config('app.name','AutoLote') }}</p>
                <p class="text-xs text-zinc-600 uppercase tracking-widest mt-1">{{ $tagline }}</p>
            </div>
            <div class="flex flex-wrap gap-6 text-sm font-black uppercase tracking-wide">
                @foreach([['#inicio','Inicio'],['#autos','Autos'],['#proceso','Proceso'],['#contacto','Contacto'],[$catalogoUrl,'Catálogo']] as $link)
                <a href="{{ $link[0] }}" class="text-zinc-600 hover:text-amber-400 transition">{{ $link[1] }}</a>
                @endforeach
            </div>
            <p class="text-xs text-zinc-700">&copy; {{ date('Y') }} {{ config('app.name','AutoLote') }}</p>
        </div>
    </footer>
</div>
