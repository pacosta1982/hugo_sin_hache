<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Models\Employee;

class ResetFirebasePassword extends Command
{
    protected $signature = 'firebase:reset-password {email} {new-password}';
    
    protected $description = 'Reset a Firebase user password';

    public function handle()
    {
        $email = $this->argument('email');
        $newPassword = $this->argument('new-password');

        $firebaseService = app(FirebaseService::class);

        if (!$firebaseService->isConfigured()) {
            $this->error('❌ Firebase is not configured. Run: php artisan firebase:test');
            return 1;
        }

        try {

            $firebaseUser = $firebaseService->getUserByEmail($email);
            
            if (!$firebaseUser) {
                $this->error("❌ User with email {$email} not found in Firebase");
                return 1;
            }

            $this->info("🔍 Found user: {$firebaseUser->email}");
            $this->info("   UID: {$firebaseUser->uid}");


            $this->info("🔐 Updating password...");
            
            $success = $firebaseService->updateUserPassword($firebaseUser->uid, $newPassword);
            
            if (!$success) {
                $this->error('❌ Failed to update password');
                return 1;
            }

            $this->info('✅ Password updated successfully');
            

            if ($this->confirm('Revoke all existing tokens to force re-login on all devices?', true)) {
                $revokeSuccess = $firebaseService->revokeRefreshTokens($firebaseUser->uid);
                if ($revokeSuccess) {
                    $this->info('✅ All refresh tokens revoked');
                } else {
                    $this->warn('⚠️  Failed to revoke refresh tokens');
                }
            }


            $this->info('');
            $this->info('📊 Password Reset Summary:');
            $this->table(
                ['Field', 'Value'],
                [
                    ['User Email', $firebaseUser->email],
                    ['Firebase UID', $firebaseUser->uid],
                    ['Password Updated', '✅ Yes'],
                    ['Tokens Revoked', $revokeSuccess ?? false ? '✅ Yes' : '❌ No'],
                ]
            );

            $this->info('🎉 Password reset completed successfully!');
            $this->info('💡 The user can now log in with the new password');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Failed to reset password: ' . $e->getMessage());
            return 1;
        }
    }
}