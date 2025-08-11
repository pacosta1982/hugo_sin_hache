<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
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
    

    const SHORT_CACHE = 5;    
    const MEDIUM_CACHE = 30;  
    const LONG_CACHE = 60;    
    const DAILY_CACHE = 1440; 
    

    const DEFAULT_TTL = 3600;
    const SHORT_TTL = 300;   
    const LONG_TTL = 86400;  
    

    const PRODUCT_TAGS = ['products', 'catalog'];
    const EMPLOYEE_TAGS = ['employees', 'users'];
    const ORDER_TAGS = ['orders', 'transactions'];
    const STATS_TAGS = ['statistics', 'analytics'];
    const API_PREFIX = 'api';
    const RECOMMENDATIONS_PREFIX = 'recommendations';

    public function getActiveProducts(): \Illuminate\Database\Eloquent\Collection
    {

        if (app()->environment('testing')) {
            return Product::active()->available()->get();
        }
        
        return Cache::remember(self::PRODUCTS_ACTIVE, self::MEDIUM_CACHE, function () {
            return Product::active()->available()->get();
        });
    }

    public function getProductsByCategory($categoryId): \Illuminate\Pagination\LengthAwarePaginator
    {

        if (app()->environment('testing')) {
            return Product::active()->available()->byCategoryId($categoryId)->paginate(12);
        }
        
        $cacheKey = self::PRODUCTS_BY_CATEGORY . $categoryId;
        
        return Cache::remember($cacheKey, self::MEDIUM_CACHE, function () use ($categoryId) {
            return Product::active()->available()->byCategoryId($categoryId)->paginate(12);
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
                ->orderByRaw('CASE WHEN stock = -1 THEN 0 ELSE 1 END')
                ->orderBy('costo_puntos', 'asc')
                ->limit($limit)
                ->get();
        }
        
        return Cache::remember(self::DASHBOARD_FEATURED, self::LONG_CACHE, function () use ($limit) {
            return Product::active()
                ->available()
                ->orderByRaw('CASE WHEN stock = -1 THEN 0 ELSE 1 END')
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



    public function getProductsAdvanced(array $filters = [], int $limit = 20): array
    {
        $filterKey = $this->generateFilterKey($filters);
        $key = "products.advanced.list.{$filterKey}.{$limit}";
        
        return $this->cacheWithTags(self::PRODUCT_TAGS, $key, self::SHORT_TTL, function () use ($filters, $limit) {
            $query = Product::query();
            

            if (!empty($filters['category'])) {
                $query->where('categoria', $filters['category']);
            }
            
            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('nombre', 'like', "%{$filters['search']}%")
                      ->orWhere('descripcion', 'like', "%{$filters['search']}%");
                });
            }
            
            if (!empty($filters['min_points'])) {
                $query->where('costo_puntos', '>=', $filters['min_points']);
            }
            
            if (!empty($filters['max_points'])) {
                $query->where('costo_puntos', '<=', $filters['max_points']);
            }
            
            if (!empty($filters['in_stock'])) {
                $query->where(function ($q) {
                    $q->where('stock', -1)
                      ->orWhere('stock', '>', 0);
                });
            }

            return $query->orderBy('created_at', 'desc')
                        ->limit($limit)
                        ->get()
                        ->toArray();
        });
    }

    public function cacheApiResponse(string $endpoint, array $params, $data, int $ttl = null): void
    {
        $ttl = $ttl ?? self::DEFAULT_TTL;
        $key = $this->buildApiKey($endpoint, $params);
        $tags = $this->getApiTags($endpoint);
        
        $this->cacheWithTags($tags, $key, $ttl, function () use ($data) {
            return $data;
        });
    }

    public function getCachedApiResponse(string $endpoint, array $params)
    {
        $key = $this->buildApiKey($endpoint, $params);
        return Cache::get($key);
    }

    public function getAdvancedCacheStats(): array
    {
        $basicStats = $this->getCacheStats();
        
        try {
            if (config('cache.default') === 'redis') {
                $redis = Redis::connection();
                $info = $redis->info();
                
                return array_merge($basicStats, [
                    'driver' => 'redis',
                    'memory_used' => $info['used_memory_human'] ?? 'Unknown',
                    'memory_peak' => $info['used_memory_peak_human'] ?? 'Unknown',
                    'hits' => $info['keyspace_hits'] ?? 0,
                    'misses' => $info['keyspace_misses'] ?? 0,
                    'hit_rate' => $this->calculateHitRate($info['keyspace_hits'] ?? 0, $info['keyspace_misses'] ?? 0),
                    'total_keys' => $redis->dbsize(),
                    'connected_clients' => $info['connected_clients'] ?? 0,
                    'evicted_keys' => $info['evicted_keys'] ?? 0,
                    'expired_keys' => $info['expired_keys'] ?? 0,
                ]);
            }
            
            return array_merge($basicStats, [
                'driver' => config('cache.default'),
                'message' => 'Advanced statistics available only for Redis driver',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get advanced cache stats', ['error' => $e->getMessage()]);
            return array_merge($basicStats, [
                'error' => 'Failed to retrieve advanced cache statistics',
                'driver' => config('cache.default'),
            ]);
        }
    }

    public function invalidateByTags(array $tags): void
    {
        if ($this->supportsTagging()) {
            Cache::tags($tags)->flush();
            Log::info('Cache invalidated by tags', ['tags' => $tags]);
        } else {

            $this->clearProductCache();
            $this->clearAdminCache();
            Log::warning('Cache tagging not supported, performed pattern-based clearing');
        }
    }

    public function invalidateProductsAdvanced(): void
    {
        $this->clearProductCache();
        $this->invalidateByTags(self::PRODUCT_TAGS);
    }

    public function invalidateEmployeeAdvanced(string $employeeId): void
    {
        $this->clearEmployeeCache($employeeId);
        

        $this->clearCacheByPattern("recommendations.employee.{$employeeId}.*");
        
        $this->invalidateByTags(self::EMPLOYEE_TAGS);
    }

    public function invalidateOrdersAdvanced(): void
    {
        $this->invalidateByTags(self::ORDER_TAGS);
        $this->clearAdminCache();
    }

    public function preloadCriticalCache(): void
    {
        Log::info('Starting critical cache preload');
        
        try {

            $this->getActiveProducts();
            

            $this->getFeaturedProducts();
            

            $this->getPopularProducts();
            

            $this->getAdminStatistics();
            

            $this->getProductsAdvanced(['in_stock' => true], 50);
            $this->getProductsAdvanced([], 20);
            
            Log::info('Critical cache preload completed successfully');
        } catch (\Exception $e) {
            Log::error('Critical cache preload failed', ['error' => $e->getMessage()]);
        }
    }

    private function cacheWithTags(array $tags, string $key, int $ttl, callable $callback)
    {
        if (app()->environment('testing')) {
            return $callback();
        }
        
        if ($this->supportsTagging()) {
            return Cache::tags($tags)->remember($key, $ttl, $callback);
        } else {
            return Cache::remember($key, $ttl, $callback);
        }
    }

    private function buildApiKey(string $endpoint, array $params): string
    {
        $paramKey = !empty($params) ? md5(serialize($params)) : 'default';
        return self::API_PREFIX . ".{$endpoint}.{$paramKey}";
    }

    private function getApiTags(string $endpoint): array
    {
        $tags = [self::API_PREFIX];
        
        if (str_contains($endpoint, 'products') || str_contains($endpoint, 'productos')) {
            $tags = array_merge($tags, self::PRODUCT_TAGS);
        }
        
        if (str_contains($endpoint, 'orders') || str_contains($endpoint, 'pedidos')) {
            $tags = array_merge($tags, self::ORDER_TAGS);
        }
        
        if (str_contains($endpoint, 'employees') || str_contains($endpoint, 'empleados')) {
            $tags = array_merge($tags, self::EMPLOYEE_TAGS);
        }
        
        if (str_contains($endpoint, 'stats') || str_contains($endpoint, 'statistics')) {
            $tags = array_merge($tags, self::STATS_TAGS);
        }
        
        return array_unique($tags);
    }

    private function generateFilterKey(array $filters): string
    {
        if (empty($filters)) {
            return 'default';
        }
        
        ksort($filters);
        return md5(serialize($filters));
    }

    private function supportsTagging(): bool
    {
        return in_array(config('cache.default'), ['redis', 'memcached']);
    }

    private function calculateHitRate(int $hits, int $misses): float
    {
        $total = $hits + $misses;
        return $total > 0 ? round(($hits / $total) * 100, 2) : 0;
    }

    private function clearCacheByPattern(string $pattern): void
    {
        if (config('cache.default') === 'redis') {
            try {
                $redis = Redis::connection();
                $keys = $redis->keys($pattern);
                
                if (!empty($keys)) {
                    $redis->del($keys);
                    Log::info('Cache cleared by pattern', ['pattern' => $pattern, 'count' => count($keys)]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to clear cache by pattern', [
                    'pattern' => $pattern,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function logCacheMetrics(): void
    {
        try {
            $stats = $this->getAdvancedCacheStats();
            
            Log::info('Cache performance metrics', [
                'driver' => $stats['driver'] ?? 'unknown',
                'memory_used' => $stats['memory_used'] ?? 'unknown',
                'hit_rate' => $stats['hit_rate'] ?? 'unknown',
                'total_keys' => $stats['total_keys'] ?? 'unknown',
                'evicted_keys' => $stats['evicted_keys'] ?? 0,
                'expired_keys' => $stats['expired_keys'] ?? 0,
            ]);
            

            if (isset($stats['hit_rate']) && $stats['hit_rate'] < 70) {
                Log::warning('Low cache hit rate detected', ['hit_rate' => $stats['hit_rate']]);
            }
            

            if (isset($stats['evicted_keys']) && $stats['evicted_keys'] > 1000) {
                Log::warning('High cache eviction rate detected', ['evicted_keys' => $stats['evicted_keys']]);
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to log cache metrics', ['error' => $e->getMessage()]);
        }
    }
}