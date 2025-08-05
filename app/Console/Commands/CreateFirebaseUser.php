<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Models\Employee;

class CreateFirebaseUser extends Command
{
    protected $signature = 'firebase:create-user {email} {password} {--name=} {--admin}';
    
    protected $description = 'Create a new Firebase user and sync with employee database';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->option('name') ?: explode('@', $email)[0];
        $isAdmin = $this->option('admin');

        $firebaseService = app(FirebaseService::class);

        if (!$firebaseService->isConfigured()) {
            $this->error('❌ Firebase is not configured. Run: php artisan firebase:test');
            return 1;
        }

        try {

            $existingUser = $firebaseService->getUserByEmail($email);
            if ($existingUser) {
                $this->error("❌ User with email {$email} already exists in Firebase");
                return 1;
            }


            $this->info("🔥 Creating Firebase user: {$email}");
            
            $additionalClaims = [
                'displayName' => $name,
                'role' => $isAdmin ? 'Administrador' : 'Empleado',
                'isAdmin' => $isAdmin,
            ];

            $firebaseUser = $firebaseService->createUser($email, $password, $additionalClaims);
            
            if (!$firebaseUser) {
                $this->error('❌ Failed to create Firebase user');
                return 1;
            }

            $this->info("✅ Firebase user created successfully");
            $this->info("   UID: {$firebaseUser->uid}");
            $this->info("   Email: {$firebaseUser->email}");


            $employee = Employee::where('email', $email)->first();
            
            if ($employee) {
                $this->info('📝 Updating existing employee record...');
                $employee->update([
                    'id_empleado' => $firebaseUser->uid,
                ]);
            } else {
                $this->info('👤 Creating new employee record...');
                $employee = Employee::create([
                    'id_empleado' => $firebaseUser->uid,
                    'nombre' => $name,
                    'email' => $email,
                    'rol_usuario' => $isAdmin ? 'Administrador' : 'Empleado',
                    'puntos_totales' => 0,
                    'puntos_canjeados' => 0,
                ]);
            }

            $this->info('✅ Employee record synced successfully');
            

            $this->info('');
            $this->info('📊 User Creation Summary:');
            $this->table(
                ['Field', 'Value'],
                [
                    ['Firebase UID', $firebaseUser->uid],
                    ['Email', $firebaseUser->email],
                    ['Display Name', $firebaseUser->displayName ?? 'Not set'],
                    ['Role', $isAdmin ? 'Administrador' : 'Empleado'],
                    ['Employee ID', $employee->id],
                    ['Points', $employee->puntos_totales],
                ]
            );

            $this->info('🎉 User created successfully!');
            $this->info('💡 The user can now log in at: ' . config('app.url') . '/login');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Failed to create user: ' . $e->getMessage());
            return 1;
        }
    }
}