<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Favorite;
use Illuminate\Support\Collection;

class ProductRecommendationService
{
    public function getRecommendationsForEmployee(string $employeeId, int $limit = 6): Collection
    {
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return collect();
        }

        $recommendations = collect();


        $favoriteBasedRecs = $this->getRecommendationsBasedOnFavorites($employee, 3);
        $recommendations = $recommendations->merge($favoriteBasedRecs);


        $purchaseBasedRecs = $this->getRecommendationsBasedOnPurchases($employee, 3);
        $recommendations = $recommendations->merge($purchaseBasedRecs);


        $popularRecs = $this->getPopularProductsForSimilarUsers($employee, 2);
        $recommendations = $recommendations->merge($popularRecs);


        if ($recommendations->count() < $limit) {
            $trendingRecs = $this->getTrendingProducts($limit - $recommendations->count());
            $recommendations = $recommendations->merge($trendingRecs);
        }


        $excludeIds = $this->getExcludedProductIds($employee);
        
        return $recommendations
            ->unique('id')
            ->filter(fn($product) => !in_array($product->id, $excludeIds))
            ->take($limit);
    }

    protected function getRecommendationsBasedOnFavorites(Employee $employee, int $limit): Collection
    {
        $favoriteProducts = $employee->favorites()
            ->with('product')
            ->get()
            ->pluck('product');

        if ($favoriteProducts->isEmpty()) {
            return collect();
        }


        $priceRanges = $favoriteProducts->pluck('puntos_requeridos');
        $minPrice = $priceRanges->min();
        $maxPrice = $priceRanges->max();
        

        $priceBuffer = ($maxPrice - $minPrice) * 0.2;
        $expandedMin = max(0, $minPrice - $priceBuffer);
        $expandedMax = $maxPrice + $priceBuffer;

        return Product::active()
            ->inStock()
            ->whereBetween('puntos_requeridos', [$expandedMin, $expandedMax])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    protected function getRecommendationsBasedOnPurchases(Employee $employee, int $limit): Collection
    {
        $purchasedProducts = $employee->orders()
            ->where('estado', 'completed')
            ->with('product')
            ->get()
            ->pluck('product')
            ->filter();

        if ($purchasedProducts->isEmpty()) {
            return collect();
        }


        $avgPurchasePrice = $purchasedProducts->avg('puntos_requeridos');
        $priceRange = $avgPurchasePrice * 0.3;

        return Product::active()
            ->inStock()
            ->whereBetween('puntos_requeridos', [
                $avgPurchasePrice - $priceRange,
                $avgPurchasePrice + $priceRange
            ])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    protected function getPopularProductsForSimilarUsers(Employee $employee, int $limit): Collection
    {

        $pointRange = $employee->puntos_totales * 0.2;
        $similarEmployees = Employee::whereBetween('puntos_totales', [
            $employee->puntos_totales - $pointRange,
            $employee->puntos_totales + $pointRange
        ])
        ->where('id_empleado', '!=', $employee->id_empleado)
        ->pluck('id_empleado');

        if ($similarEmployees->isEmpty()) {
            return collect();
        }


        return Product::active()
            ->inStock()
            ->whereHas('orders', function ($query) use ($similarEmployees) {
                $query->whereIn('empleado_id', $similarEmployees)
                      ->where('estado', 'completed');
            })
            ->withCount(['orders' => function ($query) use ($similarEmployees) {
                $query->whereIn('empleado_id', $similarEmployees)
                      ->where('estado', 'completed');
            }])
            ->orderBy('orders_count', 'desc')
            ->limit($limit)
            ->get();
    }

    protected function getTrendingProducts(int $limit): Collection
    {

        return Product::active()
            ->inStock()
            ->withCount(['orders' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(30))
                      ->where('estado', 'completed');
            }])
            ->withCount(['favorites' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->orderByRaw('(orders_count + favorites_count) DESC')
            ->limit($limit)
            ->get();
    }

    protected function getExcludedProductIds(Employee $employee): array
    {

        $favoriteIds = $employee->favorites()->pluck('producto_id')->toArray();
        
        $recentOrderIds = $employee->orders()
            ->where('created_at', '>=', now()->subDays(7))
            ->pluck('producto_id')
            ->toArray();

        return array_merge($favoriteIds, $recentOrderIds);
    }

    public function getRecommendationReasons(Product $product, Employee $employee): string
    {

        $favorites = $employee->favorites()->with('product')->get()->pluck('product');
        $orders = $employee->orders()->where('estado', 'completed')->with('product')->get()->pluck('product')->filter();
        
        if ($favorites->isNotEmpty()) {
            $avgFavoritePrice = $favorites->avg('puntos_requeridos');
            if (abs($product->puntos_requeridos - $avgFavoritePrice) <= $avgFavoritePrice * 0.3) {
                return "Basado en tus productos favoritos";
            }
        }

        if ($orders->isNotEmpty()) {
            $avgOrderPrice = $orders->avg('puntos_requeridos');
            if (abs($product->puntos_requeridos - $avgOrderPrice) <= $avgOrderPrice * 0.3) {
                return "Basado en tus compras anteriores";
            }
        }

        return "Popular entre usuarios similares";
    }

    public function getCategoryRecommendations(string $employeeId, int $categoryId, int $limit = 6): Collection
    {
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return collect();
        }

        $category = ProductCategory::find($categoryId);
        if (!$category) {
            return collect();
        }

        $recommendations = collect();


        $popularInCategory = $this->getPopularProductsInCategory($categoryId, $limit / 2);
        $recommendations = $recommendations->merge($popularInCategory);


        $affordableInCategory = $this->getAffordableProductsInCategory($employee, $categoryId, $limit / 2);
        $recommendations = $recommendations->merge($affordableInCategory);


        if ($recommendations->count() < $limit) {
            $remainingSlots = $limit - $recommendations->count();
            $randomInCategory = $this->getRandomProductsInCategory($categoryId, $remainingSlots);
            $recommendations = $recommendations->merge($randomInCategory);
        }


        $excludeIds = $this->getExcludedProductIds($employee);
        
        return $recommendations
            ->unique('id')
            ->filter(fn($product) => !in_array($product->id, $excludeIds))
            ->take($limit);
    }

    protected function getPopularProductsInCategory(int $categoryId, int $limit): Collection
    {
        return Product::query()
            ->where('category_id', $categoryId)
            ->where('activo', true)
            ->available()
            ->withCount(['orders as popularity_score' => function($query) {
                $query->where('created_at', '>=', now()->subDays(30))
                      ->whereIn('estado', ['completed', 'processing']);
            }])
            ->orderByDesc('popularity_score')
            ->orderBy('puntos_requeridos')
            ->limit($limit)
            ->get();
    }

    protected function getAffordableProductsInCategory(Employee $employee, int $categoryId, int $limit): Collection
    {
        return Product::query()
            ->where('category_id', $categoryId)
            ->where('activo', true)
            ->available()
            ->where('puntos_requeridos', '<=', $employee->puntos_totales)
            ->orderBy('puntos_requeridos')
            ->limit($limit)
            ->get();
    }

    protected function getRandomProductsInCategory(int $categoryId, int $limit): Collection
    {
        return Product::query()
            ->where('category_id', $categoryId)
            ->where('activo', true)
            ->available()
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}