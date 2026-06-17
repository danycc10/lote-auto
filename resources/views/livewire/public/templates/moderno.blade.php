<div class="bg-white text-slate-900 overflow-x-hidden">

    <x-public-navbar :whatsapp="$whatsapp" />

    @if($anuncioActivo && $anuncioTexto)
    <div class="w-full py-2 px-4 text-center text-xs font-semibold text-white" style="background-color: var(--color-primario)">{{ $anuncioTexto }}</div>
    @endif

    {{-- HERO --}}
    <section id="inicio" class="relative min-h-screen flex items-center overflow-hidden pt-[68px] bg-gradient-to-br from-slate-50 via-white to-indigo-50">
        <div class="absolute top-0 right-0 w-[700px] h-[700px] bg-indigo-100/60 rounded-full blur-[120px] pointer-events-none -translate-y-1/3 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-100/40 rounded-full blur-[100px] pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 border border-indigo-200 px-4 py-1.5 text-sm font-semibold text-indigo-700">
                    <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    {{ $badgeHero }}
                </span>
                <h1 class="mt-6 text-5xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-[0.9] text-slate-900">
                    {{ $heroTitulo }}<br>
                    <span style="color: var(--color-primario)">{{ $heroAcento }}</span>
                </h1>
                <p class="mt-6 text-xl text-slate-500 leading-relaxed max-w-lg">{{ $heroDescripcion }}</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="#autos" class="inline-flex items-center justify-center gap-2 rounded-xl px-7 py-4 text-base font-bold text-white shadow-lg transition hover:opacity-90 active:scale-[0.97]" style="background-color: var(--color-primario)">
                        {{ $ctaHeroPrimario }}
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="{{ $waCotizar }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-7 py-4 text-base font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 active:scale-[0.97]">
                        <svg class="h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>
                        {{ $ctaHeroSecundario }}
                    </a>
                </div>
                <div class="mt-10 grid grid-cols-3 gap-4 max-w-lg">
                    @foreach([[$stat1Valor,$stat1Label],[$stat2Valor,$stat2Label],[$stat3Valor,$stat3Label]] as $s)
                    <div class="text-center rounded-2xl border border-slate-100 bg-white shadow-sm p-4">
                        <p class="text-2xl font-black text-slate-900" style="color: var(--color-primario)">{{ $s[0] }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $s[1] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($heroAutos->count())
            <div x-data="{ active:0, total:{{ $heroAutos->count() }}, start(){ setInterval(()=>{ this.active=(this.active+1)%this.total },4500) } }" x-init="start()" class="relative">
                <div class="absolute -inset-4 bg-indigo-100/50 rounded-[2rem] blur-2xl pointer-events-none"></div>
                <div class="relative overflow-hidden rounded-2xl border border-slate-200 shadow-2xl bg-white">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        @foreach($heroAutos as $index => $ha)
                        @php
                            $hm2 = $ha->marca ?? null; $hmo2 = $ha->modelo ?? null;
                            $hmn2 = is_object($hm2)?($hm2->nombre??''):($hm2??'');
                            $hmon2 = is_object($hmo2)?($hmo2->nombre??''):($hmo2??'');
                            $htit2 = trim($hmn2.' '.$hmon2)?:'Auto disponible';
                            $him2 = $ha->imagenPortada?->ruta ?? $ha->imagenes?->first()?->ruta ?? null;
                            $hiUrl2 = null;
                            if ($him2) {
                                if (\Illuminate\Support\Str::startsWith($him2,['http://','https://'])) $hiUrl2=$him2;
                                elseif (\Illuminate\Support\Str::startsWith($him2,['storage/'])) $hiUrl2=asset($him2);
                                elseif (\Illuminate\Support\Str::startsWith($him2,['/storage/'])) $hiUrl2=asset(ltrim($him2,'/'));
                                elseif (\Illuminate\Support\Str::startsWith($him2,['public/'])) $hiUrl2=asset('storage/'.\Illuminate\Support\Str::after($him2,'public/'));
                                else $hiUrl2=asset('storage/'.$him2);
                            }
                            $hprecio2 = $ha->precio_venta ?? $ha->precio_contado ?? $ha->precio_financiado ?? 0;
                        @endphp
                        @if($hiUrl2)
                        <div x-show="active==={{ $index }}" x-transition:enter="transition duration-600" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0">
                            <img src="{{ $hiUrl2 }}" alt="{{ $htit2 }}" class="h-full w-full object-cover" loading="eager">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 to-transparent"></div>
                            <div class="absolute bottom-0 inset-x-0 p-5">
                                <span class="inline-flex items-center rounded-full bg-emerald-500 px-2.5 py-0.5 text-xs font-bold text-white">Disponible</span>
                                <h3 class="mt-1 text-xl font-black text-white">{{ $htit2 }} {{ $ha->anio??'' }}</h3>
                                @if($hprecio2)<p class="text-sm text-white/80 font-semibold">${{ number_format((float)$hprecio2,0) }}</p>@endif
                            </div>
                        </div>
                        @endif
                        @endforeach
                        <div class="absolute bottom-4 right-4 flex gap-1.5 z-10">
                            @foreach($heroAutos as $index => $dot)
                            <button @click="active={{ $index }}" :class="active==={{ $index }}?'bg-white w-6':'bg-white/40 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    {{-- TRUST BAR --}}
    <div class="border-y border-slate-100 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-slate-100 text-center">
                @foreach([['200+','Autos entregados'],['24h','Respuesta garantizada'],['100%','Proceso transparente'],['0','Letra chica']] as $m)
                <div class="py-4 px-6">
                    <p class="text-2xl font-black" style="color: var(--color-primario)">{{ $m[0] }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $m[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- BENEFICIOS --}}
    <section id="financiamiento" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center mb-14">
            <p class="text-xs font-semibold tracking-[0.22em] uppercase" style="color: var(--color-primario)">{{ $beneficiosEyebrow }}</p>
            <h2 class="mt-3 text-4xl md:text-5xl font-black tracking-tight text-slate-900">{{ $beneficiosTitulo }}</h2>
            <p class="mt-4 text-slate-500 max-w-xl mx-auto text-lg">{{ $beneficiosSubtitulo }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($beneficios as $i => $b)
            <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm hover:shadow-md transition-shadow">
                <div class="h-14 w-14 rounded-xl flex items-center justify-center mb-5" style="background-color: color-mix(in srgb, var(--color-primario) 10%, white)">
                    <svg class="h-7 w-7" style="color: var(--color-primario)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">{!! $b['icon'] !!}</svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">{{ $b['titulo'] }}</h3>
                <p class="mt-2 text-slate-500 leading-relaxed">{{ $b['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- PROCESO --}}
    <section id="proceso" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <p class="text-xs font-semibold tracking-[0.22em] uppercase" style="color: var(--color-primario)">{{ $procesoEyebrow }}</p>
                <h2 class="mt-3 text-4xl md:text-5xl font-black tracking-tight text-slate-900">{{ $procesoTitulo }}</h2>
                <p class="mt-4 text-slate-500 max-w-xl mx-auto">{{ $procesoSubtitulo }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($pasos as $i => $step)
                <div class="relative bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center">
                    <div class="mx-auto mb-4 h-12 w-12 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-md" style="background-color: var(--color-primario)">{{ $i+1 }}</div>
                    <h3 class="text-lg font-bold text-slate-900">{{ $step['titulo'] }}</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AUTOS --}}
    <section id="autos" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-12">
            <div>
                <p class="text-xs font-semibold tracking-[0.22em] uppercase" style="color: var(--color-primario)">{{ $autosEyebrow }}</p>
                <h2 class="mt-3 text-4xl md:text-5xl font-black tracking-tight text-slate-900">{{ $autosTitulo }}</h2>
                <p class="mt-3 text-slate-500 max-w-xl">{{ $autosDescripcion }}</p>
            </div>
            <a href="{{ $catalogoUrl }}" class="shrink-0 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                Ver catálogo completo <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
            </a>
        </div>
        @if($autosDestacados->count())
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($autosDestacados as $auto)
            @php
                $mn3=$auto->marca??null;$mo3=$auto->modelo??null;
                $mnombre3=is_object($mn3)?($mn3->nombre??''):($mn3??'');
                $monombre3=is_object($mo3)?($mo3->nombre??''):($mo3??'');
                $tituloA3=trim(($mnombre3?:'Marca').' '.($monombre3?:'Modelo'));
                $precioC3=(float)($auto->precio_contado??$auto->precio_venta??$auto->precio??0);
                $precioF3=(float)($auto->precio_financiado??0);
                $precioM3=$precioC3?:$precioF3;
                $img3=$auto->imagenPortada?->ruta??null;$imgUrl3=null;
                if($img3){if(\Illuminate\Support\Str::startsWith($img3,['http://','https://']))$imgUrl3=$img3;elseif(\Illuminate\Support\Str::startsWith($img3,['storage/']))$imgUrl3=asset($img3);elseif(\Illuminate\Support\Str::startsWith($img3,['/storage/']))$imgUrl3=asset(ltrim($img3,'/'));elseif(\Illuminate\Support\Str::startsWith($img3,['public/']))$imgUrl3=asset('storage/'.\Illuminate\Support\Str::after($img3,'public/'));else $imgUrl3=asset('storage/'.$img3);}
                $msgWa3=$waBase.urlencode('Hola, me interesa el '.$tituloA3.' '.($auto->anio??''));
                $detUrl3=route('public.autos.show',$auto->uuid);
            @endphp
            <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-lg transition-shadow">
                <a href="{{ $detUrl3 }}" class="block relative aspect-[16/10] overflow-hidden bg-slate-100">
                    @if($imgUrl3)
                    <img src="{{ $imgUrl3 }}" alt="{{ $tituloA3 }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                    @else
                    <div class="h-full w-full flex items-center justify-center bg-slate-100">
                        <svg class="h-12 w-12 text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center rounded-full bg-emerald-500 px-2.5 py-1 text-xs font-bold text-white shadow">Disponible</span>
                    </div>
                </a>
                <div class="p-5">
                    <p class="text-xs text-slate-400 font-medium">{{ $auto->anio??'' }}</p>
                    <a href="{{ $detUrl3 }}"><h3 class="mt-1 text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $tituloA3 }}</h3></a>
                    <div class="mt-4 flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-black text-slate-900">${{ number_format($precioM3,0) }}</p>
                            @if($precioF3>0&&$precioF3!==$precioC3)<p class="text-sm font-semibold text-emerald-600">Fin.: ${{ number_format($precioF3,0) }}</p>@endif
                        </div>
                        <span class="text-sm text-slate-400">{{ number_format((float)($auto->kilometraje??0)) }} km</span>
                    </div>
                    @if($auto->transmision||$auto->color)
                    <div class="mt-3 flex flex-wrap gap-1.5">
                        @foreach(array_filter([$auto->transmision,$auto->color,$auto->tipo_combustible]) as $chip3)
                        <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs text-slate-600 font-medium">{{ ucfirst($chip3) }}</span>
                        @endforeach
                    </div>
                    @endif
                    <div class="mt-4 flex gap-2">
                        <a href="{{ $detUrl3 }}" class="flex-1 flex items-center justify-center rounded-xl border border-slate-200 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Ver detalles</a>
                        <a href="{{ $msgWa3 }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-1.5 rounded-xl py-2.5 text-sm font-bold text-white transition hover:opacity-90" style="background-color: var(--color-secundario)">
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
    <section class="py-20 bg-slate-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <p class="text-xs font-semibold tracking-[0.22em] uppercase" style="color: var(--color-primario)">{{ $ctaEyebrow }}</p>
            <h2 class="mt-4 text-4xl sm:text-5xl font-black tracking-tight text-slate-900">{{ $ctaTitulo }} <span class="text-slate-400">{{ $ctaSubtitulo }}</span></h2>
            <p class="mt-5 text-lg text-slate-500">{{ $ctaDescripcion }}</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                @foreach(array_filter([$trust1,$trust2,$trust3,$trust4]) as $badge)
                <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-1.5 text-sm text-slate-600 shadow-sm">
                    <svg class="h-3.5 w-3.5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                    {{ $badge }}
                </span>
                @endforeach
            </div>
            <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="#autos" class="inline-flex items-center justify-center rounded-xl px-8 py-4 text-base font-bold text-white shadow-lg transition hover:opacity-90" style="background-color: var(--color-primario)">{{ $ctaHeroPrimario }}</a>
                <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-8 py-4 text-base font-bold text-white shadow-lg transition hover:opacity-90" style="background-color: var(--color-secundario)">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>Cotizar ahora
                </a>
            </div>
        </div>
    </section>

    {{-- CONTACTO --}}
    <section id="contacto" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <p class="text-xs font-semibold tracking-[0.22em] uppercase" style="color: var(--color-primario)">Contacto</p>
                    <h2 class="mt-3 text-4xl font-black text-slate-900">{{ $contactoTitulo }} <span class="text-slate-400">{{ $contactoSubtitulo }}</span></h2>
                    <p class="mt-5 text-lg text-slate-500">{{ $contactoDescripcion }}</p>
                    <div class="mt-8 space-y-3">
                        @foreach([['Horario',$horario,'text-blue-600'],['Ubicación',$direccion,'text-violet-600'],['WhatsApp','+52 '.$whatsapp,'text-emerald-600']] as $ci)
                        <div class="flex items-center gap-4 rounded-xl border border-slate-100 p-4">
                            <div class="shrink-0 h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center font-bold {{ $ci[2] }} text-sm">{{ mb_substr($ci[0],0,1) }}</div>
                            <div><p class="text-xs text-slate-400 font-medium">{{ $ci[0] }}</p><p class="font-semibold text-slate-800">{{ $ci[1] }}</p></div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-6 flex gap-3">
                        <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-2 rounded-xl py-3.5 font-bold text-white transition hover:opacity-90" style="background-color: var(--color-secundario)">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>Escribir
                        </a>
                        <a href="{{ $waCotizar }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-2 rounded-xl border border-slate-200 py-3.5 font-semibold text-slate-700 hover:bg-slate-50 transition">Cotizar</a>
                    </div>
                </div>
                <livewire:public.formulario-contacto />
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <p class="font-black text-xl">{{ config('app.name','AutoLote') }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ $tagline }}</p>
                    <p class="mt-3 text-sm text-slate-500">{{ $descripcionFooter }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-4">Navegación</p>
                    <ul class="space-y-2">
                        @foreach([['#inicio','Inicio'],['#financiamiento','Financiamiento'],['#proceso','Proceso'],['#autos','Autos'],['#contacto','Contacto'],[$catalogoUrl,'Catálogo']] as $link)
                        <li><a href="{{ $link[0] }}" class="text-sm text-slate-400 hover:text-white transition">{{ $link[1] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-4">Contacto</p>
                    <p class="text-sm text-slate-400">{{ $horario }}</p>
                    <p class="mt-2 text-sm text-slate-400">{{ $direccion }}</p>
                    <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700 transition">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">{!! $waIconSvg !!}</svg>WhatsApp
                    </a>
                </div>
            </div>
            <div class="mt-10 pt-8 border-t border-slate-800 text-center text-xs text-slate-600">
                &copy; {{ date('Y') }} {{ config('app.name','AutoLote') }}. Todos los derechos reservados.
            </div>
        </div>
    </footer>
</div>
