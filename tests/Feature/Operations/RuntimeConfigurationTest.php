<?php

namespace Tests\Feature\Operations;

use Tests\TestCase;

class RuntimeConfigurationTest extends TestCase
{
    public function test_operaciones_asincronas_esperan_el_commit(): void
    {
        $this->assertTrue(config('queue.connections.database.after_commit'));
        $this->assertTrue(config('queue.connections.redis.after_commit'));
        $this->assertTrue(config('queue.connections.sqs.after_commit'));
        $this->assertTrue(config('queue.connections.beanstalkd.after_commit'));
    }

    public function test_reintento_de_cola_supera_el_timeout_de_notificaciones(): void
    {
        $this->assertGreaterThan(60, config('queue.connections.database.retry_after'));
        $this->assertGreaterThan(60, config('queue.connections.redis.retry_after'));
        $this->assertGreaterThan(60, config('queue.connections.beanstalkd.retry_after'));
    }

    public function test_aplicacion_declara_una_zona_horaria_de_negocio(): void
    {
        $this->assertSame('America/Matamoros', config('app.timezone'));
    }
}
