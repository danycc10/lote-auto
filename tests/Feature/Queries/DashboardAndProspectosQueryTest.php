<?php

namespace Tests\Feature\Queries;

use App\Livewire\Admin\CobranzaAutos\Dashboard;
use App\Livewire\Admin\Prospectos\Index as ProspectosIndex;
use App\Models\Prospecto;
use App\Models\User;
use Database\Seeders\RolesPermisosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardAndProspectosQueryTest extends TestCase
{
    use RefreshDatabase;

    private User $administrator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesPermisosSeeder::class);
        $this->administrator = User::factory()->create();
        $this->administrator->assignRole('administrador');
        $this->actingAs($this->administrator);
    }

    public function test_dashboard_renderiza_en_sqlite_y_busca_por_la_columna_placa(): void
    {
        Livewire::test(Dashboard::class)
            ->set('q', 'ABC-123')
            ->assertOk();
    }

    public function test_busqueda_de_prospectos_respeta_el_filtro_de_estatus(): void
    {
        Prospecto::create(['nombre' => 'Coincidencia Nueva', 'estatus' => 'nuevo']);
        Prospecto::create(['nombre' => 'Coincidencia Ganada', 'estatus' => 'ganado']);

        Livewire::test(ProspectosIndex::class)
            ->set('q', 'Coincidencia')
            ->set('estatus', 'nuevo')
            ->assertSee('Coincidencia Nueva')
            ->assertDontSee('Coincidencia Ganada');
    }

    public function test_rechaza_un_estatus_de_prospecto_fuera_del_enum(): void
    {
        $prospect = Prospecto::create(['nombre' => 'Prospecto', 'estatus' => 'nuevo']);

        Livewire::test(ProspectosIndex::class)
            ->call('cambiarEstatus', $prospect->id, 'inventado')
            ->assertHasErrors(['estatus']);

        $this->assertSame('nuevo', $prospect->fresh()->estatus);
    }
}
