<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Factory;

class TestFirebaseConnection extends Command
{
    protected $signature = 'firebase:test';
    
    protected $description = 'Test Firebase connection and configuration';

    public function handle()
    {
        $this->info('🔥 Testing Firebase Connection...');
        

        $this->info('📋 Checking Configuration...');
        
        $requiredEnvVars = [
            'FIREBASE_PROJECT_ID' => env('FIREBASE_PROJECT_ID'),
            'FIREBASE_CREDENTIALS' => env('FIREBASE_CREDENTIALS'),
            'VITE_FIREBASE_API_KEY' => env('VITE_FIREBASE_API_KEY'),
            'VITE_FIREBASE_AUTH_DOMAIN' => env('VITE_FIREBASE_AUTH_DOMAIN'),
            'VITE_FIREBASE_PROJECT_ID' => env('VITE_FIREBASE_PROJECT_ID'),
        ];
        
        $configComplete = true;
        foreach ($requiredEnvVars as $var => $value) {
            if (empty($value)) {
                $this->error("❌ {$var} is not set");
                $configComplete = false;
            } else {
                $this->info("✅ {$var} is configured");
            }
        }
        

        $credentialsPath = env('FIREBASE_CREDENTIALS');
        if ($credentialsPath && file_exists($credentialsPath)) {
            $this->info("✅ Service account file exists: {$credentialsPath}");
            

            $content = file_get_contents($credentialsPath);
            if (str_contains($content, 'dummy-key-id') || str_contains($content, 'demo')) {
                $this->warn("⚠️  Using demo/dummy credentials - not suitable for production");
            }
        } else {
            $this->error("❌ Service account file not found: {$credentialsPath}");
            $configComplete = false;
        }
        
        if (!$configComplete) {
            $this->error('❌ Configuration incomplete. Run: php artisan serve and visit /firebase-setup for setup guide.');
            return 1;
        }
        

        try {
            $this->info('🔑 Testing Firebase Auth connection...');
            $auth = app(Auth::class);
            

            $listUsersResult = $auth->listUsers(1, 1);
            $this->info('✅ Firebase Auth connection successful');
            

            foreach ($listUsersResult as $user) {
                $this->info("   📧 Sample user: {$user->email}");
                break;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Firebase Auth connection failed: ' . $e->getMessage());
            
            if (str_contains($e->getMessage(), 'credentials')) {
                $this->warn('💡 This usually means the service account credentials are invalid');
            }
            
            if (str_contains($e->getMessage(), 'project')) {
                $this->warn('💡 Check if FIREBASE_PROJECT_ID matches your actual Firebase project');
            }
            
            return 1;
        }
        

        $this->info('🎯 Testing token verification...');
        try {

            $customToken = $auth->createCustomToken('test-uid');
            $this->info('✅ Custom token creation successful');
            $this->line("   🎫 Sample token: " . substr($customToken->toString(), 0, 50) . "...");
            
        } catch (\Exception $e) {
            $this->error('❌ Custom token creation failed: ' . $e->getMessage());
        }
        

        $this->info('');
        $this->info('📊 Configuration Summary:');
        $this->table(
            ['Component', 'Status', 'Notes'],
            [
                ['Environment Variables', $configComplete ? '✅ Complete' : '❌ Incomplete', 'Check .env file'],
                ['Service Account', file_exists($credentialsPath ?? '') ? '✅ Found' : '❌ Missing', $credentialsPath ?? 'Not set'],
                ['Firebase Auth', 'See above', 'Connection test results above'],
                ['Frontend Config', env('VITE_FIREBASE_API_KEY') ? '✅ Set' : '❌ Missing', 'Check VITE_* variables'],
            ]
        );
        
        if ($configComplete) {
            $this->info('🎉 Firebase is properly configured and working!');
            $this->info('💡 Next steps:');
            $this->line('   1. Create users in Firebase Console');
            $this->line('   2. Add employee records to database with matching Firebase UIDs');
            $this->line('   3. Test login flow in browser');
            return 0;
        } else {
            $this->error('❌ Firebase configuration needs attention');
            $this->info('💡 Run: php artisan serve and visit /firebase-setup for detailed setup guide');
            return 1;
        }
    }
}