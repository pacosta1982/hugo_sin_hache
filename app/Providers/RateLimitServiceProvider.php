<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Default API rate limiting - 60 requests per minute per user
        RateLimiter::for('api', function (Request $request) {
            $user = $request->get('employee');
            $key = $user ? $user->id_empleado : $request->ip();
            
            return Limit::perMinute(60)->by($key);
        });

        // Login rate limiting - 5 attempts per minute per IP
        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip()),
                Limit::perHour(20)->by($request->ip())
            ];
        });

        // Product redemption rate limiting - 3 redemptions per minute per user
        RateLimiter::for('redemption', function (Request $request) {
            $user = $request->get('employee');
            $key = $user ? $user->id_empleado : $request->ip();
            
            return [
                Limit::perMinute(3)->by($key),
                Limit::perHour(20)->by($key)
            ];
        });

        // Search rate limiting - 30 searches per minute per user/IP
        RateLimiter::for('search', function (Request $request) {
            $user = $request->get('employee');
            $key = $user ? $user->id_empleado : $request->ip();
            
            return Limit::perMinute(30)->by($key);
        });

        // Admin operations - 120 requests per minute per admin
        RateLimiter::for('admin', function (Request $request) {
            $user = $request->get('employee');
            $key = $user && $user->is_admin ? $user->id_empleado : $request->ip();
            
            return Limit::perMinute(120)->by($key);
        });

        // Favorites operations - 10 changes per minute per user
        RateLimiter::for('favorites', function (Request $request) {
            $user = $request->get('employee');
            $key = $user ? $user->id_empleado : $request->ip();
            
            return Limit::perMinute(10)->by($key);
        });

        // Profile updates - 5 updates per hour per user
        RateLimiter::for('profile', function (Request $request) {
            $user = $request->get('employee');
            $key = $user ? $user->id_empleado : $request->ip();
            
            return Limit::perHour(5)->by($key);
        });

        // Firebase operations - stricter limits for security
        RateLimiter::for('firebase', function (Request $request) {
            return [
                Limit::perMinute(10)->by($request->ip()),
                Limit::perHour(50)->by($request->ip())
            ];
        });
    }
}