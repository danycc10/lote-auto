{{-- ELEGANTE: Navy profundo + dorado/champagne, premium automotriz de lujo --}}
<div class="bg-[#070c1a] text-white overflow-x-hidden" style="font-family: 'Inter', sans-serif;">

    <x-public-navbar :whatsapp="$whatsapp" />

    @if($anuncioActivo && $anuncioTexto)
    <div class="w-full py-2 px-4 text-center text-xs font-semibold text-[#070c1a]" style="background-color: #c9a95c">{{ $anuncioTexto }}</div>
    @endif

    {{-- HERO --}}
    <section id="inicio" class="relative min-h-screen flex items-center pt-[68px] overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #070c1a 0%, #0d1635 50%, #070c1a 100%)"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full opacity-30 pointer-events-none" style="background: radial-gradient(ellipse at top right, rgba(201,169,92,0.3) 0%, transparent 60%)"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 opacity-20 pointer-events-none rounded-full blur-[100px]" style="background: rgba(201,169,92,0.4)"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="inline-flex items-center gap-2 mb-8" style="border-bottom: 1px solid rgba(201,169,92,0.4); padding-bottom: 12px">
                    <span class="h-px w-10" style="background: #c9a95c"></span>
                    <span class="text-xs font-medium tracking-[0.3em] uppercase" style="color: #c9a95c">{{ $badgeHero }}</span>
                </div>
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-thin leading-[1.05] tracking-wider text-white">
                    {{ $heroTitulo }}<br>
                    <span class="font-bold" style="color: #c9a95c">{{ $heroAcento }}</span>
                </h1>
                <p class="mt-8 text-lg leading-relaxed max-w-lg" style="color: rgba(255,255,255,0.55)">{{ $heroDescripcion }}</p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <a href="#autos" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-sm font-semibold tracking-wider uppercase text-[#070c1a] transition hover:opacity-90" style="background: linear-gradient(135deg, #c9a95c, #e8c97a)">
                        {{ $ctaHeroPrimario }}
                    </a>
                    <a href="{{ $waCotizar }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-sm font-semibold tracking-wider uppercase text-white border transition hover:bg-white/5" style="border-color: rgba(201,169,92,0.4)">
                        <svg class="h-4 w-4" style="color: #c9a95c" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>
                        Cotizar
                    </a>
                </div>
                <div class="mt-14 grid grid-cols-3 gap-6">
                    @foreach([[$stat1Valor,$stat1Label],[$stat2Valor,$stat2Label],[$stat3Valor,$stat3Label]] as $s)
                    <div>
                        <p class="text-3xl font-light tracking-wider" style="color: #c9a95c">{{ $s[0] }}</p>
                        <p class="mt-1 text-xs tracking-[0.2em] uppercase" style="color: rgba(255,255,255,0.35)">{{ $s[1] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($heroAutos->count())
            <div x-data="{ active:0, total:{{ $heroAutos->count() }}, start(){ setInterval(()=>{ this.active=(this.active+1)%this.total },5500) } }" x-init="start()" class="relative">
                <div class="relative overflow-hidden" style="border: 1px solid rgba(201,169,92,0.25); box-shadow: 0 0 60px rgba(201,169,92,0.08)">
                    <div class="relative aspect-[4/3] max-h-[480px]">
                        @foreach($heroAutos as $index => $ha)
                        @php
                            $hm6=$ha->marca??null;$hmo6=$ha->modelo??null;
                            $hmn6=is_object($hm6)?($hm6->nombre??''):($hm6??'');
                            $hmon6=is_object($hmo6)?($hmo6->nombre??''):($hmo6??'');
                            $htit6=trim($hmn6.' '.$hmon6)?:'Vehículo Premium';
                            $him6=$ha->imagenPortada?->ruta??$ha->imagenes?->first()?->ruta??null;
                            $hiUrl6=null;
                            if($him6){if(\Illuminate\Support\Str::startsWith($him6,['http://','https://']))$hiUrl6=$him6;elseif(\Illuminate\Support\Str::startsWith($him6,['storage/']))$hiUrl6=asset($him6);elseif(\Illuminate\Support\Str::startsWith($him6,['/storage/']))$hiUrl6=asset(ltrim($him6,'/'));elseif(\Illuminate\Support\Str::startsWith($him6,['public/']))$hiUrl6=asset('storage/'.\Illuminate\Support\Str::after($him6,'public/'));else $hiUrl6=asset('storage/'.$him6);}
                            $hprecio6=$ha->precio_venta??$ha->precio_contado??$ha->precio_financiado??0;
                        @endphp
                        @if($hiUrl6)
                        <div x-show="active==={{ $index }}" x-transition:enter="transition duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-400" x-transition:leave-end="opacity-0" class="absolute inset-0">
                            <img src="{{ $hiUrl6 }}" alt="{{ $htit6 }}" class="h-full w-full object-cover" loading="eager">
                            <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(7,12,26,0.9) 0%, rgba(7,12,26,0.2) 50%, transparent 100%)"></div>
                            <div class="absolute bottom-0 inset-x-0 p-6">
                                <p class="text-xs tracking-[0.25em] uppercase mb-1" style="color: #c9a95c">Disponible</p>
                                <h3 class="text-2xl font-light text-white tracking-wide">{{ $htit6 }}</h3>
                                @if($hprecio6)<p class="mt-1 text-base font-semibold" style="color: #c9a95c">${{ number_format((float)$hprecio6,0) }}</p>@endif
                            </div>
                        </div>
                        @endif
                        @endforeach
                        <div class="absolute bottom-4 right-4 flex gap-2 z-10">
                            @foreach($heroAutos as $index => $dot)
                            <button @click="active={{ $index }}" :class="active==={{ $index }}?'w-6':'w-2'" class="h-px transition-all duration-400" style="background: #c9a95c; opacity: active==={{ $index }} ? 1 : 0.3"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-px -right-px w-16 h-16 border-r border-b pointer-events-none" style="border-color: #c9a95c"></div>
                <div class="absolute -top-px -left-px w-16 h-16 border-l border-t pointer-events-none" style="border-color: rgba(201,169,92,0.4)"></div>
            </div>
            @endif
        </div>

        <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-xs tracking-[0.25em] uppercase hidden md:flex" style="color: rgba(255,255,255,0.3)">
            Scroll
            <span class="h-10 w-px block" style="background: linear-gradient(to bottom, rgba(201,169,92,0.6), transparent)"></span>
        </div>
    </section>

    {{-- TRUST BAR --}}
    <div style="background: rgba(201,169,92,0.04); border-top: 1px solid rgba(201,169,92,0.15); border-bottom: 1px solid rgba(201,169,92,0.15)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                @foreach([['200+','Autos entregados'],['24h','Respuesta garantizada'],['100%','Proceso transparente'],['0','Letra chica']] as $m)
                <div class="py-2">
                    <p class="text-2xl font-light tracking-wider" style="color: #c9a95c">{{ $m[0] }}</p>
                    <p class="text-xs tracking-[0.2em] uppercase mt-1" style="color: rgba(255,255,255,0.35)">{{ $m[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- BENEFICIOS --}}
    <section id="financiamiento" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center mb-16">
            <span class="text-xs tracking-[0.3em] uppercase" style="color: #c9a95c">{{ $beneficiosEyebrow }}</span>
            <h2 class="mt-4 text-4xl md:text-5xl font-thin tracking-wider text-white">{{ $beneficiosTitulo }}</h2>
            <p class="mt-4 max-w-xl mx-auto text-lg" style="color: rgba(255,255,255,0.45)">{{ $beneficiosSubtitulo }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-px" style="background: rgba(201,169,92,0.1)">
            @foreach($beneficios as $i => $b)
            <div class="p-10 hover:bg-white/[0.03] transition-colors" style="background: #070c1a">
                <div class="mb-6 h-px w-12" style="background: #c9a95c"></div>
                <h3 class="text-lg font-semibold tracking-wider text-white">{{ $b['titulo'] }}</h3>
                <p class="mt-3 leading-relaxed text-sm" style="color: rgba(255,255,255,0.45)">{{ $b['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- PROCESO --}}
    <section id="proceso" class="py-24" style="background: #040810">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs tracking-[0.3em] uppercase" style="color: #c9a95c">{{ $procesoEyebrow }}</span>
                <h2 class="mt-4 text-4xl md:text-5xl font-thin tracking-wider text-white">{{ $procesoTitulo }}</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($pasos as $i => $step)
                <div class="text-center">
                    <div class="mx-auto mb-5 h-14 w-14 rounded-full border flex items-center justify-center text-xl font-thin" style="border-color: rgba(201,169,92,0.4); color: #c9a95c">{{ $i+1 }}</div>
                    <h3 class="font-semibold text-white tracking-wide">{{ $step['titulo'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed" style="color: rgba(255,255,255,0.4)">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AUTOS --}}
    <section id="autos" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-12">
            <div>
                <span class="text-xs tracking-[0.3em] uppercase" style="color: #c9a95c">{{ $autosEyebrow }}</span>
                <h2 class="mt-4 text-4xl md:text-5xl font-thin tracking-wider text-white">{{ $autosTitulo }}</h2>
            </div>
            <a href="{{ $catalogoUrl }}" class="shrink-0 inline-flex items-center gap-2 px-6 py-3 text-sm tracking-wider uppercase border transition hover:bg-white/5" style="border-color: rgba(201,169,92,0.3); color: #c9a95c">
                Ver catálogo
            </a>
        </div>
        @if($autosDestacados->count())
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($autosDestacados as $auto)
            @php
                $mn7=$auto->marca??null;$mo7=$auto->modelo??null;
                $mnombre7=is_object($mn7)?($mn7->nombre??''):($mn7??'');
                $monombre7=is_object($mo7)?($mo7->nombre??''):($mo7??'');
                $tituloA7=trim(($mnombre7?:'Marca').' '.($monombre7?:'Modelo'));
                $precioC7=(float)($auto->precio_contado??$auto->precio_venta??$auto->precio??0);
                $precioF7=(float)($auto->precio_financiado??0);
                $precioM7=$precioC7?:$precioF7;
                $img7=$auto->imagenPortada?->ruta??null;$imgUrl7=null;
                if($img7){if(\Illuminate\Support\Str::startsWith($img7,['http://','https://']))$imgUrl7=$img7;elseif(\Illuminate\Support\Str::startsWith($img7,['storage/']))$imgUrl7=asset($img7);elseif(\Illuminate\Support\Str::startsWith($img7,['/storage/']))$imgUrl7=asset(ltrim($img7,'/'));elseif(\Illuminate\Support\Str::startsWith($img7,['public/']))$imgUrl7=asset('storage/'.\Illuminate\Support\Str::after($img7,'public/'));else $imgUrl7=asset('storage/'.$img7);}
                $msgWa7=$waBase.urlencode('Hola, me interesa el '.$tituloA7.' '.($auto->anio??''));
                $detUrl7=route('public.autos.show',$auto->uuid);
            @endphp
            <article class="group overflow-hidden transition-all duration-300" style="border: 1px solid rgba(201,169,92,0.12); background: rgba(255,255,255,0.02)">
                <a href="{{ $detUrl7 }}" class="block relative aspect-[16/10] overflow-hidden">
                    @if($imgUrl7)
                    <img src="{{ $imgUrl7 }}" alt="{{ $tituloA7 }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(7,12,26,0.8) 0%, transparent 60%)"></div>
                    @else
                    <div class="h-full w-full flex items-center justify-center" style="background: rgba(255,255,255,0.02)">
                        <svg class="h-10 w-10" style="color: rgba(201,169,92,0.3)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    </div>
                    @endif
                    <div class="absolute top-3 left-3 text-xs tracking-[0.2em] uppercase px-2 py-0.5 font-medium" style="background: rgba(201,169,92,0.9); color: #070c1a">Disponible</div>
                </a>
                <div class="p-5">
                    <p class="text-xs tracking-[0.15em] uppercase mb-1" style="color: rgba(255,255,255,0.3)">{{ $auto->anio??'' }}</p>
                    <a href="{{ $detUrl7 }}"><h3 class="text-lg font-light tracking-wider text-white group-hover:opacity-80 transition-opacity">{{ $tituloA7 }}</h3></a>
                    <p class="mt-3 text-xl font-semibold" style="color: #c9a95c">${{ number_format($precioM7,0) }}</p>
                    @if($precioF7>0&&$precioF7!==$precioC7)<p class="text-xs" style="color: rgba(201,169,92,0.6)">Financiado: ${{ number_format($precioF7,0) }}</p>@endif
                    <div class="mt-4 flex gap-2">
                        <a href="{{ $detUrl7 }}" class="flex-1 flex items-center justify-center py-2.5 text-xs tracking-wider uppercase border transition hover:bg-white/5" style="border-color: rgba(201,169,92,0.25); color: rgba(255,255,255,0.6)">Ver más</a>
                        <a href="{{ $msgWa7 }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-1.5 py-2.5 text-xs tracking-wider uppercase font-semibold transition hover:opacity-90" style="background: linear-gradient(135deg,#c9a95c,#e8c97a); color: #070c1a">
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>WhatsApp
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </section>

    {{-- CTA --}}
    <section class="py-24" style="background: #040810; border-top: 1px solid rgba(201,169,92,0.1); border-bottom: 1px solid rgba(201,169,92,0.1)">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <span class="text-xs tracking-[0.3em] uppercase" style="color: #c9a95c">{{ $ctaEyebrow }}</span>
            <h2 class="mt-5 text-4xl sm:text-5xl font-thin tracking-wider text-white">{{ $ctaTitulo }} <span style="color: rgba(255,255,255,0.3)">{{ $ctaSubtitulo }}</span></h2>
            <p class="mt-5 text-lg" style="color: rgba(255,255,255,0.45)">{{ $ctaDescripcion }}</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                @foreach(array_filter([$trust1,$trust2,$trust3,$trust4]) as $badge)
                <span class="inline-flex items-center gap-2 px-4 py-1.5 text-sm" style="border: 1px solid rgba(201,169,92,0.2); color: rgba(255,255,255,0.5)">
                    <span class="h-px w-4" style="background: #c9a95c"></span>{{ $badge }}
                </span>
                @endforeach
            </div>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#autos" class="inline-flex items-center justify-center px-10 py-4 text-sm tracking-wider uppercase font-semibold transition hover:opacity-90" style="background: linear-gradient(135deg,#c9a95c,#e8c97a); color: #070c1a">{{ $ctaHeroPrimario }}</a>
                <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-10 py-4 text-sm tracking-wider uppercase border transition hover:bg-white/5" style="border-color: rgba(201,169,92,0.3); color: #c9a95c">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>WhatsApp
                </a>
            </div>
        </div>
    </section>

    {{-- CONTACTO --}}
    <section id="contacto" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <div>
                    <span class="text-xs tracking-[0.3em] uppercase" style="color: #c9a95c">Contacto</span>
                    <h2 class="mt-4 text-4xl font-thin tracking-wider text-white">{{ $contactoTitulo }} <span style="color: rgba(255,255,255,0.3)">{{ $contactoSubtitulo }}</span></h2>
                    <p class="mt-5 leading-relaxed" style="color: rgba(255,255,255,0.45)">{{ $contactoDescripcion }}</p>
                    <div class="mt-8 space-y-3">
                        @foreach([['Horario',$horario],['Ubicación',$direccion],['WhatsApp','+52 '.$whatsapp]] as $ci)
                        <div class="flex items-start gap-4 py-4" style="border-bottom: 1px solid rgba(201,169,92,0.1)">
                            <span class="text-xs tracking-[0.2em] uppercase w-20 shrink-0 mt-0.5" style="color: #c9a95c">{{ $ci[0] }}</span>
                            <span class="text-sm" style="color: rgba(255,255,255,0.65)">{{ $ci[1] }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-8 flex gap-4">
                        <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-2 py-3.5 text-sm tracking-wider uppercase font-semibold transition hover:opacity-90" style="background: linear-gradient(135deg,#c9a95c,#e8c97a); color: #070c1a">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>Escribir
                        </a>
                        <a href="{{ $waCotizar }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center py-3.5 text-sm tracking-wider uppercase border transition hover:bg-white/5" style="border-color: rgba(201,169,92,0.3); color: rgba(255,255,255,0.6)">Cotizar</a>
                    </div>
                </div>
                <livewire:public.formulario-contacto />
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer style="border-top: 1px solid rgba(201,169,92,0.1); background: #040810">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div>
                    <a href="{{ $homeUrl }}" class="text-xl font-thin tracking-[0.15em] uppercase text-white">{{ config('app.name','AutoLote') }}</a>
                    <p class="text-xs mt-1 tracking-[0.2em] uppercase" style="color: #c9a95c; opacity: 0.7">{{ $tagline }}</p>
                </div>
                <div class="flex flex-wrap gap-8 text-xs tracking-[0.2em] uppercase">
                    @foreach([['#inicio','Inicio'],['#autos','Autos'],['#proceso','Proceso'],['#contacto','Contacto'],[$catalogoUrl,'Catálogo']] as $link)
                    <a href="{{ $link[0] }}" class="transition hover:opacity-100" style="color: rgba(255,255,255,0.35)">{{ $link[1] }}</a>
                    @endforeach
                </div>
                <p class="text-xs" style="color: rgba(255,255,255,0.2)">&copy; {{ date('Y') }} {{ config('app.name','AutoLote') }}</p>
            </div>
        </div>
    </footer>
</div>
