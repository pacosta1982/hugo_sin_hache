<?php

namespace Tests\Feature\Feature\Api;

use Tests\TestCase;

class HealthApiTest extends TestCase
{
    public function test_basic_health_check_returns_ok(): void
    {
        $response = $this->get('/api/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'service',
                'version'
            ])
            ->assertJson([
                'status' => 'ok',
                'service' => 'UGo Points System'
            ]);
    }

    public function test_detailed_health_check_returns_status(): void
    {
        $response = $this->get('/api/health/detailed');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'checks'
            ]);
    }

    public function test_performance_metrics_endpoint(): void
    {
        $response = $this->get('/api/health/performance');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'timestamp',
                'response_time_ms',
                'memory_usage',
                'php',
                'laravel'
            ]);
    }

    public function test_liveness_probe_endpoint(): void
    {
        $response = $this->get('/api/health/live');

        $response->assertStatus(200)
            ->assertJson([
                'alive' => true
            ]);
    }

    public function test_readiness_probe_endpoint(): void
    {
        $response = $this->get('/api/health/ready');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'ready',
                'timestamp',
                'checks'
            ]);
    }

    public function test_config_endpoint_returns_configuration(): void
    {
        $response = $this->get('/api/health/config');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'timestamp',
                'configuration' => [
                    'app',
                    'database',
                    'cache',
                    'firebase'
                ],
                'environment_variables'
            ]);
    }
}
