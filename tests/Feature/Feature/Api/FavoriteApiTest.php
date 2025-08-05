<?php

namespace Tests\Feature\Feature\Api;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteApiTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $employee;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->employee = Employee::factory()->create([
            'puntos_totales' => 1000,
            'rol_usuario' => 'Empleado'
        ]);
        
        $this->product = Product::factory()->create([
            'activo' => true,
            'stock' => 10
        ]);
    }

    public function test_can_list_user_favorites(): void
    {
        Favorite::factory()->create([
            'empleado_id' => $this->employee->id_empleado,
            'producto_id' => $this->product->id,
        ]);

        $response = $this->mockFirebaseAuth($this->employee)
            ->get('/api/favoritos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'producto_id',
                        'producto'
                    ]
                ]
            ]);
    }

    public function test_can_toggle_favorite_product(): void
    {
        $response = $this->mockFirebaseAuth($this->employee)
            ->post("/api/favoritos/{$this->product->id}");

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Producto agregado a favoritos'
            ]);

        $this->assertDatabaseHas('favorites', [
            'empleado_id' => $this->employee->id_empleado,
            'producto_id' => $this->product->id
        ]);
    }

    public function test_can_remove_favorite_product(): void
    {
        $favorite = Favorite::factory()->create([
            'empleado_id' => $this->employee->id_empleado,
            'producto_id' => $this->product->id,
        ]);

        $response = $this->mockFirebaseAuth($this->employee)
            ->post("/api/favoritos/{$this->product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Producto eliminado de favoritos'
            ]);

        $this->assertDatabaseMissing('favorites', [
            'id' => $favorite->id
        ]);
    }

    public function test_cannot_favorite_inactive_product(): void
    {
        $inactiveProduct = Product::factory()->create([
            'activo' => false
        ]);

        $response = $this->mockFirebaseAuth($this->employee)
            ->post("/api/favoritos/{$inactiveProduct->id}");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'No se puede agregar a favoritos un producto inactivo'
            ]);
    }

    public function test_requires_authentication_for_favorites(): void
    {
        $response = $this->get('/api/favoritos');
        $response->assertStatus(401);

        $response = $this->post("/api/favoritos/{$this->product->id}");
        $response->assertStatus(401);
    }

    private function mockFirebaseAuth(Employee $employee)
    {
        $this->app->instance('firebase.auth.employee', $employee);
        
        return $this->withHeaders([
            'Authorization' => 'Bearer mock-firebase-token',
            'Accept' => 'application/json',
        ]);
    }
}
