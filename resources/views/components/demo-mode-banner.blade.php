@if (config('demo.enabled'))
    <div class="border-b border-amber-300 bg-amber-50 px-4 py-2.5 text-amber-950 sm:px-6"
         role="status">
        <div class="flex items-center justify-center gap-2 text-center text-sm">
            <svg class="h-5 w-5 shrink-0 text-amber-700"
                 viewBox="0 0 20 20"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.8"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6.75v3.5m0 3h.01M17.25 10a7.25 7.25 0 1 1-14.5 0 7.25 7.25 0 0 1 14.5 0Z"/>
            </svg>
            <p>
                <span class="font-bold">Modo demo:</span>
                puedes explorar toda la plataforma, pero los cambios están deshabilitados.
            </p>
        </div>
    </div>
@endif
