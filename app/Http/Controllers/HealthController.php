<?php

namespace App\Http\Controllers;

use App\Services\MonitoringService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    public function __construct(
        private MonitoringService $monitoringService
    ) {}

    public function basic(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
            'service' => 'UGo Points System',
            'version' => '1.0.0',
        ]);
    }

    public function detailed(): JsonResponse
    {
        $health = $this->monitoringService->getHealthStatus();
        
        $statusCode = match($health['status']) {
            'healthy' => 200,
            'degraded' => 200,
            'unhealthy' => 503,
            default => 500
        };

        return response()->json($health, $statusCode);
    }

    public function metrics(): JsonResponse
    {
        $metrics = Cache::remember('app_metrics', 60, function () {
            return $this->monitoringService->logMetrics();
        });

        return response()->json($metrics);
    }

    public function status(): JsonResponse
    {
        $health = $this->monitoringService->getHealthStatus();
        $metrics = Cache::remember('app_metrics', 60, function () {
            return $this->monitoringService->logMetrics();
        });

        return response()->json([
            'health' => $health,
            'metrics' => [
                'system' => $metrics['system'] ?? [],
                'database' => $metrics['database'] ?? [],
                'cache' => $metrics['cache'] ?? [],
                'business' => $metrics['business'] ?? [],
                'performance' => $metrics['performance'] ?? [],
            ],
            'summary' => [
                'overall_status' => $health['status'],
                'total_employees' => $metrics['database']['total_employees'] ?? 0,
                'total_products' => $metrics['database']['total_products'] ?? 0,
                'active_products' => $metrics['database']['active_products'] ?? 0,
                'total_orders' => $metrics['database']['total_orders'] ?? 0,
                'pending_orders' => $metrics['business']['pending_orders'] ?? 0,
                'orders_today' => $metrics['business']['orders_today'] ?? 0,
                'memory_usage_mb' => $metrics['performance']['memory_usage_mb'] ?? 0,
                'database_connection_time_ms' => $metrics['database']['connection_time_ms'] ?? 0,
            ]
        ]);
    }

    public function performance(): JsonResponse
    {
        $startTime = microtime(true);
        
        $metrics = [
            'timestamp' => now()->toISOString(),
            'response_time_ms' => 0,
            'memory_usage' => [
                'current_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
                'peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
                'limit' => ini_get('memory_limit'),
            ],
            'php' => [
                'version' => PHP_VERSION,
                'opcache_enabled' => function_exists('opcache_get_status') && opcache_get_status() !== false,
                'max_execution_time' => ini_get('max_execution_time'),
            ],
            'laravel' => [
                'version' => app()->version(),
                'environment' => config('app.env'),
                'debug_mode' => config('app.debug'),
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
            ],
        ];

        $metrics['response_time_ms'] = round((microtime(true) - $startTime) * 1000, 2);

        return response()->json($metrics);
    }

    public function ready(): JsonResponse
    {
        $health = $this->monitoringService->getHealthStatus();
        
        $isReady = in_array($health['status'], ['healthy', 'degraded']);
        
        $response = [
            'ready' => $isReady,
            'timestamp' => now()->toISOString(),
            'checks' => $health['checks'],
        ];

        return response()->json($response, $isReady ? 200 : 503);
    }

    public function live(): JsonResponse
    {
        try {
            return response()->json([
                'alive' => true,
                'timestamp' => now()->toISOString(),
                'service' => 'UGo Points System',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'alive' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    public function config(): JsonResponse
    {
        return response()->json([
            'timestamp' => now()->toISOString(),
            'configuration' => [
                'app' => [
                    'name' => config('app.name'),
                    'environment' => config('app.env'),
                    'debug' => config('app.debug'),
                    'url' => config('app.url'),
                    'timezone' => config('app.timezone'),
                ],
                'database' => [
                    'default' => config('database.default'),
                    'connection' => config('database.connections.' . config('database.default') . '.driver'),
                ],
                'cache' => [
                    'default' => config('cache.default'),
                ],
                'session' => [
                    'driver' => config('session.driver'),
                    'lifetime' => config('session.lifetime'),
                ],
                'firebase' => [
                    'configured' => !empty(config('firebase.projects.app.credentials')),
                    'project_id' => config('firebase.projects.app.project_id'),
                ],
                'logging' => [
                    'default' => config('logging.default'),
                    'channels' => array_keys(config('logging.channels', [])),
                ],
            ],
            'environment_variables' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
            ],
        ]);
    }
}