<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Employee;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $employee = Employee::factory()->create();
        $product = Product::factory()->create();

        return [
            'empleado_id' => $employee->id_empleado,
            'producto_id' => $product->id,
            'fecha' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'estado' => $this->faker->randomElement(['Pendiente', 'En curso', 'Realizado', 'Cancelado']),
            'puntos_utilizados' => $product->costo_puntos,
            'producto_nombre' => $product->nombre,
            'empleado_nombre' => $employee->nombre,
            'observaciones' => $this->faker->optional()->sentence(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Order::STATUS_PENDING,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Order::STATUS_COMPLETED,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Order::STATUS_CANCELLED,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Order::STATUS_IN_PROGRESS,
        ]);
    }

    public function forEmployee(Employee $employee): static
    {
        return $this->state(fn (array $attributes) => [
            'empleado_id' => $employee->id_empleado,
            'empleado_nombre' => $employee->nombre,
        ]);
    }

    public function forProduct(Product $product): static
    {
        return $this->state(fn (array $attributes) => [
            'producto_id' => $product->id,
            'producto_nombre' => $product->nombre,
            'puntos_utilizados' => $product->costo_puntos,
        ]);
    }
}