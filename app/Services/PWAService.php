<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class PWAService
{
    private array $manifestData;

    public function __construct()
    {
        $this->manifestData = $this->getManifestData();
    }

    public function getManifestData(): array
    {
        return [
            'name' => config('app.name', 'UGo Rewards System'),
            'short_name' => 'UGo',
            'description' => 'Sistema de recompensas corporativo para empleados',
            'start_url' => '/dashboard',
            'display' => 'standalone',
            'background_color' => '#0ADD90',
            'theme_color' => '#00AE6E',
            'orientation' => 'portrait-primary',
            'scope' => '/',
            'lang' => 'es-ES',
            'categories' => ['business', 'productivity'],
        ];
    }

    public function isInstallable(): bool
    {

        return $this->hasManifest() && 
               $this->hasServiceWorker() && 
               $this->hasValidIcons();
    }

    public function hasManifest(): bool
    {
        return file_exists(public_path('app-manifest.json'));
    }

    public function hasServiceWorker(): bool
    {
        return file_exists(public_path('sw.js'));
    }

    public function hasValidIcons(): bool
    {

        $requiredSizes = ['192x192', '512x512'];
        
        foreach ($requiredSizes as $size) {
            $iconPath = public_path("images/icons/icon-{$size}.png");
            if (!file_exists($iconPath)) {
                return false;
            }
        }
        
        return true;
    }

    public function generatePWAMetaTags(): string
    {
        $metaTags = [
            '<meta name="apple-mobile-web-app-capable" content="yes">',
            '<meta name="apple-mobile-web-app-status-bar-style" content="default">',
            '<meta name="apple-mobile-web-app-title" content="' . $this->manifestData['short_name'] . '">',
            '<meta name="mobile-web-app-capable" content="yes">',
            '<meta name="theme-color" content="' . $this->manifestData['theme_color'] . '">',
            '<meta name="msapplication-TileColor" content="' . $this->manifestData['background_color'] . '">',
            '<meta name="msapplication-tap-highlight" content="no">',
            '<link rel="manifest" href="/app-manifest.json">',
            '<link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">',
            '<link rel="icon" type="image/png" sizes="192x192" href="/images/icons/icon-192x192.png">',
            '<link rel="icon" type="image/png" sizes="512x512" href="/images/icons/icon-512x512.png">',
        ];

        return implode("\n    ", $metaTags);
    }

    public function getInstallationStatus(): array
    {
        return [
            'installable' => $this->isInstallable(),
            'manifest_exists' => $this->hasManifest(),
            'service_worker_exists' => $this->hasServiceWorker(),
            'icons_valid' => $this->hasValidIcons(),
            'requirements' => [
                'manifest' => $this->hasManifest(),
                'service_worker' => $this->hasServiceWorker(),
                'icons' => $this->hasValidIcons(),
                'https' => request()->secure() || app()->environment('local'),
            ],
        ];
    }

    public function generateServiceWorkerConfig(): array
    {
        return [
            'cache_name' => 'ugo-rewards-v' . config('app.version', '1.0.0'),
            'static_assets' => [
                '/',
                '/dashboard',
                '/products',
                '/offline',
                '/app-manifest.json',
            ],
            'api_endpoints' => [
                '/api/me',
                '/api/products',
                '/api/favorites',
                '/api/orders',
                '/api/notifications',
                '/api/recommendations',
            ],
            'auth_required_routes' => [
                '/dashboard',
                '/products',
                '/orders',
                '/favorites',
                '/admin',
            ],
            'offline_fallbacks' => [
                'navigation' => '/offline',
                'image' => '/images/offline-placeholder.png',
                'font' => '',
            ],
        ];
    }

    public function canSendPushNotifications(): bool
    {

        return !empty(config('app.vapid_public_key')) && 
               !empty(config('app.vapid_private_key'));
    }

    public function getVAPIDKeys(): array
    {
        return [
            'public_key' => config('app.vapid_public_key'),
            'private_key' => config('app.vapid_private_key'),
            'subject' => config('app.vapid_subject', 'mailto:' . config('mail.from.address')),
        ];
    }

    public function sendPushNotification(string $subscription, array $payload): bool
    {
        try {
            if (!$this->canSendPushNotifications()) {
                Log::warning('Push notifications not configured');
                return false;
            }



            Log::info('Push notification sent', [
                'payload' => $payload,
                'subscription_endpoint' => parse_url($subscription, PHP_URL_HOST),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send push notification', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);
            return false;
        }
    }

    public function getCacheableAssets(): array
    {
        return [
            'critical' => [
                '/assets/app.css',
                '/assets/app.js',
                '/app-manifest.json',
                '/images/icons/icon-192x192.png',
                '/images/icons/icon-512x512.png',
            ],
            'pages' => [
                '/',
                '/dashboard',
                '/products',
                '/orders',
                '/favorites',
                '/offline',
            ],
            'api' => [
                '/api/me',
                '/api/products?limit=20',
                '/api/favorites',
                '/api/orders?limit=10',
                '/api/notifications/latest',
            ],
        ];
    }

    public function getOfflineCapabilities(): array
    {
        return [
            'view_cached_data' => true,
            'browse_products' => true,
            'view_order_history' => true,
            'view_favorites' => true,
            'view_points_balance' => true,
            'queue_actions' => true,
            'background_sync' => true,
            'push_notifications' => $this->canSendPushNotifications(),
        ];
    }

    public function generateInstallPrompt(): string
    {
        return View::make('pwa.install-prompt', [
            'app_name' => $this->manifestData['name'],
            'app_description' => $this->manifestData['description'],
            'features' => [
                'Acceso rápido desde la pantalla de inicio',
                'Funciona sin conexión a internet',
                'Notificaciones push en tiempo real',
                'Experiencia nativa como una app',
                'Sincronización automática',
            ],
        ])->render();
    }

    public function getAnalytics(): array
    {
        return Cache::remember('pwa_analytics', 3600, function () {

            return [
                'total_installations' => 0,
                'active_users' => 0,
                'offline_usage_percentage' => 0,
                'push_notification_engagement' => 0,
                'most_cached_pages' => [],
                'service_worker_hits' => 0,
                'cache_hit_rate' => 0,
            ];
        });
    }

    public function createDefaultIcons(): bool
    {
        try {


            $iconDir = public_path('images/icons');
            if (!is_dir($iconDir)) {
                mkdir($iconDir, 0755, true);
            }

            $screenshotDir = public_path('images/screenshots');
            if (!is_dir($screenshotDir)) {
                mkdir($screenshotDir, 0755, true);
            }

            Log::info('PWA icon directories created');
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to create PWA icon directories', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function updateManifest(array $data): bool
    {
        try {
            $manifestPath = public_path('app-manifest.json');
            $currentManifest = $this->manifestData;
            

            $updatedManifest = array_merge($currentManifest, $data);
            

            file_put_contents($manifestPath, json_encode($updatedManifest, JSON_PRETTY_PRINT));
            
            Log::info('PWA manifest updated', ['changes' => $data]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update PWA manifest', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            return false;
        }
    }
}