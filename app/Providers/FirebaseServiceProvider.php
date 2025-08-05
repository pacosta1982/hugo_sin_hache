<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
// Removed Database and Storage imports - only using Auth

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Factory::class, function ($app) {
            $credentialsPath = config('firebase.projects.app.credentials');
            
            if (!$credentialsPath) {
                throw new \Exception('Firebase credentials path not configured in .env file (FIREBASE_CREDENTIALS)');
            }
            
            // Try relative path first, then absolute path from base_path
            $absolutePath = $credentialsPath;
            if (!file_exists($absolutePath)) {
                $absolutePath = base_path($credentialsPath);
            }
            
            if (!file_exists($absolutePath)) {
                throw new \Exception("Firebase credentials file not found. Tried: {$credentialsPath} and {$absolutePath}");
            }

            return (new Factory())
                ->withServiceAccount($absolutePath);
        });

        $this->app->singleton(Auth::class, function ($app) {
            return $app->make(Factory::class)->createAuth();
        });

        // Database and Storage services removed - only using Auth for this implementation
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}