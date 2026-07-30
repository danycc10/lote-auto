<x-guest-layout>
    @section('title', 'Iniciar sesión')
    @section('meta_description', 'Acceso seguro al panel administrativo de ' . config('app.name'))

    <main class="relative flex min-h-dvh items-center justify-center overflow-hidden bg-slate-100 px-4 py-10 sm:px-6">
        <div class="absolute inset-x-0 top-0 h-1 bg-slate-900" aria-hidden="true"></div>
        <div class="absolute -left-32 -top-32 h-72 w-72 rounded-full bg-blue-200/40 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-32 -right-32 h-72 w-72 rounded-full bg-slate-300/60 blur-3xl" aria-hidden="true"></div>

        <div class="relative w-full max-w-md">
            <div class="rounded-2xl border border-slate-200 bg-white px-6 py-8 shadow-xl shadow-slate-900/5 sm:px-9 sm:py-10">
                <div class="flex justify-center">
                    <x-authentication-card-logo />
                </div>

                <div class="mt-7 text-center">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">Iniciar sesión</h1>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Accede al panel administrativo.</p>
                </div>

                @session('status')
                    <div class="mt-6 flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 10 3 3 7-7"/>
                        </svg>
                        <span>{{ $value }}</span>
                    </div>
                @endsession

                @if ($errors->any())
                    <div class="mt-6 flex gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6.75v3.5m0 3h.01M17.25 10a7.25 7.25 0 1 1-14.5 0 7.25 7.25 0 0 1 14.5 0Z"/>
                        </svg>
                        <p>Revisa tus credenciales e intenta nuevamente.</p>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('login') }}"
                      class="mt-7 grid gap-5"
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
                                   class="block min-h-12 w-full rounded-xl bg-white py-3 pl-12 pr-4 text-base text-slate-950 shadow-sm transition-colors duration-200 placeholder:text-slate-400 focus:ring-4 {{ $errors->has('email') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-300 hover:border-slate-400 focus:border-blue-700 focus:ring-blue-700/10' }}"
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
                                <a class="rounded text-sm font-semibold text-blue-700 underline-offset-4 hover:text-blue-900 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2"
                                   href="{{ route('password.request') }}">
                                    ¿La olvidaste?
                                </a>
                            @endif
                        </div>

                        <div class="relative">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 10.25V8a5.25 5.25 0 0 1 10.5 0v2.25m-11 0h11.5a1.5 1.5 0 0 1 1.5 1.5v7a1.5 1.5 0 0 1-1.5 1.5H6.25a1.5 1.5 0 0 1-1.5-1.5v-7a1.5 1.5 0 0 1 1.5-1.5Z"/>
                            </svg>
                            <input id="password"
                                   class="block min-h-12 w-full rounded-xl bg-white py-3 pl-12 pr-14 text-base text-slate-950 shadow-sm transition-colors duration-200 placeholder:text-slate-400 focus:ring-4 {{ $errors->has('password') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-300 hover:border-slate-400 focus:border-blue-700 focus:ring-blue-700/10' }}"
                                   type="password"
                                   x-bind:type="showPassword ? 'text' : 'password'"
                                   name="password"
                                   placeholder="Ingresa tu contraseña"
                                   required
                                   autocomplete="current-password"
                                   @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                            <button type="button"
                                    class="absolute right-1.5 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-lg text-slate-500 transition-colors duration-200 hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-700"
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
                               class="h-5 w-5 rounded border-slate-300 text-blue-700 shadow-sm focus:ring-2 focus:ring-blue-700 focus:ring-offset-2">
                        Mantener mi sesión iniciada
                    </label>

                    <button type="submit"
                            class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-base font-bold text-white shadow-lg shadow-slate-950/10 transition-colors duration-200 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 active:bg-blue-900 disabled:cursor-wait disabled:opacity-70"
                            x-bind:disabled="submitting">
                        <svg x-cloak x-show="submitting" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3"></circle>
                            <path class="opacity-90" fill="currentColor" d="M12 3a9 9 0 0 1 9 9h-3a6 6 0 0 0-6-6V3Z"></path>
                        </svg>
                        <span x-text="submitting ? 'Ingresando…' : 'Iniciar sesión'">Iniciar sesión</span>
                    </button>
                </form>
            </div>

            <div class="mt-6 flex items-center justify-center gap-3 text-sm">
                <a href="/"
                   class="rounded font-semibold text-slate-600 underline-offset-4 hover:text-slate-950 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2">
                    Volver al sitio
                </a>
                <span class="text-slate-300" aria-hidden="true">•</span>
                <span class="text-slate-500">Acceso autorizado</span>
            </div>
        </div>
    </main>
</x-guest-layout>
