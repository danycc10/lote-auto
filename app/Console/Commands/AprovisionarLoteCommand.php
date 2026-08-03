<?php

namespace App\Console\Commands;

use App\Models\Configuracion;
use App\Models\User;
use Database\Seeders\RolesPermisosSeeder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use RuntimeException;

#[Signature('lote:aprovisionar {--name= : Nombre comercial del lote} {--slug= : Identificador corto} {--admin-email= : Correo del administrador} {--force : Actualiza una instalación existente}')]
#[Description('Configura la identidad y el administrador inicial de una instalación independiente.')]
class AprovisionarLoteCommand extends Command
{
    public function handle(): int
    {
        $existingUuid = Configuracion::obtener('instalacion.uuid');

        if (filled($existingUuid) && ! $this->option('force')) {
            $this->error('La instalación ya está aprovisionada. Usa --force únicamente para actualizar su identidad.');

            return self::FAILURE;
        }

        $name = trim((string) ($this->option('name') ?: $this->ask('Nombre comercial del lote')));
        $slug = trim((string) ($this->option('slug') ?: Str::slug($name)));
        $email = trim((string) ($this->option('admin-email') ?: config('bootstrap.admin.email') ?: $this->ask('Correo del administrador')));
        $existingUser = User::query()->where('email', $email)->first();
        $password = $existingUser ? null : config('bootstrap.admin.password');

        if (! $existingUser && blank($password)) {
            $password = $this->secret('Contraseña inicial del administrador');
        }

        $data = [
            'name' => $name,
            'slug' => $slug,
            'email' => $email,
            'password' => $password,
        ];
        $rules = [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'alpha_dash:ascii', 'max:80'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'password' => $existingUser
                ? ['nullable']
                : ['required', 'string', Password::min(12)->letters()->mixedCase()->numbers()->symbols()],
        ];
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $this->call('db:seed', [
            '--class' => RolesPermisosSeeder::class,
            '--force' => true,
        ]);

        $uuid = (string) ($existingUuid ?: Str::uuid());

        DB::transaction(function () use ($data, $existingUser, $uuid): void {
            $user = $existingUser ?: User::query()->create([
                'name' => 'Administrador',
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            if (! $existingUser) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            $user->syncRoles(['administrador']);

            Configuracion::establecer('instalacion.uuid', $uuid);
            Configuracion::establecer('instalacion.nombre', $data['name']);
            Configuracion::establecer('instalacion.slug', $data['slug']);
            Configuracion::establecer('instalacion.version', (string) config('app.version'));

            if (blank(Configuracion::obtener('instalacion.instalada_at'))) {
                Configuracion::establecer('instalacion.instalada_at', now()->toIso8601String());
            }

            if (blank(Configuracion::obtener('branding.seo_titulo'))) {
                Configuracion::establecer('branding.seo_titulo', $data['name']);
            }
        });

        if (blank(Configuracion::obtener('instalacion.uuid'))) {
            throw new RuntimeException('No fue posible guardar la identidad de la instalación.');
        }

        $this->info('Lote aprovisionado correctamente.');
        $this->line('Identificador: '.$uuid);
        $this->line('Prefijo remoto recomendado: backups/'.$slug);

        return self::SUCCESS;
    }
}
