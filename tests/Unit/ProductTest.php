<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_has_correct_fillable_fields()
    {
        $product = new Product();
        
        $expectedFillable = [
            'nombre',
            'descripcion',
            'categoria',
            'costo_puntos',
            'stock',
            'activo',
            'integra_jira',
            'envia_email',
            'terminos_condiciones',
        ];

        $this->assertEquals($expectedFillable, $product->getFillable());
    }

    public function test_product_is_available_when_active_and_has_stock()
    {
        $product = Product::factory()->create([
            'activo' => true,
            'stock' => 10
        ]);

        $this->assertTrue($product->is_available);
    }

    public function test_product_is_not_available_when_inactive()
    {
        $product = Product::factory()->create([
            'activo' => false,
            'stock' => 10
        ]);

        $this->assertFalse($product->is_available);
    }

    public function test_product_is_not_available_when_out_of_stock()
    {
        $product = Product::factory()->create([
            'activo' => true,
            'stock' => 0
        ]);

        $this->assertFalse($product->is_available);
    }

    public function test_product_with_unlimited_stock_is_available()
    {
        $product = Product::factory()->create([
            'activo' => true,
            'stock' => -1
        ]);

        $this->assertTrue($product->is_available);
        $this->assertTrue($product->has_unlimited_stock);
    }

    public function test_product_has_stock_method()
    {
        $product = Product::factory()->create(['stock' => 5]);
        
        $this->assertTrue($product->hasStock(1));
        $this->assertTrue($product->hasStock(5));
        $this->assertFalse($product->hasStock(6));
    }

    public function test_unlimited_stock_product_always_has_stock()
    {
        $product = Product::factory()->unlimited()->create();
        
        $this->assertTrue($product->hasStock(1));
        $this->assertTrue($product->hasStock(1000));
    }

    public function test_can_decrement_stock()
    {
        $product = Product::factory()->create(['stock' => 10]);
        
        $product->decrementStock(3);
        
        $this->assertEquals(7, $product->fresh()->stock);
    }

    public function test_cannot_decrement_unlimited_stock()
    {
        $product = Product::factory()->unlimited()->create();
        
        $product->decrementStock(1);
        
        $this->assertEquals(-1, $product->fresh()->stock);
    }

    public function test_can_increment_stock()
    {
        $product = Product::factory()->create(['stock' => 5]);
        
        $product->incrementStock(2);
        
        $this->assertEquals(7, $product->fresh()->stock);
    }

    public function test_active_scope_filters_active_products()
    {
        Product::factory()->create(['activo' => true]);
        Product::factory()->create(['activo' => false]);

        $activeProducts = Product::active()->get();

        $this->assertCount(1, $activeProducts);
    }

    public function test_available_scope_filters_available_products()
    {
        Product::factory()->create(['activo' => true, 'stock' => 10]); // Available
        Product::factory()->create(['activo' => false, 'stock' => 10]); // Not available (inactive)
        Product::factory()->create(['activo' => true, 'stock' => 0]); // Not available (no stock)
        Product::factory()->unlimited()->create(['activo' => true]); // Available (unlimited)

        $availableProducts = Product::available()->get();

        $this->assertCount(2, $availableProducts);
    }

    public function test_by_category_scope_filters_by_category()
    {
        Product::factory()->create(['categoria' => 'Tecnología']);
        Product::factory()->create(['categoria' => 'Bienestar']);
        Product::factory()->create(['categoria' => 'Tecnología']);

        $techProducts = Product::byCategory('Tecnología')->get();

        $this->assertCount(2, $techProducts);
    }
}