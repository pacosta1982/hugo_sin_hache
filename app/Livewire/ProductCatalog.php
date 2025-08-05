<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Favorite;
use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;

class ProductCatalog extends Component
{
    use WithPagination;

    public $employee;
    public $search = '';
    public $category = '';
    public $sortBy = 'costo_puntos';
    public $sortDirection = 'asc';
    public $availableOnly = true;
    public $maxPoints = null;
    public $isAuthenticated = false;
    public $authTimestamp = null;
    
    public $categories = [];
    public $favoriteIds = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sortBy' => ['except' => 'costo_puntos'],
        'sortDirection' => ['except' => 'asc'],
        'availableOnly' => ['except' => true],
    ];

    public function mount()
    {

        $this->employee = request()->get('employee');
        
        if ($this->employee) {
            $this->maxPoints = $this->employee->puntos_totales;
            $this->isAuthenticated = true;
            $this->loadFavorites();
        }
        
        $this->loadCategories(); // Categories don't require authentication
    }

    public function loadCategories()
    {
        $this->categories = Product::active()
            ->whereNotNull('categoria')
            ->distinct()
            ->pluck('categoria')
            ->filter()
            ->sort()
            ->values()
            ->toArray();
    }

    public function loadFavorites()
    {
        if (!$this->employee) {
            $this->favoriteIds = [];
            return;
        }
        

        $employeeId = $this->getEmployeeId();
        
        if (!$employeeId) {
            $this->favoriteIds = [];
            return;
        }
        
        $this->favoriteIds = Favorite::where('empleado_id', $employeeId)
            ->pluck('producto_id')
            ->toArray();
            
        \Log::info('ProductCatalog: Favorites loaded', [
            'employee_id' => $employeeId,
            'favorites_count' => count($this->favoriteIds),
            'favorite_ids' => $this->favoriteIds
        ]);
    }

    private function getEmployeeId()
    {
        if (!$this->employee) {
            return null;
        }
        

        if (is_object($this->employee) && isset($this->employee->id_empleado)) {
            return $this->employee->id_empleado;
        }
        

        if (is_array($this->employee) && isset($this->employee['id_empleado'])) {
            return $this->employee['id_empleado'];
        }
        

        if (is_object($this->employee) && isset($this->employee->{'id_empleado'})) {
            return $this->employee->{'id_empleado'};
        }
        
        return null;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedAvailableOnly()
    {
        $this->resetPage();
    }

    public function updatedFavoriteIds()
    {


    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->availableOnly = true;
        $this->maxPoints = $this->employee->puntos_totales;
        $this->sortBy = 'costo_puntos';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function toggleFavorite($productId)
    {

        if (!$this->employee) {
            $this->dispatch('toast', [
                'message' => 'Debes iniciar sesión para agregar favoritos',
                'type' => 'warning'
            ]);
            return;
        }


        $employeeId = $this->getEmployeeId();
        
        if (!$employeeId) {
            $this->dispatch('toast', [
                'message' => 'Error: No se pudo identificar el usuario',
                'type' => 'error'
            ]);
            return;
        }

        try {
            \Log::info('ProductCatalog: Toggling favorite', [
                'employee_id' => $employeeId,
                'product_id' => $productId,
                'current_favorites' => $this->favoriteIds
            ]);
            
            $result = Favorite::toggle($employeeId, $productId);
            

            if ($result['added']) {
                $this->favoriteIds[] = $productId;
                $message = 'Producto agregado a favoritos';
                $type = 'success';
            } else {
                $this->favoriteIds = array_diff($this->favoriteIds, [$productId]);
                $message = 'Producto removido de favoritos';
                $type = 'info';
            }

            $this->dispatch('toast', [
                'message' => $message,
                'type' => $type
            ]);


            $this->dispatch('favorite-toggled', [
                'productId' => $productId,
                'isFavorite' => $result['added'],
                'source' => 'product-catalog'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Error al actualizar favoritos',
                'type' => 'error'
            ]);
        }
    }

    public function loadEmployeeData()
    {
        try {

            $this->employee = request()->get('employee');
            

            if (!$this->employee) {


                $this->dispatch('load-employee-data');
                return;
            }
            
            $this->maxPoints = $this->employee->puntos_totales;
            $this->loadFavorites();
            

            \Log::info('ProductCatalog: Employee data loaded', [
                'employee_id' => $this->employee->id_empleado,
                'favorites_count' => count($this->favoriteIds)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('ProductCatalog: Error loading employee data', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function setEmployeeData($employeeData)
    {

        if ($employeeData) {
            \Log::info('ProductCatalog: Setting employee data from JavaScript', [
                'data_received' => $employeeData,
                'data_type' => gettype($employeeData)
            ]);
            

            $this->employee = $employeeData;
            $this->maxPoints = is_array($employeeData) 
                ? ($employeeData['puntos_totales'] ?? null)
                : ($employeeData->puntos_totales ?? null);
            $this->isAuthenticated = true;
            $this->authTimestamp = now()->timestamp; // Force reactivity trigger
            

            $this->loadFavorites();
            
            \Log::info('ProductCatalog: Employee data set successfully', [
                'employee_id' => $this->getEmployeeId(),
                'favorites_count' => count($this->favoriteIds),
                'max_points' => $this->maxPoints,
                'is_authenticated' => $this->isAuthenticated
            ]);
            

            \Log::info('ProductCatalog: Component should re-render now', [
                'auth_timestamp' => $this->authTimestamp,
                'is_authenticated' => $this->isAuthenticated,
                'has_employee' => !empty($this->employee)
            ]);
        }
    }

    public function refreshFavorites()
    {
        $this->loadFavorites();
    }

    public function forceRefresh()
    {

        $this->loadFavorites();
        $this->dispatch('component-refreshed');
    }

    public function getIsUserAuthenticatedProperty()
    {
        return $this->isAuthenticated && $this->employee && $this->getEmployeeId();
    }

    public function redirectToProduct($productId)
    {
        return redirect()->route('products.show', $productId);
    }

    public function getProductsProperty()
    {
        $cacheService = app(CacheService::class);
        

        if ($this->category && !$this->search && $this->availableOnly && 
            !$this->maxPoints && $this->sortBy === 'costo_puntos' && $this->sortDirection === 'asc') {
            return $cacheService->getProductsByCategory($this->category);
        }
        

        return Product::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                      ->orWhere('categoria', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function (Builder $query) {
                $query->where('categoria', $this->category);
            })
            ->when($this->availableOnly, function (Builder $query) {
                $query->available();
            })
            ->when($this->maxPoints, function (Builder $query) {
                $query->where('costo_puntos', '<=', $this->maxPoints);
            })
            ->where('activo', true)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.product-catalog', [
            'products' => $this->products,
        ])
        ->extends('layouts.app')
        ->section('content');
    }
}