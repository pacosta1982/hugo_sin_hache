<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PointsService;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PointsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PointsService $pointsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pointsService = app(PointsService::class);
    }

    public function test_can_redeem_product_successfully()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000, 'puntos_canjeados' => 0]);
        $product = Product::factory()->create(['costo_puntos' => 500, 'stock' => 10]);

        $result = $this->pointsService->redeemProduct($employee, $product);

        $this->assertTrue($result['success']);
        $this->assertInstanceOf(Order::class, $result['order']);
        $this->assertEquals(500, $result['remaining_points']);
        

        $employee->refresh();
        $this->assertEquals(500, $employee->puntos_totales);
        $this->assertEquals(500, $employee->puntos_canjeados);
        

        $product->refresh();
        $this->assertEquals(9, $product->stock);
    }

    public function test_cannot_redeem_product_with_insufficient_points()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 100]);
        $product = Product::factory()->create(['costo_puntos' => 500]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Puntos insuficientes para canjear este producto.');

        $this->pointsService->redeemProduct($employee, $product);
    }

    public function test_cannot_redeem_inactive_product()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000]);
        $product = Product::factory()->inactive()->create(['costo_puntos' => 500]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('El producto no está disponible para canje.');

        $this->pointsService->redeemProduct($employee, $product);
    }

    public function test_cannot_redeem_out_of_stock_product()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000]);
        $product = Product::factory()->create(['costo_puntos' => 500, 'stock' => 0, 'activo' => true]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('El producto no está disponible para canje.');

        $this->pointsService->redeemProduct($employee, $product);
    }

    public function test_can_redeem_unlimited_stock_product()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000]);
        $product = Product::factory()->unlimited()->create(['costo_puntos' => 500]);

        $result = $this->pointsService->redeemProduct($employee, $product);

        $this->assertTrue($result['success']);
        

        $product->refresh();
        $this->assertEquals(-1, $product->stock);
    }

    public function test_can_cancel_order_and_refund_points()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 500, 'puntos_canjeados' => 500]);
        $order = Order::factory()->pending()->forEmployee($employee)->create(['puntos_utilizados' => 300]);

        $result = $this->pointsService->cancelOrder($order, 'Test cancellation');

        $this->assertTrue($result['success']);
        $this->assertEquals(300, $result['refunded_points']);
        

        $employee->refresh();
        $this->assertEquals(800, $employee->puntos_totales);
        $this->assertEquals(200, $employee->puntos_canjeados);
        

        $order->refresh();
        $this->assertEquals(Order::STATUS_CANCELLED, $order->estado);
    }

    public function test_cannot_cancel_completed_order()
    {
        $order = Order::factory()->completed()->create();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Este pedido no puede ser cancelado en su estado actual.');

        $this->pointsService->cancelOrder($order);
    }

    public function test_can_get_points_summary()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000, 'puntos_canjeados' => 500]);
        

        Order::factory()->count(3)->completed()->forEmployee($employee)->create();
        Order::factory()->count(2)->pending()->forEmployee($employee)->create();

        $summary = $this->pointsService->getPointsSummary($employee);

        $this->assertEquals(1000, $summary['available_points']);
        $this->assertEquals(500, $summary['redeemed_points']);
        $this->assertEquals(1500, $summary['total_points_earned']);
        $this->assertEquals(5, $summary['total_orders']);
        $this->assertEquals(2, $summary['pending_orders']);
        $this->assertEquals(3, $summary['completed_orders']);
    }

    public function test_can_check_if_employee_can_afford_product()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000]);
        $affordableProduct = Product::factory()->create(['costo_puntos' => 500]);
        $expensiveProduct = Product::factory()->create(['costo_puntos' => 1500]);

        $this->assertTrue($this->pointsService->canAffordProduct($employee, $affordableProduct));
        $this->assertFalse($this->pointsService->canAffordProduct($employee, $expensiveProduct));
    }

    public function test_can_get_recommended_products()
    {
        $employee = Employee::factory()->create(['puntos_totales' => 1000]);
        

        Product::factory()->create(['costo_puntos' => 2000]); // Too expensive
        $product1 = Product::factory()->create(['costo_puntos' => 800]);
        $product2 = Product::factory()->create(['costo_puntos' => 500]);
        $product3 = Product::factory()->create(['costo_puntos' => 200]);

        $recommended = $this->pointsService->getRecommendedProducts($employee, 5);

        $this->assertCount(3, $recommended);
        $this->assertEquals($product1->id, $recommended->first()->id); // Highest value first
    }
}