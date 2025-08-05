<?php

namespace Tests\Feature\Api;

use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $employee;
    protected Employee $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->employee = Employee::factory()->create([
            'puntos_totales' => 1000,
            'rol_usuario' => 'Empleado'
        ]);
        
        $this->admin = Employee::factory()->create([
            'puntos_totales' => 2000,
            'rol_usuario' => 'Administrador'
        ]);
    }

    public function test_employee_can_view_own_order_history()
    {

        $ownOrders = Order::factory()->count(3)->create([
            'empleado_id' => $this->employee->id_empleado,
            'empleado_nombre' => $this->employee->nombre
        ]);


        Order::factory()->count(2)->create([
            'empleado_id' => 'another-employee-id',
            'empleado_nombre' => 'Another Employee'
        ]);

        $response = $this->actingAs($this->employee)
                         ->getJson('/pedidos');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'empleado_id',
                             'producto_id',
                             'fecha',
                             'estado',
                             'puntos_utilizados',
                             'producto_nombre',
                             'empleado_nombre',
                             'observaciones',
                             'is_pending',
                             'is_completed',
                             'can_be_cancelled',
                             'created_at',
                             'updated_at'
                         ]
                     ]
                 ]);


        $this->assertCount(3, $response->json('data'));
        
        foreach ($response->json('data') as $order) {
            $this->assertEquals($this->employee->id_empleado, $order['empleado_id']);
        }
    }

    public function test_can_filter_orders_by_status()
    {
        Order::factory()->create([
            'empleado_id' => $this->employee->id_empleado,
            'estado' => 'Pendiente'
        ]);
        
        Order::factory()->create([
            'empleado_id' => $this->employee->id_empleado,
            'estado' => 'Realizado'
        ]);

        $response = $this->actingAs($this->employee)
                         ->getJson('/pedidos?estado=Pendiente');

        $response->assertStatus(200);
        $orders = $response->json('data');
        $this->assertCount(1, $orders);
        $this->assertEquals('Pendiente', $orders[0]['estado']);
    }

    public function test_can_view_specific_order_details()
    {
        $order = Order::factory()->create([
            'empleado_id' => $this->employee->id_empleado,
            'empleado_nombre' => $this->employee->nombre,
            'observaciones' => 'Test order details'
        ]);

        $response = $this->actingAs($this->employee)
                         ->getJson("/pedidos/{$order->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'id',
                     'empleado_id',
                     'producto_id',
                     'fecha',
                     'estado',
                     'puntos_utilizados',
                     'producto_nombre',
                     'empleado_nombre',
                     'observaciones',
                     'created_at',
                     'updated_at'
                 ]);

        $this->assertEquals('Test order details', $response->json('observaciones'));
    }

    public function test_cannot_view_other_employee_orders()
    {
        $otherOrder = Order::factory()->create([
            'empleado_id' => 'another-employee-id',
            'empleado_nombre' => 'Another Employee'
        ]);

        $response = $this->actingAs($this->employee)
                         ->getJson("/pedidos/{$otherOrder->id}");

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_view_all_orders()
    {

        Order::factory()->count(3)->create(['empleado_id' => $this->employee->id_empleado]);
        Order::factory()->count(2)->create(['empleado_id' => 'another-employee']);

        $response = $this->actingAs($this->admin)
                         ->getJson('/admin/pedidos');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_admin_can_filter_orders_by_employee()
    {
        Order::factory()->count(2)->create(['empleado_id' => $this->employee->id_empleado]);
        Order::factory()->count(3)->create(['empleado_id' => 'another-employee']);

        $response = $this->actingAs($this->admin)
                         ->getJson("/admin/pedidos?empleado_id={$this->employee->id_empleado}");

        $response->assertStatus(200);
        $orders = $response->json('data');
        $this->assertCount(2, $orders);
        
        foreach ($orders as $order) {
            $this->assertEquals($this->employee->id_empleado, $order['empleado_id']);
        }
    }

    public function test_admin_can_filter_orders_by_date_range()
    {

        Order::factory()->create([
            'empleado_id' => $this->employee->id_empleado,
            'created_at' => now()->subDays(10)
        ]);
        

        Order::factory()->create([
            'empleado_id' => $this->employee->id_empleado,
            'created_at' => now()
        ]);

        $fromDate = now()->subDays(5)->format('Y-m-d');
        
        $response = $this->actingAs($this->admin)
                         ->getJson("/admin/pedidos?fecha_desde={$fromDate}");

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_admin_can_update_order_status()
    {
        $order = Order::factory()->create([
            'empleado_id' => $this->employee->id_empleado,
            'estado' => 'Pendiente'
        ]);

        $response = $this->actingAs($this->admin)
                         ->putJson("/admin/pedidos/{$order->id}/estado", [
                             'estado' => 'En curso',
                             'observaciones_admin' => 'Procesando pedido'
                         ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Estado del pedido actualizado correctamente'
                 ]);


        $order->refresh();
        $this->assertEquals('En curso', $order->estado);
    }

    public function test_employee_cannot_access_admin_endpoints()
    {
        $response = $this->actingAs($this->employee)
                         ->getJson('/admin/pedidos');

        $response->assertStatus(403); // Forbidden
    }

    public function test_validates_order_status_update_request()
    {
        $order = Order::factory()->create([
            'empleado_id' => $this->employee->id_empleado,
            'estado' => 'Pendiente'
        ]);


        $response = $this->actingAs($this->admin)
                         ->putJson("/admin/pedidos/{$order->id}/estado", [
                             'estado' => 'Invalid Status'
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['estado']);
    }

    public function test_respects_pagination_for_order_history()
    {
        Order::factory()->count(25)->create([
            'empleado_id' => $this->employee->id_empleado
        ]);

        $response = $this->actingAs($this->employee)
                         ->getJson('/pedidos?per_page=10&page=1');

        $response->assertStatus(200);
        $this->assertCount(10, $response->json('data'));
        
        $response->assertJsonStructure([
            'data',
            'meta' => [
                'current_page',
                'per_page',
                'total',
                'last_page'
            ]
        ]);
    }

    public function test_order_includes_related_product_information()
    {
        $product = Product::factory()->create([
            'nombre' => 'Test Product',
            'descripcion' => 'Test Description'
        ]);

        $order = Order::factory()->create([
            'empleado_id' => $this->employee->id_empleado,
            'producto_id' => $product->id,
            'producto_nombre' => $product->nombre
        ]);

        $response = $this->actingAs($this->employee)
                         ->getJson("/pedidos/{$order->id}?include=product");

        $response->assertStatus(200);


        $orderData = $response->json();
        $this->assertEquals($product->nombre, $orderData['producto_nombre']);
        
        if (isset($orderData['product'])) {
            $this->assertEquals($product->descripcion, $orderData['product']['descripcion']);
        }
    }

    public function test_requires_authentication_for_order_endpoints()
    {
        $order = Order::factory()->create();


        $response = $this->getJson('/pedidos');
        $response->assertStatus(401);

        $response = $this->getJson("/pedidos/{$order->id}");
        $response->assertStatus(401);

        $response = $this->getJson('/admin/pedidos');
        $response->assertStatus(401);
    }

    public function actingAs($user, $driver = null)
    {

        $this->app->instance('firebase.auth', $user);
        
        return $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer mock-firebase-token'
        ]);
    }
}