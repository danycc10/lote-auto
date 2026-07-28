<?php

namespace Tests\Feature\Operations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_check_verifica_base_de_datos_y_cache(): void
    {
        $this->get('/up')
            ->assertOk();

        $this->assertDatabaseCount('cache', 0);
    }
}
