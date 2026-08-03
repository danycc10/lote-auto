<?php

namespace Tests\Feature;

use App\Models\Configuracion;
use App\Models\User;
use Database\Seeders\RolesPermisosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_logo_persiste_durante_la_navegacion_del_menu(): void
    {
        Configuracion::establecer('branding.logo_url', 'branding/logo.webp');
        $this->actingAs(User::factory()->create());

        $html = (string) $this->view('navigation-menu');

        $this->assertStringContainsString('x-persist="admin-sidebar-logo"', $html);
        $this->assertStringContainsString('wire:ignore', $html);
        $this->assertStringContainsString('src="/storage/branding/logo.webp"', $html);
        $this->assertStringContainsString('width="32"', $html);
        $this->assertStringContainsString('height="32"', $html);
    }

    public function test_los_enlaces_del_menu_usan_navegacion_livewire(): void
    {
        $this->actingAs(User::factory()->create());

        $html = (string) $this->view('navigation-menu');
        preg_match_all('/<a\b[^>]*href="[^"]+"[^>]*>/', $html, $coincidencias);

        $this->assertNotEmpty($coincidencias[0]);

        foreach ($coincidencias[0] as $enlace) {
            $this->assertStringContainsString('wire:navigate', $enlace);
        }
    }

    public function test_el_menu_respeta_los_permisos_exigidos_por_las_rutas(): void
    {
        $this->seed(RolesPermisosSeeder::class);

        $user = User::factory()->create();
        $user->givePermissionTo('dashboard.ver');
        $this->actingAs($user);

        $html = (string) $this->view('navigation-menu');

        $this->assertStringNotContainsString(route('admin.reportes.index'), $html);
        $this->assertStringNotContainsString(route('admin.administracion.tarjetas-cobro'), $html);

        $user->givePermissionTo(['reportes.ver', 'seguridad.roles']);
        $html = (string) $this->view('navigation-menu');

        $this->assertStringContainsString(route('admin.reportes.index'), $html);
        $this->assertStringContainsString(route('admin.administracion.tarjetas-cobro'), $html);
    }
}
