<x-guest-layout>
    @section('title', 'Recuperar contraseña')
    @section('meta_description', 'Recupera el acceso al panel administrativo de ' . config('app.name'))

    <main class="grid min-h-dvh bg-slate-50 lg:grid-cols-[minmax(0,1.05fr)_minmax(32rem,0.95fr)]">
        <section class="relative hidden overflow-hidden bg-slate-950 px-12 py-10 text-white lg:flex lg:flex-col lg:justify-between xl:px-20 xl:py-14"
                 aria-label="Proceso de recuperación de acceso">
            <div class="bg-dot-grid absolute inset-0 opacity-70" aria-hidden="true"></div>
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-400 to-emerald-400" aria-hidden="true"></div>
            <div class="absolute -right-28 top-1/3 h-80 w-80 rounded-full border border-white/10" aria-hidden="true"></div>
            <div class="absolute -right-12 top-1/3 h-48 w-48 rounded-full border border-blue-400/20" aria-hidden="true"></div>

            <a href="/"
               class="relative z-10 inline-flex w-fit items-center gap-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-4 focus:ring-offset-slate-950">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 ring-1 ring-inset ring-white/15">
                    <svg class="h-6 w-6 text-blue-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5 5.6 8.87A3 3 0 0 1 8.39 7h7.22a3 3 0 0 1 2.79 1.87l1.85 4.63M5.25 13.5h13.5a1.5 1.5 0 0 1 1.5 1.5v2.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V15a1.5 1.5 0 0 1 1.5-1.5Z"/>
                        <path stroke-linecap="round" d="M7.5 16.25h.01M16.5 16.25h.01M6 18.75V21m12-2.25V21"/>
                    </svg>
                </span>
                <span>
                    <span class="block text-lg font-bold leading-tight">{{ config('app.name') }}</span>
                    <span class="block text-xs font-medium tracking-wide text-slate-400">Administración integral</span>
                </span>
            </a>

            <div class="relative z-10 max-w-xl">
                <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-blue-400/20 bg-blue-400/10 px-3 py-1.5 text-sm font-medium text-blue-200">
                    <svg class="h-4 w-4 text-emerald-300" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.25 8.25V6.5a3.75 3.75 0 0 1 7.5 0v1.75m-8 0h8.5a1.25 1.25 0 0 1 1.25 1.25v5.25A1.25 1.25 0 0 1 14.25 16h-8.5a1.25 1.25 0 0 1-1.25-1.25V9.5a1.25 1.25 0 0 1 1.25-1.25Z"/>
                    </svg>
                    Recuperación protegida
                </div>

                <h1 class="max-w-lg text-4xl font-bold leading-tight tracking-tight xl:text-5xl">
                    Recupera el acceso,
                    <span class="text-blue-300">vuelve al control.</span>
                </h1>

                <p class="mt-6 max-w-lg text-lg leading-relaxed text-slate-300">
                    Te enviaremos un enlace temporal para que puedas definir una nueva contraseña de forma segura.
                </p>

                <ol class="mt-9 grid gap-5" aria-label="Pasos para recuperar la contraseña">
                    <li class="flex items-start gap-4">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-400/10 text-sm font-bold text-blue-200 ring-1 ring-inset ring-blue-400/20">1</span>
                        <div>
                            <p class="font-semibold text-white">Ingresa tu correo registrado</p>
                            <p class="mt-1 text-sm leading-relaxed text-slate-400">Debe ser el mismo que utilizas para iniciar sesión.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-400/10 text-sm font-bold text-blue-200 ring-1 ring-inset ring-blue-400/20">2</span>
                        <div>
                            <p class="font-semibold text-white">Revisa tu bandeja de entrada</p>
                            <p class="mt-1 text-sm leading-relaxed text-slate-400">Recibirás un enlace único para confirmar la solicitud.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-400/10 text-sm font-bold text-blue-200 ring-1 ring-inset ring-blue-400/20">3</span>
                        <div>
                            <p class="font-semibold text-white">Crea una nueva contraseña</p>
                            <p class="mt-1 text-sm leading-relaxed text-slate-400">Elige una contraseña segura que no hayas utilizado antes.</p>
                        </div>
                    </li>
                </ol>
            </div>

            <div class="relative z-10 flex items-center gap-3 text-sm text-slate-400">
                <svg class="h-5 w-5 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25V12l2.25 1.5m6-1.5a8.25 8.25 0 1 1-16.5 0 8.25 8.25 0 0 1 16.5 0Z"/>
                </svg>
                El enlace expira automáticamente por seguridad
            </div>
        </section>

        <section class="relative flex min-h-dvh items-center justify-center px-5 py-10 sm:px-8 lg:px-12">
            <a href="/"
               class="absolute right-5 top-5 inline-flex min-h-11 items-center gap-2 rounded-lg px-3 text-sm font-semibold text-slate-600 transition-colors duration-200 hover:bg-white hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 sm:right-8 sm:top-8">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m12.5 15-5-5 5-5"/>
                </svg>
                Ir al sitio
            </a>

            <div class="w-full max-w-md">
                <div class="mb-8 flex justify-center lg:justify-start">
                    <x-authentication-card-logo />
                </div>

                <div class="mb-8 text-center lg:text-left">
                    <p class="mb-2 text-sm font-semibold uppercase tracking-[0.16em] text-blue-700">Recuperación de acceso</p>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Restablece tu contraseña</h2>
                    <p class="mt-3 text-base leading-relaxed text-slate-600">
                        Escribe el correo asociado a tu cuenta y te enviaremos las instrucciones.
                    </p>
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
                        <div>
                            <p class="font-semibold">No pudimos enviar el enlace.</p>
                            <p class="mt-1 text-red-700">Revisa el correo e intenta nuevamente.</p>
                        </div>
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
                                   aria-describedby="email-help{{ $errors->has('email') ? ' email-error' : '' }}"
                                   @error('email') aria-invalid="true" @enderror>
                        </div>
                        <p id="email-help" class="text-sm leading-relaxed text-slate-500">
                            Usa el correo con el que ingresas al panel administrativo.
                        </p>
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
                        <span x-text="submitting ? 'Enviando…' : 'Enviar enlace de recuperación'">Enviar enlace de recuperación</span>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <a href="{{ route('login') }}"
                       class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg px-3 text-sm font-semibold text-blue-700 transition-colors duration-200 hover:bg-blue-50 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m12.5 15-5-5 5-5"/>
                        </svg>
                        Volver a iniciar sesión
                    </a>
                </div>

                <div class="mt-6 flex items-center justify-center gap-2 text-center text-xs text-slate-500">
                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 8.5V6a4.5 4.5 0 0 1 9 0v2.5m-9.5 0h10a1 1 0 0 1 1 1V16a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5a1 1 0 0 1 1-1Z"/>
                    </svg>
                    El enlace solo se enviará a cuentas registradas
                </div>
            </div>
        </section>
    </main>
</x-guest-layout>
