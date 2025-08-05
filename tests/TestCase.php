<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        

    }
    
    protected function createEmployee(array $attributes = []): \App\Models\Employee
    {
        return \App\Models\Employee::factory()->create($attributes);
    }
    
    protected function createProduct(array $attributes = []): \App\Models\Product
    {
        return \App\Models\Product::factory()->create($attributes);
    }
    
    protected function createOrder(array $attributes = []): \App\Models\Order
    {
        return \App\Models\Order::factory()->create($attributes);
    }
    
    protected function actingAsEmployee(?\App\Models\Employee $employee = null): self
    {
        $employee = $employee ?: $this->createEmployee();
        

        $this->app->instance('test.employee', $employee);
        

        $this->app->bind(\App\Http\Middleware\FirebaseAuth::class, function () use ($employee) {
            return new class($employee) {
                protected $employee;
                
                public function __construct($employee) {
                    $this->employee = $employee;
                }
                
                public function handle($request, $next) {
                    $request->merge([
                        'firebase_user' => [
                            'sub' => $this->employee->id_empleado,
                            'email' => $this->employee->email ?? 'test@example.com',
                            'name' => $this->employee->nombre
                        ],
                        'employee' => $this->employee,
                        'user_role' => $this->employee->rol_usuario,
                        'is_admin' => $this->employee->is_admin,
                    ]);
                    return $next($request);
                }
            };
        });
        
        return $this;
    }
    
    protected function actingAsAdmin(): self
    {
        $admin = $this->createEmployee(['rol_usuario' => 'Administrador', 'is_admin' => true]);
        return $this->actingAsEmployee($admin);
    }
}