<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Services\ProductRecommendationService;
use Livewire\Component;

class ProductRecommendations extends Component
{
    public $employeeId;
    public $recommendations;
    public $showReasons = false;

    protected $recommendationService;

    public function boot(ProductRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function mount($employeeId, $limit = 6)
    {
        $this->employeeId = $employeeId;
        $this->loadRecommendations($limit);
    }

    public function loadRecommendations($limit = 6)
    {
        $this->recommendations = $this->recommendationService
            ->getRecommendationsForEmployee($this->employeeId, $limit);
    }

    public function toggleReasons()
    {
        $this->showReasons = !$this->showReasons;
    }

    public function addToFavorites($productId)
    {
        $employee = Employee::find($this->employeeId);
        if ($employee) {
            $employee->favorites()->firstOrCreate(['producto_id' => $productId]);
            $this->dispatch('favoriteAdded');
            $this->dispatch('success', 'Producto agregado a favoritos');
        }
    }

    public function getRecommendationReason($productId)
    {
        $product = $this->recommendations->firstWhere('id', $productId);
        $employee = Employee::find($this->employeeId);
        
        if ($product && $employee) {
            return $this->recommendationService->getRecommendationReasons($product, $employee);
        }
        
        return '';
    }

    public function render()
    {
        return view('livewire.product-recommendations');
    }
}
