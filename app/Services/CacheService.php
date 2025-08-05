<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Order;

class CacheService
{

    const PRODUCTS_ACTIVE = 'products.active';
    const PRODUCTS_BY_CATEGORY = 'products.category.';
    const EMPLOYEE_STATS = 'employee.stats.';
    const DASHBOARD_FEATURED = 'dashboard.featured_products';
    const POPULAR_PRODUCTS = 'products.popular';
    const ADMIN_STATISTICS = 'admin.statistics';
    

    const SHORT_CACHE = 5;     // 5 minutes
    const MEDIUM_CACHE = 30;   // 30 minutes
    const LONG_CACHE = 60;     // 1 hour
    const DAILY_CACHE = 1440;  // 24 hours

    public function getActiveProducts(): \Illuminate\Database\Eloquent\Collection
    {

        if (app()->environment('testing')) {
            return Product::active()->available()->get();
        }
        
        return Cache::remember(self::PRODUCTS_ACTIVE, self::MEDIUM_CACHE, function () {
            return Product::active()->available()->get();
        });
    }

    public function getProductsByCategory(string $category): \Illuminate\Database\Eloquent\Collection
    {

        if (app()->environment('testing')) {
            return Product::active()->available()->byCategory($category)->get();
        }
        
        $cacheKey = self::PRODUCTS_BY_CATEGORY . $category;
        
        return Cache::remember($cacheKey, self::MEDIUM_CACHE, function () use ($category) {
            return Product::active()->available()->byCategory($category)->get();
        });
    }

    public function getEmployeeStats(Employee $employee): array
    {

        if (app()->environment('testing')) {
            return [
                'total_points' => $employee->puntos_totales,
                'redeemed_points' => $employee->puntos_canjeados,
                'total_orders' => Order::where('empleado_id', $employee->id_empleado)->count(),
                'pending_orders' => Order::where('empleado_id', $employee->id_empleado)
                    ->where('estado', Order::STATUS_PENDING)
                    ->count(),
                'completed_orders' => Order::where('empleado_id', $employee->id_empleado)
                    ->where('estado', Order::STATUS_COMPLETED)
                    ->count(),
            ];
        }
        
        $cacheKey = self::EMPLOYEE_STATS . $employee->id_empleado;
        
        return Cache::remember($cacheKey, self::SHORT_CACHE, function () use ($employee) {
            return [
                'total_points' => $employee->puntos_totales,
                'redeemed_points' => $employee->puntos_canjeados,
                'total_orders' => Order::where('empleado_id', $employee->id_empleado)->count(),
                'pending_orders' => Order::where('empleado_id', $employee->id_empleado)
                    ->where('estado', Order::STATUS_PENDING)
                    ->count(),
                'completed_orders' => Order::where('empleado_id', $employee->id_empleado)
                    ->where('estado', Order::STATUS_COMPLETED)
                    ->count(),
            ];
        });
    }

    public function getFeaturedProducts(int $limit = 8): \Illuminate\Database\Eloquent\Collection
    {

        if (app()->environment('testing')) {
            return Product::active()
                ->available()
                ->orderByRaw('CASE WHEN stock = -1 THEN 0 ELSE 1 END') // Unlimited stock first
                ->orderBy('costo_puntos', 'asc')
                ->limit($limit)
                ->get();
        }
        
        return Cache::remember(self::DASHBOARD_FEATURED, self::LONG_CACHE, function () use ($limit) {
            return Product::active()
                ->available()
                ->orderByRaw('CASE WHEN stock = -1 THEN 0 ELSE 1 END') // Unlimited stock first
                ->orderBy('costo_puntos', 'asc')
                ->limit($limit)
                ->get();
        });
    }

    public function getPopularProducts(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember(self::POPULAR_PRODUCTS, self::LONG_CACHE, function () use ($limit) {
            return Product::active()
                ->available()
                ->withCount(['orders' => function ($query) {
                    $query->where('created_at', '>=', now()->subDays(30));
                }])
                ->orderBy('orders_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    public function getAdminStatistics(): array
    {

        if (app()->environment('testing')) {
            return [
                'total_employees' => Employee::count(),
                'active_employees' => Employee::whereHas('orders', function ($query) {
                    $query->where('created_at', '>=', now()->subDays(30));
                })->count(),
                'total_products' => Product::count(),
                'active_products' => Product::active()->count(),
                'total_orders' => Order::count(),
                'orders_this_month' => Order::where('created_at', '>=', now()->startOfMonth())->count(),
                'pending_orders' => Order::where('estado', Order::STATUS_PENDING)->count(),
                'total_points_redeemed' => Order::where('estado', '!=', Order::STATUS_CANCELLED)->sum('puntos_utilizados'),
                'points_redeemed_this_month' => Order::where('estado', '!=', Order::STATUS_CANCELLED)
                    ->where('created_at', '>=', now()->startOfMonth())
                    ->sum('puntos_utilizados'),
            ];
        }
        
        return Cache::remember(self::ADMIN_STATISTICS, self::MEDIUM_CACHE, function () {
            return [
                'total_employees' => Employee::count(),
                'active_employees' => Employee::whereHas('orders', function ($query) {
                    $query->where('created_at', '>=', now()->subDays(30));
                })->count(),
                'total_products' => Product::count(),
                'active_products' => Product::active()->count(),
                'total_orders' => Order::count(),
                'orders_this_month' => Order::where('created_at', '>=', now()->startOfMonth())->count(),
                'pending_orders' => Order::where('estado', Order::STATUS_PENDING)->count(),
                'total_points_redeemed' => Order::where('estado', '!=', Order::STATUS_CANCELLED)->sum('puntos_utilizados'),
                'points_redeemed_this_month' => Order::where('estado', '!=', Order::STATUS_CANCELLED)
                    ->where('created_at', '>=', now()->startOfMonth())
                    ->sum('puntos_utilizados'),
            ];
        });
    }

    public function clearProductCache(): void
    {
        Cache::forget(self::PRODUCTS_ACTIVE);
        Cache::forget(self::DASHBOARD_FEATURED);
        Cache::forget(self::POPULAR_PRODUCTS);
        

        $categories = Product::distinct()->pluck('categoria');
        foreach ($categories as $category) {
            Cache::forget(self::PRODUCTS_BY_CATEGORY . $category);
        }
    }

    public function clearEmployeeCache(string $employeeId): void
    {

        if (app()->environment('testing')) {
            return;
        }
        
        Cache::forget(self::EMPLOYEE_STATS . $employeeId);
    }

    public function clearAdminCache(): void
    {

        if (app()->environment('testing')) {
            return;
        }
        
        Cache::forget(self::ADMIN_STATISTICS);
    }

    public function clearAllCache(): void
    {
        Cache::flush();
    }

    public function warmUpCache(): void
    {

        $this->getActiveProducts();
        $this->getFeaturedProducts();
        $this->getPopularProducts();
        $this->getAdminStatistics();
        

        $categories = Product::distinct()->pluck('categoria');
        foreach ($categories as $category) {
            $this->getProductsByCategory($category);
        }
    }

    public function getCacheStats(): array
    {
        $keys = [
            self::PRODUCTS_ACTIVE,
            self::DASHBOARD_FEATURED,
            self::POPULAR_PRODUCTS,
            self::ADMIN_STATISTICS,
        ];

        $stats = [];
        foreach ($keys as $key) {
            $stats[$key] = [
                'exists' => Cache::has($key),
                'ttl' => Cache::get($key) ? 'cached' : 'missing'
            ];
        }

        return $stats;
    }
}