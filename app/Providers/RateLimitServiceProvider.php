<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimitServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    protected function configureRateLimiting(): void
    {

        RateLimiter::for('api', function (Request $request) {
            $user = $request->get('employee');
            $key = $user ? $user->id_empleado : $request->ip();
            
            return Limit::perMinute(60)->by($key);
        });


        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip()),
                Limit::perHour(20)->by($request->ip())
            ];
        });


        RateLimiter::for('redemption', function (Request $request) {
            $user = $request->get('employee');
            $key = $user ? $user->id_empleado : $request->ip();
            
            return [
                Limit::perMinute(3)->by($key),
                Limit::perHour(20)->by($key)
            ];
        });


        RateLimiter::for('search', function (Request $request) {
            $user = $request->get('employee');
            $key = $user ? $user->id_empleado : $request->ip();
            
            return Limit::perMinute(30)->by($key);
        });


        RateLimiter::for('admin', function (Request $request) {
            $user = $request->get('employee');
            $key = $user && $user->is_admin ? $user->id_empleado : $request->ip();
            
            return Limit::perMinute(120)->by($key);
        });


        RateLimiter::for('favorites', function (Request $request) {
            $user = $request->get('employee');
            $key = $user ? $user->id_empleado : $request->ip();
            
            return Limit::perMinute(10)->by($key);
        });


        RateLimiter::for('profile', function (Request $request) {
            $user = $request->get('employee');
            $key = $user ? $user->id_empleado : $request->ip();
            
            return Limit::perHour(5)->by($key);
        });


        RateLimiter::for('firebase', function (Request $request) {
            return [
                Limit::perMinute(10)->by($request->ip()),
                Limit::perHour(50)->by($request->ip())
            ];
        });
    }
}