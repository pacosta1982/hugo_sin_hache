<?php

namespace Tests\Feature\Api;

use App\Models\Employee;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
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

    public function test_can_list_products_as_json()
    {

        Product::factory()->count(5)->create(['activo' => true, 'stock' => 10]);
        Product::factory()->count(2)->create(['activo' => false]);

        $response = $this->actingAs($this->employee)
                         ->getJson('/productos?per_page=10');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'nombre',
                             'descripcion',
                             'categoria',
                             'costo_puntos',
                             'stock',
                             'activo',
                             'is_available',
                             'created_at',
                             'updated_at'
                         ]
                     ],
                     'meta' => [
                         'current_page',
                         'per_page',
                         'total'
                     ]
                 ]);


        $this->assertCount(5, $response->json('data'));
    }

    public function test_can_search_products()
    {
        Product::factory()->create([
            'nombre' => 'iPhone 15',
            'categoria' => 'Tecnología',
            'activo' => true,
            'stock' => 5
        ]);
        
        Product::factory()->create([
            'nombre' => 'MacBook Pro',
            'categoria' => 'Tecnología', 
            'activo' => true,
            'stock' => 3
        ]);


        $response = $this->actingAs($this->employee)
                         ->getJson('/productos?search=iPhone');

        $response->assertStatus(200);
        $products = $response->json('data');
        $this->assertCount(1, $products);
        $this->assertEquals('iPhone 15', $products[0]['nombre']);


        $response = $this->actingAs($this->employee)
                         ->getJson('/productos?category=Tecnología');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_can_filter_products_by_max_points()
    {
        Product::factory()->create(['costo_puntos' => 500, 'activo' => true]);
        Product::factory()->create(['costo_puntos' => 1500, 'activo' => true]);

        $response = $this->actingAs($this->employee)
                         ->getJson('/productos?max_points=1000');

        $response->assertStatus(200);
        $products = $response->json('data');
        $this->assertCount(1, $products);
        $this->assertEquals(500, $products[0]['costo_puntos']);
    }

    public function test_can_sort_products()
    {
        Product::factory()->create(['nombre' => 'Z Product', 'costo_puntos' => 100, 'activo' => true]);
        Product::factory()->create(['nombre' => 'A Product', 'costo_puntos' => 200, 'activo' => true]);


        $response = $this->actingAs($this->employee)
                         ->getJson('/productos?sort_by=nombre&sort_direction=asc');

        $response->assertStatus(200);
        $products = $response->json('data');
        $this->assertEquals('A Product', $products[0]['nombre']);


        $response = $this->actingAs($this->employee)
                         ->getJson('/productos?sort_by=costo_puntos&sort_direction=desc');

        $response->assertStatus(200);
        $products = $response->json('data');
        $this->assertEquals(200, $products[0]['costo_puntos']);
    }

    public function test_can_redeem_product_successfully()
    {
        $product = Product::factory()->create([
            'costo_puntos' => 500,
            'stock' => 10,
            'activo' => true
        ]);

        $response = $this->actingAs($this->employee)
                         ->postJson("/productos/{$product->id}/canjear", [
                             'observaciones' => 'Producto para prueba'
                         ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'empleado_id',
                         'producto_id',
                         'puntos_utilizados',
                         'estado',
                         'observaciones'
                     ]
                 ]);

        $this->assertTrue($response->json('success'));
        

        $this->employee->refresh();
        $this->assertEquals(500, $this->employee->puntos_totales); // 1000 - 500
        $this->assertEquals(500, $this->employee->puntos_canjeados);


        $product->refresh();
        $this->assertEquals(9, $product->stock);
    }

    public function test_cannot_redeem_product_with_insufficient_points()
    {
        $product = Product::factory()->create([
            'costo_puntos' => 1500, // More than employee's 1000 points
            'stock' => 10,
            'activo' => true
        ]);

        $response = $this->actingAs($this->employee)
                         ->postJson("/productos/{$product->id}/canjear");

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['points']);
    }

    public function test_cannot_redeem_inactive_product()
    {
        $product = Product::factory()->create([
            'costo_puntos' => 500,
            'stock' => 10,
            'activo' => false
        ]);

        $response = $this->actingAs($this->employee)
                         ->postJson("/productos/{$product->id}/canjear");

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['product']);
    }

    public function test_cannot_redeem_out_of_stock_product()
    {
        $product = Product::factory()->create([
            'costo_puntos' => 500,
            'stock' => 0,
            'activo' => true
        ]);

        $response = $this->actingAs($this->employee)
                         ->postJson("/productos/{$product->id}/canjear");

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['product']);
    }

    public function test_respects_rate_limiting_for_redemptions()
    {
        $product = Product::factory()->create([
            'costo_puntos' => 100,
            'stock' => 10,
            'activo' => true
        ]);


        for ($i = 0; $i < 4; $i++) {
            $response = $this->actingAs($this->employee)
                             ->postJson("/productos/{$product->id}/canjear");
            
            if ($i < 3) {
                $response->assertStatus(200);
            } else {

                $response->assertStatus(429);
            }
        }
    }

    public function test_validates_redemption_request_data()
    {
        $product = Product::factory()->create([
            'costo_puntos' => 500,
            'stock' => 10,
            'activo' => true
        ]);


        $response = $this->actingAs($this->employee)
                         ->postJson("/productos/{$product->id}/canjear", [
                             'observaciones' => str_repeat('a', 501) // 501 characters
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['observaciones']);
    }

    public function test_product_redemption_creates_order_record()
    {
        $product = Product::factory()->create([
            'nombre' => 'Test Product',
            'costo_puntos' => 500,
            'stock' => 10,
            'activo' => true
        ]);

        $response = $this->actingAs($this->employee)
                         ->postJson("/productos/{$product->id}/canjear", [
                             'observaciones' => 'Test redemption'
                         ]);

        $response->assertStatus(200);


        $this->assertDatabaseHas('orders', [
            'empleado_id' => $this->employee->id_empleado,
            'producto_id' => $product->id,
            'puntos_utilizados' => 500,
            'estado' => 'Pendiente',
            'producto_nombre' => 'Test Product',
            'empleado_nombre' => $this->employee->nombre,
            'observaciones' => 'Test redemption'
        ]);
    }

    public function test_requires_authentication_for_protected_endpoints()
    {
        $product = Product::factory()->create();


        $response = $this->getJson('/productos');
        $response->assertStatus(401);


        $response = $this->postJson("/productos/{$product->id}/canjear");
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