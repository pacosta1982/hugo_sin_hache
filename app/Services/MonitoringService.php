<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Order;

class MonitoringService
{
    public function logMetrics(): array
    {
        $metrics = [
            'timestamp' => now()->toISOString(),
            'system' => $this->getSystemMetrics(),
            'database' => $this->getDatabaseMetrics(),
            'cache' => $this->getCacheMetrics(),
            'business' => $this->getBusinessMetrics(),
            'performance' => $this->getPerformanceMetrics(),
        ];

        Log::channel('metrics')->info('Application Metrics', $metrics);

        return $metrics;
    }

    private function getSystemMetrics(): array
    {
        return [
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'memory_limit' => ini_get('memory_limit'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
        ];
    }

    private function getDatabaseMetrics(): array
    {
        $startTime = microtime(true);
        
        try {

            DB::connection()->getPdo();
            $connectionTime = (microtime(true) - $startTime) * 1000;
            

            $employeeCount = Employee::count();
            $productCount = Product::count();
            $orderCount = Order::count();
            $activeProductCount = Product::where('activo', true)->count();
            
            return [
                'connection_status' => 'healthy',
                'connection_time_ms' => round($connectionTime, 2),
                'total_employees' => $employeeCount,
                'total_products' => $productCount,
                'active_products' => $activeProductCount,
                'total_orders' => $orderCount,
                'database_size' => $this->getDatabaseSize(),
            ];
        } catch (\Exception $e) {
            Log::error('Database metrics collection failed', ['error' => $e->getMessage()]);
            
            return [
                'connection_status' => 'error',
                'error' => $e->getMessage(),
                'connection_time_ms' => null,
            ];
        }
    }

    private function getCacheMetrics(): array
    {
        $startTime = microtime(true);
        
        try {

            $testKey = 'monitoring_test_' . time();
            $testValue = 'test_data';
            
            Cache::put($testKey, $testValue, 60);
            $retrieved = Cache::get($testKey);
            Cache::forget($testKey);
            
            $cacheTime = (microtime(true) - $startTime) * 1000;
            
            return [
                'cache_status' => $retrieved === $testValue ? 'healthy' : 'error',
                'cache_driver' => config('cache.default'),
                'operation_time_ms' => round($cacheTime, 2),
                'test_successful' => $retrieved === $testValue,
            ];
        } catch (\Exception $e) {
            Log::error('Cache metrics collection failed', ['error' => $e->getMessage()]);
            
            return [
                'cache_status' => 'error',
                'error' => $e->getMessage(),
                'cache_driver' => config('cache.default'),
            ];
        }
    }

    private function getBusinessMetrics(): array
    {
        try {
            $today = now()->startOfDay();
            $thisWeek = now()->startOfWeek();
            $thisMonth = now()->startOfMonth();
            
            return [
                'orders_today' => Order::where('created_at', '>=', $today)->count(),
                'orders_this_week' => Order::where('created_at', '>=', $thisWeek)->count(),
                'orders_this_month' => Order::where('created_at', '>=', $thisMonth)->count(),
                'pending_orders' => Order::where('estado', 'Pendiente')->count(),
                'completed_orders' => Order::where('estado', 'Realizado')->count(),
                'cancelled_orders' => Order::where('estado', 'Cancelado')->count(),
                'total_points_redeemed_today' => Order::where('created_at', '>=', $today)->sum('puntos_utilizados'),
                'average_order_value' => Order::avg('puntos_utilizados'),
                'active_employees' => Employee::whereHas('orders', function($query) use ($thisMonth) {
                    $query->where('created_at', '>=', $thisMonth);
                })->count(),
                'low_stock_products' => Product::where('activo', true)
                    ->where('stock', '>', 0)
                    ->where('stock', '<=', 5)
                    ->where('stock', '!=', -1)
                    ->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Business metrics collection failed', ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }

    private function getPerformanceMetrics(): array
    {
        $startTime = microtime(true);
        

        $dbQueryTime = $this->measureDatabaseQuery();
        $cacheAccessTime = $this->measureCacheAccess();
        
        $totalTime = (microtime(true) - $startTime) * 1000;
        
        return [
            'total_metrics_collection_time_ms' => round($totalTime, 2),
            'database_query_time_ms' => $dbQueryTime,
            'cache_access_time_ms' => $cacheAccessTime,
            'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'memory_peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
        ];
    }

    private function measureDatabaseQuery(): float
    {
        $startTime = microtime(true);
        
        try {

            DB::table('employees')->select('id_empleado')->first();
            return round((microtime(true) - $startTime) * 1000, 2);
        } catch (\Exception $e) {
            return -1;
        }
    }

    private function measureCacheAccess(): float
    {
        $startTime = microtime(true);
        
        try {
            Cache::get('non_existent_key');
            return round((microtime(true) - $startTime) * 1000, 2);
        } catch (\Exception $e) {
            return -1;
        }
    }

    private function getDatabaseSize(): ?int
    {
        try {
            if (config('database.default') === 'sqlite') {
                $dbPath = config('database.connections.sqlite.database');
                return file_exists($dbPath) ? filesize($dbPath) : null;
            }
            

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getHealthStatus(): array
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'checks' => [],
        ];


        try {
            DB::connection()->getPdo();
            $health['checks']['database'] = [
                'status' => 'healthy',
                'message' => 'Database connection successful'
            ];
        } catch (\Exception $e) {
            $health['status'] = 'unhealthy';
            $health['checks']['database'] = [
                'status' => 'error',
                'message' => 'Database connection failed: ' . $e->getMessage()
            ];
        }


        try {
            $testKey = 'health_check_' . time();
            Cache::put($testKey, 'test', 60);
            $retrieved = Cache::get($testKey);
            Cache::forget($testKey);
            
            if ($retrieved === 'test') {
                $health['checks']['cache'] = [
                    'status' => 'healthy',
                    'message' => 'Cache operations successful'
                ];
            } else {
                $health['status'] = 'degraded';
                $health['checks']['cache'] = [
                    'status' => 'warning',
                    'message' => 'Cache operations inconsistent'
                ];
            }
        } catch (\Exception $e) {
            $health['status'] = 'degraded';
            $health['checks']['cache'] = [
                'status' => 'warning',
                'message' => 'Cache operations failed: ' . $e->getMessage()
            ];
        }


        try {
            $firebaseService = app(FirebaseService::class);
            if ($firebaseService->isConfigured()) {
                $health['checks']['firebase'] = [
                    'status' => 'healthy',
                    'message' => 'Firebase authentication configured'
                ];
            } else {
                $health['checks']['firebase'] = [
                    'status' => 'warning',
                    'message' => 'Firebase authentication not configured'
                ];
            }
        } catch (\Exception $e) {
            $health['checks']['firebase'] = [
                'status' => 'error',
                'message' => 'Firebase check failed: ' . $e->getMessage()
            ];
        }


        try {
            $testFile = storage_path('logs/health_check_test.txt');
            file_put_contents($testFile, 'test');
            $content = file_get_contents($testFile);
            unlink($testFile);
            
            if ($content === 'test') {
                $health['checks']['storage'] = [
                    'status' => 'healthy',
                    'message' => 'Storage write/read operations successful'
                ];
            } else {
                $health['status'] = 'degraded';
                $health['checks']['storage'] = [
                    'status' => 'warning',
                    'message' => 'Storage operations inconsistent'
                ];
            }
        } catch (\Exception $e) {
            $health['status'] = 'degraded';
            $health['checks']['storage'] = [
                'status' => 'warning',
                'message' => 'Storage operations failed: ' . $e->getMessage()
            ];
        }

        return $health;
    }

    public function logSecurityEvent(string $event, array $context = []): void
    {
        Log::channel('security')->warning("Security Event: {$event}", [
            'timestamp' => now()->toISOString(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'context' => $context,
        ]);
    }

    public function logBusinessEvent(string $event, array $context = []): void
    {
        Log::channel('business')->info("Business Event: {$event}", [
            'timestamp' => now()->toISOString(),
            'context' => $context,
        ]);
    }

    public function logPerformanceIssue(string $operation, float $duration, array $context = []): void
    {
        Log::channel('performance')->warning("Performance Issue: {$operation}", [
            'timestamp' => now()->toISOString(),
            'duration_ms' => $duration,
            'threshold_exceeded' => true,
            'context' => $context,
        ]);
    }
}