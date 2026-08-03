<div class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('admin.sistema.index') }}" wire:navigate class="rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 hover:text-indigo-700">Sistema</a>
                <span aria-hidden="true">/</span>
                <span>Salud operativa</span>
            </div>
            <h1 class="mt-1 text-xl font-semibold text-slate-900">Salud de la instalación</h1>
            <p class="mt-1 text-sm text-slate-600">Verifica los procesos esenciales de este lote sin mostrar credenciales ni secretos.</p>
        </div>

        <button type="button" wire:click="refreshHealth" wire:loading.attr="disabled" wire:target="refreshHealth"
            class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-wait disabled:opacity-60">
            <svg wire:loading.remove wire:target="refreshHealth" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992V4.356m-1.68 9a7.5 7.5 0 11-2.08-7.814l3.76 3.806" />
            </svg>
            <svg wire:loading wire:target="refreshHealth" class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span wire:loading.remove wire:target="refreshHealth">Actualizar estado</span>
            <span wire:loading wire:target="refreshHealth">Actualizando…</span>
        </button>
    </div>

    @php
        $overall = $health['overall'];
        $overallLabel = match ($overall) {
            'ok' => 'Operación normal',
            'error' => 'Requiere atención inmediata',
            default => 'Requiere revisión',
        };
    @endphp

    <section @class([
        'rounded-xl border p-5',
        'border-emerald-200 bg-emerald-50' => $overall === 'ok',
        'border-amber-200 bg-amber-50' => $overall === 'warning',
        'border-rose-200 bg-rose-50' => $overall === 'error',
    ]) aria-labelledby="estado-general">
        <div class="flex items-start gap-3">
            <span @class([
                'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-white',
                'bg-emerald-600' => $overall === 'ok',
                'bg-amber-600' => $overall === 'warning',
                'bg-rose-600' => $overall === 'error',
            ]) aria-hidden="true">
                @if($overall === 'ok')
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>
                @else
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.7 2.6 17a2 2 0 0 0 1.73 3h15.34a2 2 0 0 0 1.73-3L13.7 3.7a2 2 0 0 0-3.4 0Z" /></svg>
                @endif
            </span>
            <div>
                <h2 id="estado-general" class="font-semibold text-slate-900">{{ $overallLabel }}</h2>
                <p class="mt-1 text-sm text-slate-700">Diagnóstico generado el {{ $health['generated_at'] }}.</p>
            </div>
        </div>
    </section>

    <section aria-labelledby="comprobaciones">
        <div class="mb-3">
            <h2 id="comprobaciones" class="text-base font-semibold text-slate-900">Comprobaciones</h2>
            <p class="mt-0.5 text-sm text-slate-500">Los avisos no siempre implican una caída, pero sí requieren verificación.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($health['checks'] as $check)
                @php
                    $statusLabel = match ($check['status']) {
                        'ok' => 'Correcto',
                        'error' => 'Error',
                        default => 'Aviso',
                    };
                @endphp
                <article wire:key="health-check-{{ $loop->index }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="text-sm font-semibold text-slate-900">{{ $check['label'] }}</h3>
                        <span @class([
                            'inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold',
                            'bg-emerald-100 text-emerald-800' => $check['status'] === 'ok',
                            'bg-amber-100 text-amber-900' => $check['status'] === 'warning',
                            'bg-rose-100 text-rose-800' => $check['status'] === 'error',
                        ])>
                            <span @class([
                                'h-2 w-2 rounded-full',
                                'bg-emerald-600' => $check['status'] === 'ok',
                                'bg-amber-600' => $check['status'] === 'warning',
                                'bg-rose-600' => $check['status'] === 'error',
                            ]) aria-hidden="true"></span>
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $check['detail'] }}</p>
                    @if($check['checked_at'])
                        <p class="mt-3 text-xs text-slate-500">Última actividad: {{ $check['checked_at'] }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    </section>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" aria-labelledby="instalacion">
            <h2 id="instalacion" class="text-base font-semibold text-slate-900">Instalación del lote</h2>
            <dl class="mt-4 divide-y divide-slate-100 text-sm">
                <div class="flex items-start justify-between gap-4 py-3 first:pt-0"><dt class="text-slate-500">Nombre</dt><dd class="text-right font-medium text-slate-900">{{ $health['installation']['name'] }}</dd></div>
                <div class="flex items-start justify-between gap-4 py-3"><dt class="text-slate-500">Identificador</dt><dd class="break-all text-right font-medium text-slate-900">{{ $health['installation']['slug'] }}</dd></div>
                <div class="flex items-start justify-between gap-4 py-3"><dt class="text-slate-500">UUID</dt><dd class="break-all text-right font-mono text-xs text-slate-700">{{ $health['installation']['uuid'] }}</dd></div>
                <div class="flex items-start justify-between gap-4 py-3 last:pb-0"><dt class="text-slate-500">Versión</dt><dd class="text-right font-medium text-slate-900">{{ $health['installation']['version'] }}</dd></div>
            </dl>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" aria-labelledby="entorno">
            <h2 id="entorno" class="text-base font-semibold text-slate-900">Entorno técnico</h2>
            <dl class="mt-4 grid grid-cols-2 gap-x-4 gap-y-4 text-sm sm:grid-cols-3">
                <div><dt class="text-slate-500">Ambiente</dt><dd class="mt-1 font-medium text-slate-900">{{ $health['environment']['app'] }}</dd></div>
                <div><dt class="text-slate-500">PHP</dt><dd class="mt-1 font-medium text-slate-900">{{ $health['environment']['php'] }}</dd></div>
                <div><dt class="text-slate-500">Base de datos</dt><dd class="mt-1 font-medium text-slate-900">{{ $health['environment']['database'] }}</dd></div>
                <div><dt class="text-slate-500">Cola</dt><dd class="mt-1 font-medium text-slate-900">{{ $health['environment']['queue'] }}</dd></div>
                <div><dt class="text-slate-500">Worker</dt><dd class="mt-1 font-medium text-slate-900">{{ $health['environment']['worker_mode'] }}</dd></div>
                <div><dt class="text-slate-500">Correo</dt><dd class="mt-1 font-medium text-slate-900">{{ $health['environment']['mail'] }}</dd></div>
                <div><dt class="text-slate-500">En cola</dt><dd class="mt-1 font-medium text-slate-900">{{ $health['queues']['pending'] ?? 'No medible' }}</dd></div>
                <div><dt class="text-slate-500">Fallidos</dt><dd class="mt-1 font-medium text-slate-900">{{ $health['queues']['failed'] ?? 'No medible' }}</dd></div>
            </dl>
        </section>
    </div>

    <aside class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
        <p class="font-semibold">Qué hacer ante un aviso</p>
        <p class="mt-1 leading-6">Ejecuta <code class="rounded bg-blue-100 px-1.5 py-0.5 font-mono text-xs">php artisan hosting:verificar --strict</code>, revisa el cron de cPanel y confirma que puedas restaurar el respaldo externo.</p>
    </aside>
</div>
