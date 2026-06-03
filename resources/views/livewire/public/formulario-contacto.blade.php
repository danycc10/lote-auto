<div>
    @if($enviado)
        {{-- Success state --}}
        <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-10 flex flex-col items-center text-center gap-4">
            <div class="h-16 w-16 rounded-full bg-emerald-500/20 flex items-center justify-center">
                <svg class="h-8 w-8 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold text-white">¡Mensaje recibido!</p>
                <p class="mt-1 text-slate-400 text-sm">Nos pondremos en contacto contigo muy pronto.</p>
            </div>
            <button wire:click="$set('enviado', false)"
                    class="mt-2 text-sm text-emerald-400 hover:text-emerald-300 underline underline-offset-2 transition">
                Enviar otro mensaje
            </button>
        </div>
    @else
        {{-- Form --}}
        <div class="rounded-2xl border border-white/[0.08] bg-white/[0.03] p-6 sm:p-8">
            <h3 class="text-xl font-bold text-white mb-1">Déjanos tus datos</h3>
            <p class="text-sm text-slate-400 mb-6">Te contactamos sin compromiso.</p>

            <div class="space-y-4">
                {{-- Nombre --}}
                <div>
                    <label for="fc-nombre" class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">
                        Nombre <span class="text-red-400">*</span>
                    </label>
                    <input id="fc-nombre"
                           type="text"
                           wire:model="nombre"
                           placeholder="Tu nombre completo"
                           autocomplete="name"
                           class="w-full rounded-xl border @error('nombre') border-red-500 bg-red-950/20 @else border-white/10 bg-white/[0.05] @enderror px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-emerald-500/60 focus:outline-none focus:ring-1 focus:ring-emerald-500/40 transition">
                    @error('nombre')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div>
                    <label for="fc-telefono" class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">
                        Teléfono / WhatsApp
                    </label>
                    <input id="fc-telefono"
                           type="tel"
                           wire:model="telefono"
                           placeholder="10 dígitos"
                           autocomplete="tel"
                           class="w-full rounded-xl border border-white/10 bg-white/[0.05] px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-emerald-500/60 focus:outline-none focus:ring-1 focus:ring-emerald-500/40 transition">
                    @error('telefono')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Correo --}}
                <div>
                    <label for="fc-correo" class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">
                        Correo electrónico
                    </label>
                    <input id="fc-correo"
                           type="email"
                           wire:model="correo"
                           placeholder="tucorreo@ejemplo.com"
                           autocomplete="email"
                           class="w-full rounded-xl border @error('correo') border-red-500 bg-red-950/20 @else border-white/10 bg-white/[0.05] @enderror px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-emerald-500/60 focus:outline-none focus:ring-1 focus:ring-emerald-500/40 transition">
                    @error('correo')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mensaje --}}
                <div>
                    <label for="fc-mensaje" class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">
                        Mensaje (opcional)
                    </label>
                    <textarea id="fc-mensaje"
                              wire:model="mensaje"
                              rows="3"
                              placeholder="¿En qué te podemos ayudar? ¿Tienes algún auto en mente?"
                              class="w-full rounded-xl border border-white/10 bg-white/[0.05] px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-emerald-500/60 focus:outline-none focus:ring-1 focus:ring-emerald-500/40 transition resize-none"></textarea>
                </div>

                {{-- Submit --}}
                <button wire:click="enviar"
                        wire:loading.attr="disabled"
                        wire:target="enviar"
                        class="w-full flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3.5 text-base font-bold text-white shadow-lg transition hover:bg-emerald-500 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="enviar">Enviar mensaje</span>
                    <span wire:loading wire:target="enviar">Enviando...</span>
                    <svg wire:loading.remove wire:target="enviar" class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3.105 2.289a.75.75 0 00-.826.95l1.414 4.925A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086l-1.414 4.926a.75.75 0 00.826.95 28.896 28.896 0 0015.293-7.154.75.75 0 000-1.115A28.897 28.897 0 003.105 2.289z"/>
                    </svg>
                </button>

                <p class="text-center text-xs text-slate-600">
                    Tu información es confidencial y no será compartida.
                </p>
            </div>
        </div>
    @endif
</div>
