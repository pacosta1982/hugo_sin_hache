<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;

class ListFirebaseUsers extends Command
{
    protected $signature = 'firebase:list-users {--limit=10 : Maximum number of users to display}';
    
    protected $description = 'List Firebase users';

    public function handle()
    {
        $limit = (int) $this->option('limit');

        $firebaseService = app(FirebaseService::class);

        if (!$firebaseService->isConfigured()) {
            $this->error('❌ Firebase is not configured. Run: php artisan firebase:test');
            return 1;
        }

        try {
            $this->info("👥 Listing Firebase users (limit: {$limit})...");
            
            $result = $firebaseService->listUsers($limit);
            
            if (empty($result['users'])) {
                $this->info('📭 No users found in Firebase project');
                return 0;
            }

            $users = $result['users'];
            $this->info("Found " . count($users) . " user(s):");
            

            $tableData = [];
            foreach ($users as $user) {
                $tableData[] = [
                    substr($user['uid'], 0, 12) . '...', // Truncate UID for display
                    $user['email'] ?? 'No email',
                    $user['displayName'] ?? 'No name',
                    $user['disabled'] ? '❌ Disabled' : '✅ Enabled',
                    $user['emailVerified'] ? '✅ Verified' : '❌ Not verified',
                    $user['createdAt'],
                    $user['lastLoginAt'] ?? 'Never',
                ];
            }

            $this->table(
                ['UID', 'Email', 'Display Name', 'Status', 'Email Verified', 'Created', 'Last Login'],
                $tableData
            );

            if (isset($result['pageToken'])) {
                $this->info('💡 There are more users available. Increase --limit to see more.');
            }

            $this->info('');
            $this->info('💡 Management commands:');
            $this->line('   • Create user: php artisan firebase:create-user <email> <password>');
            $this->line('   • Reset password: php artisan firebase:reset-password <email> <new-password>');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Failed to list users: ' . $e->getMessage());
            return 1;
        }
    }
}