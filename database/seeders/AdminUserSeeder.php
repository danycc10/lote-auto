<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use RuntimeException;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('bootstrap.admin.email');
        $password = config('bootstrap.admin.password');

        if (blank($email) && blank($password)) {
            $this->command?->warn(
                'No se creó un administrador. Configure INITIAL_ADMIN_EMAIL e INITIAL_ADMIN_PASSWORD para habilitar el bootstrap.'
            );

            return;
        }

        if (blank($email) || blank($password)) {
            throw new RuntimeException(
                'INITIAL_ADMIN_EMAIL e INITIAL_ADMIN_PASSWORD deben configurarse juntos.'
            );
        }

        $credentials = Validator::validate(
            ['email' => $email, 'password' => $password],
            [
                'email' => ['required', 'email:rfc', 'max:255'],
                'password' => ['required', 'string', Password::min(12)->letters()->mixedCase()->numbers()->symbols()],
            ],
        );

        $user = User::firstOrCreate(
            ['email' => $credentials['email']],
            [
                'name' => 'Administrador',
                'password' => $credentials['password'],
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol solo si el rol ya existe (corre después de RolesPermisosSeeder
        // o en cualquier momento con php artisan db:seed --class=AdminUserSeeder)
        if (Role::where('name', 'administrador')->exists()) {
            $user->syncRoles(['administrador']);
        }
    }
}
