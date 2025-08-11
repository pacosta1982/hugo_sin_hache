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
        try {
            $this->employee = null;
            $this->recentOrders = collect();
            $this->featuredProducts = collect();
            $this->stats = [
                'total_points' => 0,
                'redeemed_points' => 0,
                'total_orders' => 0,
                'pending_orders' => 0
            ];
            
            \Log::info('Dashboard component mounted successfully');
        } catch (\Exception $e) {
            \Log::error('Dashboard mount failed', ['error' => $e->getMessage()]);
            $this->initializeEmptyState();
        }
    }

    private function initializeEmptyState()
    {
        $this->employee = null;
        $this->recentOrders = collect();
        $this->featuredProducts = collect();
        $this->stats = [
            'total_points' => 0,
            'redeemed_points' => 0,
            'total_orders' => 0,
            'pending_orders' => 0
        ];
    }

    public function loadDashboardData($employeeData = null)
    {
        try {
            \Log::info('Loading dashboard data', ['employee_data' => $employeeData]);
            
            if (!$employeeData || !isset($employeeData['id_empleado'])) {
                \Log::warning('Invalid employee data provided', ['data' => $employeeData]);
                $this->dispatch('toast', [
                    'message' => 'Error: Datos de empleado inválidos',
                    'type' => 'error'
                ]);
                return;
            }

            $this->employee = Employee::where('id_empleado', $employeeData['id_empleado'])->first();
            
            if (!$this->employee) {
                \Log::warning('Employee not found in database', ['id_empleado' => $employeeData['id_empleado']]);
                $this->dispatch('toast', [
                    'message' => 'Error: Empleado no encontrado',
                    'type' => 'error'
                ]);
                return;
            }

            \Log::info('Employee found', ['employee' => $this->employee->toArray()]);

            $cacheService = app(CacheService::class);
            
            try {
                $this->recentOrders = Order::where('empleado_id', $this->employee->id_empleado)
                    ->with('product')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                    
                \Log::info('Recent orders loaded', ['count' => $this->recentOrders->count()]);
            } catch (\Exception $e) {
                \Log::error('Failed to load recent orders', ['error' => $e->getMessage()]);
                $this->recentOrders = collect();
            }

            try {
                $this->featuredProducts = $cacheService->getFeaturedProducts(8);
                \Log::info('Featured products loaded', ['count' => $this->featuredProducts->count()]);
            } catch (\Exception $e) {
                \Log::error('Failed to load featured products', ['error' => $e->getMessage()]);
                $this->featuredProducts = collect();
            }

            try {
                $this->stats = $cacheService->getEmployeeStats($this->employee);
                \Log::info('Employee stats loaded', ['stats' => $this->stats]);
                
                if (!is_array($this->stats)) {
                    throw new \Exception('Invalid stats format');
                }
                
                $requiredKeys = ['total_points', 'redeemed_points', 'total_orders', 'pending_orders'];
                foreach ($requiredKeys as $key) {
                    if (!isset($this->stats[$key])) {
                        $this->stats[$key] = 0;
                    }
                }
                
            } catch (\Exception $e) {
                \Log::error('Failed to load employee stats', ['error' => $e->getMessage()]);
                $this->stats = [
                    'total_points' => $this->employee->puntos_totales ?? 0,
                    'redeemed_points' => $this->employee->puntos_canjeados ?? 0,
                    'total_orders' => 0,
                    'pending_orders' => 0
                ];
            }

            \Log::info('Dashboard data loaded successfully');
            
            $this->dispatch('dashboard-loaded', [
                'employee' => $this->employee->toArray(),
                'stats' => $this->stats
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Dashboard data loading failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->initializeEmptyState();
            
            $this->dispatch('toast', [
                'message' => 'Error al cargar datos del dashboard. Intenta refrescar la página.',
                'type' => 'error'
            ]);
        }
    }

    public function addToFavorites($productId)
    {
        try {
            \Log::info('Adding to favorites', ['product_id' => $productId, 'employee_id' => $this->employee?->id_empleado]);
            
            if (!$this->employee) {
                \Log::warning('Attempt to add favorite without employee');
                $this->dispatch('toast', [
                    'message' => 'Debes iniciar sesión para agregar favoritos',
                    'type' => 'warning'
                ]);
                return;
            }

            if (!$productId || !is_numeric($productId)) {
                \Log::warning('Invalid product ID for favorites', ['product_id' => $productId]);
                $this->dispatch('toast', [
                    'message' => 'Producto inválido',
                    'type' => 'error'
                ]);
                return;
            }

            $result = \App\Models\Favorite::toggle($this->employee->id_empleado, $productId);
            
            if ($result['added']) {
                \Log::info('Product added to favorites successfully');
                $this->dispatch('toast', [
                    'message' => 'Producto agregado a favoritos',
                    'type' => 'success'
                ]);
            } else {
                \Log::info('Product removed from favorites successfully');
                $this->dispatch('toast', [
                    'message' => 'Producto removido de favoritos',
                    'type' => 'info'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to toggle favorite', [
                'product_id' => $productId,
                'employee_id' => $this->employee?->id_empleado,
                'error' => $e->getMessage()
            ]);
            $this->dispatch('toast', [
                'message' => 'Error al actualizar favoritos. Intenta de nuevo.',
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