<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Order;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class Dashboard extends Component
{
    public $employee;
    public $recentOrders;
    public $featuredProducts;
    public $stats;

    public function mount()
    {


        $this->employee = null;
        $this->recentOrders = collect();
        $this->featuredProducts = collect();
        $this->stats = [];
    }

    public function loadDashboardData($employeeData = null)
    {
        if ($employeeData) {

            $this->employee = Employee::where('id_empleado', $employeeData['id_empleado'])->first();
        }
        
        if (!$this->employee) {
            return;
        }

        $cacheService = app(CacheService::class);
        

        $this->recentOrders = Order::where('empleado_id', $this->employee->id_empleado)
            ->with('product')
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();


        $this->featuredProducts = $cacheService->getFeaturedProducts(8);


        $this->stats = $cacheService->getEmployeeStats($this->employee);
    }

    public function addToFavorites($productId)
    {

        if (!$this->employee) {
            $this->dispatch('toast', [
                'message' => 'Debes iniciar sesión para agregar favoritos',
                'type' => 'warning'
            ]);
            return;
        }

        try {
            $result = \App\Models\Favorite::toggle($this->employee->id_empleado, $productId);
            
            if ($result['added']) {
                $this->dispatch('toast', [
                    'message' => 'Producto agregado a favoritos',
                    'type' => 'success'
                ]);
            } else {
                $this->dispatch('toast', [
                    'message' => 'Producto removido de favoritos',
                    'type' => 'info'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Error al actualizar favoritos',
                'type' => 'error'
            ]);
        }
    }

    public function redirectToProduct($productId)
    {
        return redirect()->route('products.show', $productId);
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->extends('layouts.app')
            ->section('content');
    }
}