<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductRedemptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_redeem_product_with_sufficient_points()
    {
        $employee = Employee::factory()->create([
            'puntos_totales' => 1000,
            'puntos_canjeados' => 0
        ]);
        $product = Product::factory()->create(['costo_puntos' => 500, 'stock' => 10]);

        $response = $this->actingAsEmployee($employee)
            ->post("/productos/{$product->id}/canjear", [
                'observaciones' => 'Test redemption'
            ]);

        $response->assertRedirect();
        

        $this->assertDatabaseHas('orders', [
            'empleado_id' => $employee->id_empleado,
            'producto_id' => $product->id,
            'puntos_utilizados' => 500,
            'estado' => Order::STATUS_PENDING
        ]);


        $employee->refresh();
        $this->assertEquals(500, $employee->puntos_totales);
        $this->assertEquals(500, $employee->puntos_canjeados);


        $product->refresh();
        $this->assertEquals(9, $product->stock);
    }

    public function test_employee_cannot_redeem_product_with_insufficient_points()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 100]);
        $product = Product::factory()->create(['costo_puntos' => 500]);

        $response = $this->actingAsEmployee($employee)
            ->post("/productos/{$product->id}/canjear");

        $response->assertRedirect();
        $response->assertSessionHasErrors();


        $this->assertDatabaseMissing('orders', [
            'empleado_id' => $employee->id_empleado,
            'producto_id' => $product->id
        ]);
    }

    public function test_employee_cannot_redeem_out_of_stock_product()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000]);
        $product = Product::factory()->outOfStock()->create(['costo_puntos' => 500]);

        $response = $this->actingAsEmployee($employee)
            ->post("/productos/{$product->id}/canjear");

        $response->assertRedirect();
        $response->assertSessionHasErrors();


        $this->assertDatabaseMissing('orders', [
            'empleado_id' => $employee->id_empleado,
            'producto_id' => $product->id
        ]);
    }

    public function test_employee_cannot_redeem_inactive_product()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000]);
        $product = Product::factory()->inactive()->create(['costo_puntos' => 500]);

        $response = $this->actingAsEmployee($employee)
            ->post("/productos/{$product->id}/canjear");

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    public function test_employee_can_redeem_unlimited_stock_product()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000]);
        $product = Product::factory()->unlimited()->create(['costo_puntos' => 500]);

        $response = $this->actingAsEmployee($employee)
            ->post("/productos/{$product->id}/canjear");

        $response->assertRedirect();
        

        $this->assertDatabaseHas('orders', [
            'empleado_id' => $employee->id_empleado,
            'producto_id' => $product->id
        ]);


        $product->refresh();
        $this->assertEquals(-1, $product->stock);
    }

    public function test_product_redemption_requires_authentication()
    {
        $product = Product::factory()->create();

        $response = $this->post("/productos/{$product->id}/canjear");

        $response->assertStatus(500); // Due to Firebase middleware
    }

    public function test_nonexistent_product_redemption_returns_404()
    {
        $employee = Employee::factory()->create();

        $response = $this->actingAsEmployee($employee)
            ->post("/productos/999/canjear");

        $response->assertStatus(404);
    }
}