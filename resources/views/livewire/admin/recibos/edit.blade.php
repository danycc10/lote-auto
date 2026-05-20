<div class="max-w-4xl mx-auto p-6 space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900">
                Editar {{ $recibo->folio }}
            </h1>
            <p class="text-gray-500">Solo campos administrativos.</p>
        </div>

        <a href="{{ route('admin.recibos.show', $recibo->id) }}"
           class="px-4 py-2 rounded-xl border bg-white hover:bg-gray-50">
            Volver
        </a>
    </div>

    @if(session('ok'))
        <div class="p-4 rounded-xl bg-green-50 text-green-700 border border-green-200">
            {{ session('ok') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border p-6 space-y-5">

        <div>
            <label class="font-semibold text-sm">Concepto</label>
            <textarea wire:model="concepto"
                      class="w-full mt-1 rounded-xl border-gray-300"
                      rows="3"></textarea>
        </div>

        <div>
            <label class="font-semibold text-sm">Observaciones</label>
            <textarea wire:model="observaciones"
                      class="w-full mt-1 rounded-xl border-gray-300"
                      rows="5"></textarea>
        </div>

        <div class="pt-2">
            <button wire:click="guardar"
                    class="px-5 py-2.5 rounded-xl bg-black text-white hover:bg-gray-800">
                Guardar cambios
            </button>
        </div>

    </div>

</div>