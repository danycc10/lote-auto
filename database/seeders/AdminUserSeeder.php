<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'              => 'Administrador',
                'password'          => Hash::make('Admin1234!'),
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol solo si el rol ya existe (corre después de RolesPermisosSeeder
        // o en cualquier momento con php artisan db:seed --class=AdminUserSeeder)
        if (\Spatie\Permission\Models\Role::where('name', 'administrador')->exists()) {
            $user->syncRoles(['administrador']);
        }
    }
}
