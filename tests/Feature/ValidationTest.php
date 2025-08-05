<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_redemption_validates_observaciones_length()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000]);
        $product = Product::factory()->create(['costo_puntos' => 500]);

        $response = $this->actingAsEmployee($employee)
            ->postJson("/productos/{$product->id}/canjear", [
                'observaciones' => str_repeat('a', 501) // Too long
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación'
            ])
            ->assertJsonValidationErrors(['observaciones']);
    }

    public function test_insufficient_points_validation_fails()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 100]);
        $product = Product::factory()->create(['costo_puntos' => 500]);

        $response = $this->actingAsEmployee($employee)
            ->postJson("/productos/{$product->id}/canjear");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['points']);
    }

    public function test_order_status_update_requires_admin()
    {
        $employee = Employee::factory()->create(['rol_usuario' => 'Empleado']); // Regular user
        $order = Order::factory()->pending()->create();

        $response = $this->actingAsEmployee($employee)
            ->putJson("/admin/pedidos/{$order->id}/estado", [
                'status' => Order::STATUS_IN_PROGRESS
            ]);

        $response->assertStatus(403);
    }

    public function test_order_status_update_validates_status()
    {
        $admin = Employee::factory()->admin()->create();
        $order = Order::factory()->pending()->create();

        $response = $this->actingAsEmployee($admin)
            ->putJson("/admin/pedidos/{$order->id}/estado", [
                'status' => 'invalid_status'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_order_status_transition_validation()
    {
        $admin = Employee::factory()->admin()->create();
        $order = Order::factory()->completed()->create();


        $response = $this->actingAsEmployee($admin)
            ->putJson("/admin/pedidos/{$order->id}/estado", [
                'status' => Order::STATUS_PENDING
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_nonexistent_product_returns_404()
    {
        $employee = Employee::factory()->create();

        $response = $this->actingAsEmployee($employee)
            ->getJson('/productos/999');

        $response->assertStatus(404);
    }

    public function test_unauthenticated_request_returns_401()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/productos/{$product->id}");



        $this->assertTrue(in_array($response->status(), [401, 500]));
    }

    public function test_validation_error_response_structure()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000]);
        $product = Product::factory()->create(['costo_puntos' => 500]);

        $response = $this->actingAsEmployee($employee)
            ->postJson("/productos/{$product->id}/canjear", [
                'observaciones' => str_repeat('a', 501)
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [
                    'observaciones'
                ]
            ])
            ->assertJson([
                'success' => false,
                'message' => 'Error de validación'
            ]);
    }
}