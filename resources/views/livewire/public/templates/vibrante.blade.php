{{-- VIBRANTE: Gradiente morado→esmeralda, glassmorphism, energético y moderno --}}
<div class="overflow-x-hidden text-white" style="background: linear-gradient(160deg, #0f0c29 0%, #1a1060 35%, #0b3d2e 100%)">

    <x-public-navbar :whatsapp="$whatsapp" />

    @if($anuncioActivo && $anuncioTexto)
    <div class="w-full py-2 px-4 text-center text-xs font-semibold text-white" style="background: linear-gradient(90deg, #7c3aed, #059669)">{{ $anuncioTexto }}</div>
    @endif

    {{-- HERO --}}
    <section id="inicio" class="relative min-h-screen flex items-center pt-[68px] overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-20 left-10 w-[400px] h-[400px] rounded-full blur-[100px]" style="background: rgba(124,58,237,0.25)"></div>
            <div class="absolute bottom-20 right-10 w-[500px] h-[500px] rounded-full blur-[120px]" style="background: rgba(5,150,105,0.2)"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] rounded-full blur-[80px]" style="background: rgba(124,58,237,0.1)"></div>
        </div>
        {{-- Grid overlay --}}
        <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 60px 60px"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold mb-6" style="background: rgba(124,58,237,0.2); border: 1px solid rgba(167,139,250,0.3); color: #a78bfa">
                    <span class="h-2 w-2 rounded-full bg-purple-400 animate-pulse"></span>
                    {{ $badgeHero }}
                </div>
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black leading-[0.9] tracking-tight">
                    {{ $heroTitulo }}<br>
                    <span class="bg-clip-text text-transparent" style="background-image: linear-gradient(135deg, #a78bfa 0%, #34d399 100%)">{{ $heroAcento }}</span>
                </h1>
                <p class="mt-6 text-xl leading-relaxed max-w-lg" style="color: rgba(255,255,255,0.65)">{{ $heroDescripcion }}</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="#autos" class="inline-flex items-center justify-center gap-2 rounded-2xl px-8 py-4 text-base font-bold text-white shadow-2xl transition hover:opacity-90 active:scale-[0.97]" style="background: linear-gradient(135deg, #7c3aed, #059669); box-shadow: 0 8px 32px rgba(124,58,237,0.4)">
                        {{ $ctaHeroPrimario }}
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="{{ $waCotizar }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-2xl px-8 py-4 text-base font-bold text-white backdrop-blur-sm transition hover:bg-white/15 active:scale-[0.97]" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15)">
                        <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>
                        {{ $ctaHeroSecundario }}
                    </a>
                </div>
                <div class="mt-10 grid grid-cols-3 gap-4">
                    @foreach([[$stat1Valor,$stat1Label,'#a78bfa'],[$stat2Valor,$stat2Label,'#34d399'],[$stat3Valor,$stat3Label,'#a78bfa']] as $s)
                    <div class="rounded-2xl p-4 text-center backdrop-blur-sm" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1)">
                        <p class="text-2xl font-black" style="color: {{ $s[2] }}">{{ $s[0] }}</p>
                        <p class="text-xs mt-1" style="color: rgba(255,255,255,0.5)">{{ $s[1] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($heroAutos->count())
            <div x-data="{ active:0, total:{{ $heroAutos->count() }}, start(){ setInterval(()=>{ this.active=(this.active+1)%this.total },4500) } }" x-init="start()" class="relative">
                <div class="relative overflow-hidden rounded-3xl backdrop-blur-sm" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 20px 80px rgba(124,58,237,0.3)">
                    <div class="relative aspect-[4/3] max-h-[460px]">
                        @foreach($heroAutos as $index => $ha)
                        @php
                            $hm8=$ha->marca??null;$hmo8=$ha->modelo??null;
                            $hmn8=is_object($hm8)?($hm8->nombre??''):($hm8??'');
                            $hmon8=is_object($hmo8)?($hmo8->nombre??''):($hmo8??'');
                            $htit8=trim($hmn8.' '.$hmon8)?:'Auto disponible';
                            $him8=$ha->imagenPortada?->ruta??$ha->imagenes?->first()?->ruta??null;
                            $hiUrl8=null;
                            if($him8){if(\Illuminate\Support\Str::startsWith($him8,['http://','https://']))$hiUrl8=$him8;elseif(\Illuminate\Support\Str::startsWith($him8,['storage/']))$hiUrl8=asset($him8);elseif(\Illuminate\Support\Str::startsWith($him8,['/storage/']))$hiUrl8=asset(ltrim($him8,'/'));elseif(\Illuminate\Support\Str::startsWith($him8,['public/']))$hiUrl8=asset('storage/'.\Illuminate\Support\Str::after($him8,'public/'));else $hiUrl8=asset('storage/'.$him8);}
                            $hprecio8=$ha->precio_venta??$ha->precio_contado??$ha->precio_financiado??0;
                        @endphp
                        @if($hiUrl8)
                        <div x-show="active==={{ $index }}" x-transition:enter="transition duration-700" x-transition:enter-start="opacity-0 scale-[1.03]" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition duration-400" x-transition:leave-end="opacity-0" class="absolute inset-0">
                            <img src="{{ $hiUrl8 }}" alt="{{ $htit8 }}" class="h-full w-full object-cover" loading="eager">
                            <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(15,12,41,0.9) 0%, transparent 60%)"></div>
                            <div class="absolute bottom-0 inset-x-0 p-6">
                                <span class="inline-flex items-center gap-1 rounded-full text-xs font-bold px-3 py-1" style="background: linear-gradient(135deg,rgba(124,58,237,0.8),rgba(5,150,105,0.8)); backdrop-filter: blur(8px)">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>Disponible
                                </span>
                                <h3 class="mt-2 text-2xl font-black text-white">{{ $htit8 }} {{ $ha->anio??'' }}</h3>
                                @if($hprecio8)<p class="text-lg font-bold text-emerald-400">${{ number_format((float)$hprecio8,0) }}</p>@endif
                            </div>
                        </div>
                        @endif
                        @endforeach
                        <div class="absolute bottom-4 right-4 flex gap-2 z-10">
                            @foreach($heroAutos as $index => $dot)
                            <button @click="active={{ $index }}" :class="active==={{ $index }}?'w-6 opacity-100':'w-2 opacity-40'" class="h-2 rounded-full transition-all duration-300" style="background: #a78bfa"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    {{-- TRUST BAR --}}
    <div class="backdrop-blur-sm" style="background: rgba(255,255,255,0.04); border-top: 1px solid rgba(255,255,255,0.08); border-bottom: 1px solid rgba(255,255,255,0.08)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                @foreach([['200+','Autos entregados','#a78bfa'],['24h','Respuesta','#34d399'],['100%','Transparencia','#a78bfa'],['0','Letra chica','#34d399']] as $m)
                <div>
                    <p class="text-2xl font-black" style="color: {{ $m[2] }}">{{ $m[0] }}</p>
                    <p class="text-xs mt-1" style="color: rgba(255,255,255,0.45)">{{ $m[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- BENEFICIOS --}}
    <section id="financiamiento" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center mb-14">
            <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold mb-4" style="background: rgba(124,58,237,0.2); color: #a78bfa; border: 1px solid rgba(167,139,250,0.3)">{{ $beneficiosEyebrow }}</span>
            <h2 class="text-4xl md:text-5xl font-black tracking-tight">{{ $beneficiosTitulo }}</h2>
            <p class="mt-4 max-w-xl mx-auto text-lg" style="color: rgba(255,255,255,0.55)">{{ $beneficiosSubtitulo }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($beneficios as $i => $b)
            <div class="rounded-2xl p-8 backdrop-blur-sm hover:border-purple-500/30 transition-all" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1)">
                <div class="h-14 w-14 rounded-2xl flex items-center justify-center mb-5 text-2xl font-black" style="background: linear-gradient(135deg,rgba(124,58,237,0.3),rgba(5,150,105,0.3)); color: #a78bfa">{{ $i+1 }}</div>
                <h3 class="text-xl font-bold text-white">{{ $b['titulo'] }}</h3>
                <p class="mt-3 leading-relaxed" style="color: rgba(255,255,255,0.55)">{{ $b['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- PROCESO --}}
    <section id="proceso" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-xs font-semibold tracking-[0.2em] uppercase" style="color: #34d399">{{ $procesoEyebrow }}</span>
                <h2 class="mt-3 text-4xl md:text-5xl font-black tracking-tight">{{ $procesoTitulo }}</h2>
                <p class="mt-4 max-w-xl mx-auto" style="color: rgba(255,255,255,0.55)">{{ $procesoSubtitulo }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($pasos as $i => $step)
                <div class="rounded-2xl p-6 text-center backdrop-blur-sm" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08)">
                    <div class="mx-auto mb-4 h-12 w-12 rounded-full flex items-center justify-center font-black text-xl" style="background: linear-gradient(135deg,#7c3aed,#059669); box-shadow: 0 4px 20px rgba(124,58,237,0.4)">{{ $i+1 }}</div>
                    <h3 class="font-bold text-white">{{ $step['titulo'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed" style="color: rgba(255,255,255,0.5)">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AUTOS --}}
    <section id="autos" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-12">
            <div>
                <span class="text-xs font-semibold tracking-[0.2em] uppercase text-emerald-400">{{ $autosEyebrow }}</span>
                <h2 class="mt-3 text-4xl md:text-5xl font-black tracking-tight">{{ $autosTitulo }}</h2>
                <p class="mt-3 max-w-xl" style="color: rgba(255,255,255,0.55)">{{ $autosDescripcion }}</p>
            </div>
            <a href="{{ $catalogoUrl }}" class="shrink-0 inline-flex items-center gap-2 rounded-xl px-6 py-3.5 text-sm font-semibold backdrop-blur-sm transition hover:bg-white/10" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12)">
                Ver catálogo completo
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
            </a>
        </div>
        @if($autosDestacados->count())
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($autosDestacados as $auto)
            @php
                $mn9=$auto->marca??null;$mo9=$auto->modelo??null;
                $mnombre9=is_object($mn9)?($mn9->nombre??''):($mn9??'');
                $monombre9=is_object($mo9)?($mo9->nombre??''):($mo9??'');
                $tituloA9=trim(($mnombre9?:'Marca').' '.($monombre9?:'Modelo'));
                $precioC9=(float)($auto->precio_contado??$auto->precio_venta??$auto->precio??0);
                $precioF9=(float)($auto->precio_financiado??0);
                $precioM9=$precioC9?:$precioF9;
                $img9=$auto->imagenPortada?->ruta??null;$imgUrl9=null;
                if($img9){if(\Illuminate\Support\Str::startsWith($img9,['http://','https://']))$imgUrl9=$img9;elseif(\Illuminate\Support\Str::startsWith($img9,['storage/']))$imgUrl9=asset($img9);elseif(\Illuminate\Support\Str::startsWith($img9,['/storage/']))$imgUrl9=asset(ltrim($img9,'/'));elseif(\Illuminate\Support\Str::startsWith($img9,['public/']))$imgUrl9=asset('storage/'.\Illuminate\Support\Str::after($img9,'public/'));else $imgUrl9=asset('storage/'.$img9);}
                $msgWa9=$waBase.urlencode('Hola, me interesa el '.$tituloA9.' '.($auto->anio??''));
                $detUrl9=route('public.autos.show',$auto->uuid);
            @endphp
            <article class="group overflow-hidden rounded-2xl backdrop-blur-sm hover:border-purple-500/40 transition-all" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1)">
                <a href="{{ $detUrl9 }}" class="block relative aspect-[16/10] overflow-hidden">
                    @if($imgUrl9)
                    <img src="{{ $imgUrl9 }}" alt="{{ $tituloA9 }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(15,12,41,0.7) 0%, transparent 60%)"></div>
                    @else
                    <div class="h-full w-full flex items-center justify-center" style="background: rgba(124,58,237,0.1)"><svg class="h-10 w-10 text-purple-400/40" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg></div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center gap-1 rounded-full text-xs font-bold px-2.5 py-0.5 text-white" style="background: linear-gradient(135deg,rgba(124,58,237,0.9),rgba(5,150,105,0.9))">Disponible</span>
                    </div>
                </a>
                <div class="p-5">
                    <p class="text-xs font-medium" style="color: rgba(255,255,255,0.4)">{{ $auto->anio??'' }}</p>
                    <a href="{{ $detUrl9 }}"><h3 class="mt-1 text-xl font-bold text-white group-hover:text-purple-300 transition-colors">{{ $tituloA9 }}</h3></a>
                    <div class="mt-4 flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-black text-emerald-400">${{ number_format($precioM9,0) }}</p>
                            @if($precioF9>0&&$precioF9!==$precioC9)<p class="text-xs text-purple-300">Fin: ${{ number_format($precioF9,0) }}</p>@endif
                        </div>
                        <span class="text-xs" style="color: rgba(255,255,255,0.35)">{{ number_format((float)($auto->kilometraje??0)) }} km</span>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <a href="{{ $detUrl9 }}" class="flex-1 flex items-center justify-center rounded-xl py-2.5 text-sm font-semibold backdrop-blur-sm transition hover:bg-white/10" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1)">Ver detalles</a>
                        <a href="{{ $msgWa9 }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-1.5 rounded-xl py-2.5 text-sm font-bold transition hover:opacity-90" style="background: linear-gradient(135deg,#7c3aed,#059669)">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>WhatsApp
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </section>

    {{-- CTA --}}
    <section class="py-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <span class="text-xs font-semibold tracking-[0.2em] uppercase text-purple-400">{{ $ctaEyebrow }}</span>
            <h2 class="mt-4 text-4xl sm:text-5xl font-black tracking-tight">{{ $ctaTitulo }} <span style="color: rgba(255,255,255,0.35)">{{ $ctaSubtitulo }}</span></h2>
            <p class="mt-5 text-lg" style="color: rgba(255,255,255,0.55)">{{ $ctaDescripcion }}</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                @foreach(array_filter([$trust1,$trust2,$trust3,$trust4]) as $badge)
                <span class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-sm backdrop-blur-sm" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: rgba(255,255,255,0.7)">
                    <svg class="h-3.5 w-3.5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                    {{ $badge }}
                </span>
                @endforeach
            </div>
            <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="#autos" class="inline-flex items-center justify-center rounded-2xl px-10 py-4 text-base font-bold transition hover:opacity-90" style="background: linear-gradient(135deg,#7c3aed,#059669); box-shadow: 0 8px 32px rgba(124,58,237,0.4)">{{ $ctaHeroPrimario }}</a>
                <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-2xl px-10 py-4 text-base font-bold backdrop-blur-sm transition hover:bg-white/10" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15)">
                    <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>Cotizar
                </a>
            </div>
        </div>
    </section>

    {{-- CONTACTO --}}
    <section id="contacto" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <span class="text-xs font-semibold tracking-[0.2em] uppercase text-purple-400">Contacto</span>
                    <h2 class="mt-3 text-4xl font-black tracking-tight">{{ $contactoTitulo }} <span style="color: rgba(255,255,255,0.35)">{{ $contactoSubtitulo }}</span></h2>
                    <p class="mt-5 text-lg leading-relaxed" style="color: rgba(255,255,255,0.55)">{{ $contactoDescripcion }}</p>
                    <div class="mt-8 space-y-3">
                        @foreach([['WhatsApp','+52 '.$whatsapp,'text-emerald-400'],['Horario',$horario,'text-purple-400'],['Ubicación',$direccion,'text-purple-400']] as $ci)
                        <div class="flex items-center gap-4 rounded-2xl p-4 backdrop-blur-sm" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08)">
                            <div class="shrink-0 h-10 w-10 rounded-xl flex items-center justify-center text-xs font-black {{ $ci[2] }}" style="background: rgba(255,255,255,0.06)">{{ mb_substr($ci[0],0,1) }}</div>
                            <div><p class="text-xs {{ $ci[2] }} font-medium">{{ $ci[0] }}</p><p class="text-sm text-white">{{ $ci[1] }}</p></div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-6 flex gap-3">
                        <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-2 rounded-2xl py-3.5 font-bold transition hover:opacity-90" style="background: linear-gradient(135deg,#7c3aed,#059669)">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>Escribir
                        </a>
                        <a href="{{ $waCotizar }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center rounded-2xl py-3.5 font-semibold backdrop-blur-sm transition hover:bg-white/10" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15)">Cotizar</a>
                    </div>
                </div>
                <livewire:public.formulario-contacto />
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer style="background: rgba(0,0,0,0.4); border-top: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(16px)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <p class="font-black text-lg bg-clip-text text-transparent" style="background-image: linear-gradient(135deg,#a78bfa,#34d399)">{{ config('app.name','AutoLote') }}</p>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.35)">{{ $tagline }}</p>
                </div>
                <div class="flex flex-wrap gap-6 text-sm">
                    @foreach([['#inicio','Inicio'],['#autos','Autos'],['#proceso','Proceso'],['#contacto','Contacto'],[$catalogoUrl,'Catálogo']] as $link)
                    <a href="{{ $link[0] }}" class="transition hover:text-purple-400" style="color: rgba(255,255,255,0.4)">{{ $link[1] }}</a>
                    @endforeach
                </div>
                <p class="text-xs" style="color: rgba(255,255,255,0.2)">&copy; {{ date('Y') }} {{ config('app.name','AutoLote') }}</p>
            </div>
        </div>
    </footer>
</div>
