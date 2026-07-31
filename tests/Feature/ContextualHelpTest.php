<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContextualHelpTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_screen_displays_contextual_help(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('profile.show'))
            ->assertOk()
            ->assertSeeText('Ayuda')
            ->assertSeeText('Mi perfil y seguridad')
            ->assertSee('aria-label="Abrir ayuda de esta pantalla"', false)
            ->assertSee('aria-modal="true"', false);
    }

    public function test_every_admin_screen_has_specific_help_content(): void
    {
        $configuredScreens = config('screen-help.screens');
        $this->assertIsArray($configuredScreens);

        $adminScreenRoutes = collect(Route::getRoutes()->getRoutes())
            ->filter(function ($route): bool {
                $name = $route->getName();

                return is_string($name)
                    && in_array('GET', $route->methods(), true)
                    && ($name === 'dashboard' || Str::startsWith($name, 'admin.'))
                    && ! Str::endsWith($name, ['.pdf', '.export', '.archivo', '.download']);
            })
            ->map(fn ($route): string => (string) $route->getName())
            ->values();

        $this->assertGreaterThan(20, $adminScreenRoutes->count());

        foreach ($adminScreenRoutes as $routeName) {
            $this->assertArrayHasKey($routeName, $configuredScreens, "Falta ayuda contextual para la ruta [{$routeName}].");
        }
    }

    public function test_help_content_has_the_required_structure(): void
    {
        foreach (config('screen-help.screens') as $routeName => $help) {
            $this->assertNotEmpty($help['title'], "Falta el título de ayuda para [{$routeName}].");
            $this->assertNotEmpty($help['purpose'], "Falta el propósito de ayuda para [{$routeName}].");
            $this->assertNotEmpty($help['information_title'], "Falta el título de información para [{$routeName}].");
            $this->assertGreaterThanOrEqual(2, count($help['steps']), "Faltan pasos de uso para [{$routeName}].");
            $this->assertGreaterThanOrEqual(2, count($help['information']), "Falta información contextual para [{$routeName}].");
        }
    }
}
