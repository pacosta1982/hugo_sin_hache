<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Support\Facades\Log;

class FavoritesService
{
    public function toggleFavorite(Employee $employee, Product $product): array
    {
        $result = Favorite::toggle($employee->id_empleado, $product->id);

        Log::info('Favorite toggled', [
            'employee_id' => $employee->id_empleado,
            'product_id' => $product->id,
            'action' => $result['added'] ? 'added' : 'removed',
        ]);

        return $result;
    }

    public function addToFavorites(Employee $employee, Product $product): array
    {
        $existingFavorite = Favorite::where('empleado_id', $employee->id_empleado)
            ->where('producto_id', $product->id)
            ->first();

        if ($existingFavorite) {
            return [
                'success' => true,
                'favorite' => $existingFavorite,
                'message' => 'El producto ya está en tus favoritos.',
                'added' => false,
            ];
        }

        $favorite = Favorite::create([
            'empleado_id' => $employee->id_empleado,
            'producto_id' => $product->id,
            'fecha_agregado' => now(),
        ]);

        Log::info('Product added to favorites', [
            'employee_id' => $employee->id_empleado,
            'product_id' => $product->id,
            'favorite_id' => $favorite->id,
        ]);

        return [
            'success' => true,
            'favorite' => $favorite,
            'message' => 'Producto agregado a favoritos.',
            'added' => true,
        ];
    }

    public function removeFromFavorites(Employee $employee, Product $product): array
    {
        $favorite = Favorite::where('empleado_id', $employee->id_empleado)
            ->where('producto_id', $product->id)
            ->first();

        if (!$favorite) {
            return [
                'success' => false,
                'message' => 'El producto no está en tus favoritos.',
                'removed' => false,
            ];
        }

        $favorite->delete();

        Log::info('Product removed from favorites', [
            'employee_id' => $employee->id_empleado,
            'product_id' => $product->id,
            'favorite_id' => $favorite->id,
        ]);

        return [
            'success' => true,
            'message' => 'Producto removido de favoritos.',
            'removed' => true,
        ];
    }

    public function getUserFavorites(Employee $employee): \Illuminate\Database\Eloquent\Collection
    {
        \Log::info('FavoritesService::getUserFavorites called', [
            'employee_id' => $employee->id_empleado,
        ]);
        
        $favorites = Favorite::where('empleado_id', $employee->id_empleado)
            ->with(['product' => function ($query) {
                $query->where('activo', true);
            }])
            ->orderBy('fecha_agregado', 'desc')
            ->get();
            
        \Log::info('FavoritesService::getUserFavorites result', [
            'employee_id' => $employee->id_empleado,
            'count' => $favorites->count(),
            'favorites' => $favorites->pluck('id')->toArray(),
        ]);
        
        return $favorites;
    }

    public function getAvailableFavorites(Employee $employee): \Illuminate\Database\Eloquent\Collection
    {
        return Favorite::where('empleado_id', $employee->id_empleado)
            ->withActiveProducts()
            ->with('product')
            ->orderBy('fecha_agregado', 'desc')
            ->get();
    }

    public function isFavorite(Employee $employee, Product $product): bool
    {
        return Favorite::isFavorite($employee->id_empleado, $product->id);
    }

    public function getFavoriteIds(Employee $employee): array
    {
        return Favorite::where('empleado_id', $employee->id_empleado)
            ->pluck('producto_id')
            ->toArray();
    }

    public function clearAllFavorites(Employee $employee): array
    {
        $deletedCount = Favorite::where('empleado_id', $employee->id_empleado)->delete();

        Log::info('All favorites cleared for employee', [
            'employee_id' => $employee->id_empleado,
            'deleted_count' => $deletedCount,
        ]);

        return [
            'success' => true,
            'deleted_count' => $deletedCount,
            'message' => "Se eliminaron {$deletedCount} productos de tus favoritos.",
        ];
    }

    public function getFavoritesStats(Employee $employee): array
    {
        $totalFavorites = Favorite::where('empleado_id', $employee->id_empleado)->count();
        $availableFavorites = Favorite::where('empleado_id', $employee->id_empleado)
            ->withActiveProducts()
            ->count();

        $totalCost = Favorite::where('empleado_id', $employee->id_empleado)
            ->withActiveProducts()
            ->join('products', 'favorites.producto_id', '=', 'products.id')
            ->sum('products.costo_puntos');

        $affordableFavorites = Favorite::where('empleado_id', $employee->id_empleado)
            ->withActiveProducts()
            ->join('products', 'favorites.producto_id', '=', 'products.id')
            ->where('products.costo_puntos', '<=', $employee->puntos_totales)
            ->count();

        return [
            'total_favorites' => $totalFavorites,
            'available_favorites' => $availableFavorites,
            'total_cost' => (int) $totalCost,
            'affordable_favorites' => $affordableFavorites,
            'can_afford_all' => $totalCost <= $employee->puntos_totales,
        ];
    }
}