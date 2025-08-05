<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'id_empleado' => $this->faker->unique()->numerify('###'),
            'nombre' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'puntos_totales' => $this->faker->numberBetween(0, 2000),
            'puntos_canjeados' => $this->faker->numberBetween(0, 1000),
            'rol_usuario' => $this->faker->randomElement(['Empleado', 'Administrador']),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol_usuario' => 'Administrador',
        ]);
    }

    public function withHighPoints(): static
    {
        return $this->state(fn (array $attributes) => [
            'puntos_totales' => $this->faker->numberBetween(1500, 5000),
        ]);
    }
}