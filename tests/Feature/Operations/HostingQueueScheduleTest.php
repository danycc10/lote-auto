<?php

namespace Tests\Feature\Operations;

use App\Models\EstadoOperacion;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostingQueueScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_scheduler_registra_su_ultima_ejecucion(): void
    {
        config(['hosting.queue_worker.mode' => 'external']);

        $this->artisan('schedule:run')->assertSuccessful();

        $heartbeat = EstadoOperacion::query()->where('clave', 'scheduler')->first();

        $this->assertNotNull($heartbeat);
        $this->assertSame('ok', $heartbeat->estado);
        $this->assertNotNull($heartbeat->ejecutado_at);
    }

    public function test_la_programacion_declara_un_worker_acotado_para_cpanel(): void
    {
        $worker = collect(app(Schedule::class)->events())
            ->first(fn ($event): bool => str_contains((string) $event->command, 'queue:work'));

        $this->assertNotNull($worker);
        $this->assertStringContainsString('--stop-when-empty', $worker->command);
        $this->assertStringContainsString('--timeout=300', $worker->command);
        $this->assertStringContainsString('--max-time=50', $worker->command);
    }
}
