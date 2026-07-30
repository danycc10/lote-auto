<x-guest-layout>
    @section('title', 'Iniciar sesión')
    @section('meta_description', 'Acceso seguro al panel administrativo de ' . config('app.name'))

    <main class="grid min-h-dvh bg-slate-50 lg:grid-cols-[minmax(0,1.05fr)_minmax(32rem,0.95fr)]">
        <section class="relative hidden overflow-hidden bg-slate-950 px-12 py-10 text-white lg:flex lg:flex-col lg:justify-between xl:px-20 xl:py-14"
                 aria-label="Información del sistema">
            <div class="bg-dot-grid absolute inset-0 opacity-70" aria-hidden="true"></div>
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-400 to-emerald-400" aria-hidden="true"></div>
            <div class="absolute -right-28 top-1/3 h-80 w-80 rounded-full border border-white/10" aria-hidden="true"></div>
            <div class="absolute -right-12 top-1/3 h-48 w-48 rounded-full border border-blue-400/20" aria-hidden="true"></div>

            <a href="/" class="relative z-10 inline-flex w-fit items-center gap-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-4 focus:ring-offset-slate-950">
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
                    <span class="h-2 w-2 rounded-full bg-emerald-400" aria-hidden="true"></span>
                    Operación centralizada y segura
                </div>

                <h1 class="max-w-lg text-4xl font-bold leading-tight tracking-tight xl:text-5xl">
                    Todo tu lote,
                    <span class="text-blue-300">bajo control.</span>
                </h1>

                <p class="mt-6 max-w-lg text-lg leading-relaxed text-slate-300">
                    Administra inventario, financiamientos y cobranza desde un solo lugar, con información clara para tomar mejores decisiones.
                </p>

                <ul class="mt-9 grid gap-4 text-sm text-slate-200" aria-label="Beneficios de la plataforma">
                    <li class="flex items-center gap-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-400/10 text-emerald-300 ring-1 ring-inset ring-emerald-400/20">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 10 3 3 7-7"/>
                            </svg>
                        </span>
                        Seguimiento de contratos y pagos en tiempo real
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-400/10 text-emerald-300 ring-1 ring-inset ring-emerald-400/20">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 10 3 3 7-7"/>
                            </svg>
                        </span>
                        Control de inventario y clientes en una sola plataforma
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-400/10 text-emerald-300 ring-1 ring-inset ring-emerald-400/20">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 10 3 3 7-7"/>
                            </svg>
                        </span>
                        Acceso protegido según el rol de cada usuario
                    </li>
                </ul>
            </div>

            <div class="relative z-10 flex items-center gap-3 text-sm text-slate-400">
                <svg class="h-5 w-5 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m5.25 1.5c0 5.18-3.52 9.55-8.25 10.8-4.73-1.25-8.25-5.62-8.25-10.8V5.98L12 2.95l8.25 3.03v5.27Z"/>
                </svg>
                Acceso cifrado y sesiones protegidas
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
                    <p class="mb-2 text-sm font-semibold uppercase tracking-[0.16em] text-blue-700">Panel administrativo</p>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Bienvenido de nuevo</h2>
                    <p class="mt-3 text-base leading-relaxed text-slate-600">
                        Ingresa tus credenciales para continuar con la operación.
                    </p>
                </div>

                @session('status')
                    <div class="mb-6 flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 10 3 3 7-7"/>
                        </svg>
                        <span>{{ $value }}</span>
                    </div>
                @endsession

                @if ($errors->any())
                    <div class="mb-6 flex gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6.75v3.5m0 3h.01M17.25 10a7.25 7.25 0 1 1-14.5 0 7.25 7.25 0 0 1 14.5 0Z"/>
                        </svg>
                        <div>
                            <p class="font-semibold">No pudimos iniciar la sesión.</p>
                            <p class="mt-1 text-red-700">Revisa tu correo y contraseña e intenta nuevamente.</p>
                        </div>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('login') }}"
                      class="grid gap-5"
                      x-data="{ showPassword: false, submitting: false }"
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
                                   autofocus
                                   autocomplete="username"
                                   inputmode="email"
                                   @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        </div>
                        @error('email')
                            <p id="email-error" class="text-sm font-medium text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-2">
                        <div class="flex items-center justify-between gap-4">
                            <label for="password" class="text-sm font-semibold text-slate-800">Contraseña</label>
                            @if (Route::has('password.request'))
                                <a class="rounded text-sm font-semibold text-blue-700 underline-offset-4 hover:text-blue-900 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                                   href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <div class="relative">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 10.25V8a5.25 5.25 0 0 1 10.5 0v2.25m-11 0h11.5a1.5 1.5 0 0 1 1.5 1.5v7a1.5 1.5 0 0 1-1.5 1.5H6.25a1.5 1.5 0 0 1-1.5-1.5v-7a1.5 1.5 0 0 1 1.5-1.5Z"/>
                            </svg>
                            <input id="password"
                                   class="block min-h-12 w-full rounded-xl bg-white py-3 pl-12 pr-14 text-base text-slate-950 shadow-sm transition duration-200 placeholder:text-slate-400 focus:ring-4 {{ $errors->has('password') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-300 hover:border-slate-400 focus:border-blue-600 focus:ring-blue-600/10' }}"
                                   type="password"
                                   x-bind:type="showPassword ? 'text' : 'password'"
                                   name="password"
                                   placeholder="Ingresa tu contraseña"
                                   required
                                   autocomplete="current-password"
                                   @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                            <button type="button"
                                    class="absolute right-1.5 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-lg text-slate-500 transition-colors duration-200 hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                    x-on:click="showPassword = ! showPassword"
                                    aria-label="Mostrar contraseña"
                                    aria-pressed="false"
                                    x-bind:aria-label="showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                                    x-bind:aria-pressed="showPassword">
                                <svg x-show="! showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.75 12s3.5-6 9.25-6 9.25 6 9.25 6-3.5 6-9.25 6S2.75 12 2.75 12Z"/>
                                    <circle cx="12" cy="12" r="2.5"/>
                                </svg>
                                <svg x-cloak x-show="showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4 4 16 16M10.6 6.15A8.7 8.7 0 0 1 12 6c5.75 0 9.25 6 9.25 6a15 15 0 0 1-2.1 2.8M14.25 14.25A3.18 3.18 0 0 1 9.75 9.75M6.25 7.2C4 9.05 2.75 12 2.75 12s3.5 6 9.25 6c1.2 0 2.3-.25 3.25-.65"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p id="password-error" class="text-sm font-medium text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <label for="remember_me" class="flex min-h-11 cursor-pointer items-center gap-3 text-sm font-medium text-slate-700">
                        <input id="remember_me"
                               name="remember"
                               type="checkbox"
                               class="h-5 w-5 rounded border-slate-300 text-blue-700 shadow-sm focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                        Mantener mi sesión iniciada
                    </label>

                    <button type="submit"
                            class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-base font-bold text-white shadow-lg shadow-slate-950/15 transition duration-200 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 active:bg-blue-900 disabled:cursor-wait disabled:opacity-70"
                            x-bind:disabled="submitting">
                        <svg x-cloak x-show="submitting" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3"></circle>
                            <path class="opacity-90" fill="currentColor" d="M12 3a9 9 0 0 1 9 9h-3a6 6 0 0 0-6-6V3Z"></path>
                        </svg>
                        <span x-text="submitting ? 'Ingresando…' : 'Iniciar sesión'">Iniciar sesión</span>
                    </button>
                </form>

                <div class="mt-8 flex items-center justify-center gap-2 text-center text-xs text-slate-500">
                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 8.5V6a4.5 4.5 0 0 1 9 0v2.5m-9.5 0h10a1 1 0 0 1 1 1V16a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5a1 1 0 0 1 1-1Z"/>
                    </svg>
                    Área exclusiva para personal autorizado
                </div>
            </div>
        </section>
    </main>
</x-guest-layout>
