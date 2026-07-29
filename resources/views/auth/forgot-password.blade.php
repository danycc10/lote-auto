<x-guest-layout>
    @section('title', 'Recuperar contraseña')
    @section('meta_description', 'Recupera el acceso al panel administrativo de ' . config('app.name'))

    <main class="relative flex min-h-dvh items-center justify-center overflow-hidden bg-slate-50 px-5 py-12 sm:px-8">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-emerald-400" aria-hidden="true"></div>
        <div class="absolute -left-24 top-16 h-72 w-72 rounded-full bg-blue-200/40 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -right-24 bottom-10 h-72 w-72 rounded-full bg-emerald-200/40 blur-3xl" aria-hidden="true"></div>

        <section class="relative w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-950/10 sm:p-9"
                 aria-labelledby="forgot-password-title">
            <div class="mb-7 flex justify-center">
                <x-authentication-card-logo />
            </div>

            <div class="mb-7 text-center">
                <h1 id="forgot-password-title" class="text-3xl font-bold tracking-tight text-slate-950">
                    Recuperar contraseña
                </h1>
            </div>

            @session('status')
                <div class="mb-6 flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 10 3 3 7-7"/>
                    </svg>
                    <div>
                        <p class="font-semibold">Revisa tu correo electrónico</p>
                        <p class="mt-1 text-emerald-700">{{ $value }}</p>
                    </div>
                </div>
            @endsession

            @if ($errors->any())
                <div class="mb-6 flex gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6.75v3.5m0 3h.01M17.25 10a7.25 7.25 0 1 1-14.5 0 7.25 7.25 0 0 1 14.5 0Z"/>
                    </svg>
                    <p class="font-semibold">Revisa el correo e intenta nuevamente.</p>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('password.email') }}"
                  class="grid gap-6"
                  x-data="{ submitting: false }"
                  x-on:submit="submitting = true">
                @csrf

                <div class="grid gap-2">
                    <label for="email" class="text-sm font-semibold text-slate-800">Correo electrónico</label>
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75 12 12.5l8.25-5.75M5.25 19.25h13.5a1.5 1.5 0 0 0 1.5-1.5V6.25a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v11.5a1.5 1.5 0 0 0 1.5 1.5Z"/>
                        </svg>
                        <input id="email"
                               class="block min-h-12 w-full rounded-xl bg-white py-3 pl-12 pr-4 text-base text-slate-950 shadow-sm transition duration-200 placeholder:text-slate-400 focus:ring-4 {{ $errors->has('email') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-300 hover:border-slate-400 focus:border-blue-600 focus:ring-blue-600/10' }}"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="nombre@empresa.com"
                               required
                               @if (! session('status')) autofocus @endif
                               autocomplete="username"
                               inputmode="email"
                               @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    </div>
                    @error('email')
                        <p id="email-error" class="text-sm font-medium text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-base font-bold text-white shadow-lg shadow-slate-950/15 transition duration-200 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 active:bg-blue-900 disabled:cursor-wait disabled:opacity-70"
                        x-bind:disabled="submitting"
                        x-bind:aria-busy="submitting">
                    <svg x-cloak x-show="submitting" class="h-5 w-5 animate-spin motion-reduce:animate-none" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-90" fill="currentColor" d="M12 3a9 9 0 0 1 9 9h-3a6 6 0 0 0-6-6V3Z"></path>
                    </svg>
                    <span x-text="submitting ? 'Enviando…' : 'Enviar enlace'">Enviar enlace</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}"
                   class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg px-3 text-sm font-semibold text-blue-700 transition-colors duration-200 hover:bg-blue-50 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m12.5 15-5-5 5-5"/>
                    </svg>
                    Volver a iniciar sesión
                </a>
            </div>
        </section>
    </main>
</x-guest-layout>
