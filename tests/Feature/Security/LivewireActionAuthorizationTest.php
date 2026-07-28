<?php

namespace Tests\Feature\Security;

use App\Livewire\Admin\ApartadosAutos\Index as ApartadosIndex;
use App\Livewire\Admin\Autos\Index as AutosIndex;
use App\Livewire\Admin\CobranzaAutos\Dashboard;
use App\Livewire\Admin\Prospectos\Index as ProspectosIndex;
use App\Livewire\Admin\Sistema\BrandingIndex;
use App\Livewire\Admin\Sistema\ConfiguracionIndex;
use App\Livewire\Admin\Sistema\LandingTemplateIndex;
use App\Models\User;
use Database\Seeders\RolesPermisosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class LivewireActionAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $auditor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesPermisosSeeder::class);
        $this->auditor = User::factory()->create();
        $this->auditor->assignRole('auditor');
        $this->actingAs($this->auditor);
    }

    public function test_lector_de_autos_no_puede_cambiar_publicacion(): void
    {
        Livewire::test(AutosIndex::class)
            ->call('toggleActivo', 999)
            ->assertForbidden();
    }

    public function test_lector_de_apartados_no_puede_cancelarlos(): void
    {
        Livewire::test(ApartadosIndex::class)
            ->call('confirmarCancelacion', 999)
            ->assertForbidden();
    }

    public function test_lector_de_clientes_no_puede_mutar_prospectos(): void
    {
        Livewire::test(ProspectosIndex::class)
            ->call('abrirModalNuevo')
            ->assertForbidden();
    }

    public function test_usuario_de_dashboard_no_puede_enviar_notificaciones(): void
    {
        $this->expectException(HttpException::class);

        app(Dashboard::class)->abrirModalIndividual(999);
    }

    public function test_usuario_sin_permiso_no_puede_abrir_configuracion_del_sistema(): void
    {
        Livewire::test(ConfiguracionIndex::class)->assertForbidden();
        Livewire::test(BrandingIndex::class)->assertForbidden();
        Livewire::test(LandingTemplateIndex::class)->assertForbidden();
    }
}
