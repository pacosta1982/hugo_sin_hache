<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->words(3, true),
            'descripcion' => $this->faker->paragraph(),
            'categoria' => $this->faker->randomElement(['Tecnología', 'Bienestar', 'Hogar', 'Finanzas', 'Entretenimiento']),
            'costo_puntos' => $this->faker->randomElement([100, 200, 300, 500, 750, 1000]),
            'stock' => $this->faker->randomElement([-1, 10, 25, 50, 100]), // -1 = unlimited
            'activo' => true,
            'integra_jira' => $this->faker->boolean(30),
            'envia_email' => $this->faker->boolean(70),
            'terminos_condiciones' => $this->faker->optional()->paragraph(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'activo' => false,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    public function unlimited(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => -1,
        ]);
    }

    public function expensive(): static
    {
        return $this->state(fn (array $attributes) => [
            'costo_puntos' => $this->faker->numberBetween(1500, 3000),
        ]);
    }

    public function withJiraIntegration(): static
    {
        return $this->state(fn (array $attributes) => [
            'integra_jira' => true,
        ]);
    }
}