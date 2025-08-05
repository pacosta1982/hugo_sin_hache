<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Favorite;
use App\Services\FavoritesService;

class FavoritesList extends Component
{
    public $employee;
    public $favorites;
    public $stats;


    public $loadingFavoriteId = null;

    public function mount()
    {

        $this->employee = request()->get('employee');
        
        if ($this->employee) {
            $this->loadFavorites();
        } else {

            $this->favorites = collect();
            $this->stats = [
                'total_favorites' => 0,
                'available_favorites' => 0,
                'total_cost' => 0,
                'affordable_favorites' => 0,
                'can_afford_all' => true,
            ];
        }
    }

    public function loadFavorites()
    {
        if (!$this->employee) {
            $this->favorites = collect();
            $this->stats = [
                'total_favorites' => 0,
                'available_favorites' => 0,
                'total_cost' => 0,
                'affordable_favorites' => 0,
                'can_afford_all' => true,
            ];
            return;
        }
        
        $favoritesService = app(FavoritesService::class);
        
        $this->favorites = $favoritesService->getUserFavorites($this->employee);
        $this->stats = $favoritesService->getFavoritesStats($this->employee);
    }

    public function removeFavorite($favoriteId)
    {

        if (!$this->employee) {
            $this->dispatch('toast', [
                'message' => 'Debes iniciar sesión para gestionar favoritos',
                'type' => 'warning'
            ]);
            return;
        }

        $this->loadingFavoriteId = $favoriteId;

        try {
            $favorite = Favorite::find($favoriteId);
            
            if (!$favorite || $favorite->empleado_id !== $this->employee->id_empleado) {
                $this->dispatch('toast', [
                    'message' => 'Favorito no encontrado o no autorizado',
                    'type' => 'error'
                ]);
                return;
            }

            $productId = $favorite->producto_id;
            $favorite->delete();


            $this->loadFavorites();

            $this->dispatch('toast', [
                'message' => 'Producto removido de favoritos',
                'type' => 'success'
            ]);


            $this->dispatch('favorite-toggled', [
                'productId' => $productId,
                'isFavorite' => false,
                'source' => 'favorites-list'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Error al remover de favoritos',
                'type' => 'error'
            ]);
        } finally {
            $this->loadingFavoriteId = null;
        }
    }

    public function redirectToProduct($productId)
    {
        return redirect()->route('products.show', $productId);
    }

    public function loadEmployeeData()
    {

        $this->employee = request()->get('employee');
        $this->loadFavorites();
    }

    public function refreshFavorites()
    {

        $this->employee = request()->get('employee');
        \Log::info('FavoritesList::refreshFavorites called', [
            'has_employee' => $this->employee ? 'yes' : 'no',
            'employee_id' => $this->employee ? $this->employee->id_empleado : 'none',
        ]);
        $this->loadFavorites();
    }

    public function clearAllFavorites()
    {
        try {
            $favoritesService = app(FavoritesService::class);
            $result = $favoritesService->clearAllFavorites($this->employee);

            $this->loadFavorites();

            $this->dispatch('toast', [
                'message' => $result['message'],
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Error al limpiar favoritos',
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.favorites-list')
            ->extends('layouts.app')
            ->section('content');
    }
}