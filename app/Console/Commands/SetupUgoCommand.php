<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupUgoCommand extends Command
{
    protected $signature = 'ugo:setup {--force : Force setup even if already configured}';

    protected $description = 'Set up the UGo application with initial configuration';

    public function handle()
    {
        $this->info('🚀 Setting up UGo Laravel Application...');


        if (!File::exists(base_path('.env'))) {
            $this->info('📝 Creating .env file from .env.example...');
            File::copy(base_path('.env.example'), base_path('.env'));
        }


        if (empty(config('app.key'))) {
            $this->info('🔑 Generating application key...');
            $this->call('key:generate');
        }


        $this->info('🗄️ Setting up database...');
        
        try {

            $dbDir = dirname(database_path('ugo.db'));
            if (!File::exists($dbDir)) {
                File::makeDirectory($dbDir, 0755, true);
                $this->info('📁 Created database directory');
            }


            $this->info('🔄 Running database migrations...');
            $this->call('migrate', ['--force' => true]);


            if ($this->option('force') || $this->shouldSeedDatabase()) {
                $this->info('🌱 Seeding database with sample data...');
                $this->call('db:seed', ['--force' => true]);
            }

        } catch (\Exception $e) {
            $this->error('❌ Database setup failed: ' . $e->getMessage());
            return 1;
        }


        $this->info('🔥 Checking Firebase configuration...');
        $firebaseCredentialsPath = base_path(config('firebase.projects.app.credentials', 'firebase-admin-credentials.json'));
        
        if (!File::exists($firebaseCredentialsPath)) {
            $this->warn('⚠️  Firebase credentials file not found: ' . $firebaseCredentialsPath);
            $this->line('   Please add your Firebase Admin SDK credentials file');
        } else {
            $this->info('✅ Firebase credentials found');
        }


        $envContent = File::get(base_path('.env'));
        $requiredFirebaseVars = [
            'VITE_FIREBASE_API_KEY',
            'VITE_FIREBASE_AUTH_DOMAIN',
            'VITE_FIREBASE_PROJECT_ID'
        ];

        $missingVars = [];
        foreach ($requiredFirebaseVars as $var) {
            if (!str_contains($envContent, $var . '=') || str_contains($envContent, $var . '=')) {

                preg_match('/' . $var . '=(.*)$/m', $envContent, $matches);
                if (empty($matches[1] ?? '')) {
                    $missingVars[] = $var;
                }
            }
        }

        if (!empty($missingVars)) {
            $this->warn('⚠️  Missing Firebase frontend configuration:');
            foreach ($missingVars as $var) {
                $this->line('   - ' . $var);
            }
        } else {
            $this->info('✅ Firebase frontend configuration complete');
        }


        $this->info('🔗 Creating storage link...');
        $this->call('storage:link');


        $this->info('🧹 Clearing application caches...');
        $this->call('config:clear');
        $this->call('view:clear');
        $this->call('route:clear');

        $this->newLine();
        $this->info('🎉 UGo setup completed successfully!');
        $this->newLine();
        
        $this->line('Next steps:');
        $this->line('1. Configure Firebase credentials and environment variables');
        $this->line('2. Run: npm install && npm run build');
        $this->line('3. Start the server: php artisan serve');
        $this->line('4. Visit: http://localhost:8000');

        return 0;
    }

    private function shouldSeedDatabase(): bool
    {
        try {
            return \App\Models\Employee::count() === 0;
        } catch (\Exception $e) {
            return true; // If we can't check, assume we need to seed
        }
    }
}