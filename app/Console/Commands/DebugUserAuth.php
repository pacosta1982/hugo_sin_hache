<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;

class DebugUserAuth extends Command
{
    protected $signature = 'auth:debug';

    protected $description = 'Debug user authentication and show current employees';

    public function handle()
    {
        $this->info('🔍 User Authentication Debug Report');
        $this->line('');


        $employees = Employee::all();
        
        $this->info("📊 Current Employees in Database: {$employees->count()}");
        $this->line('');

        if ($employees->count() > 0) {
            $headers = ['Firebase UID', 'Name', 'Email', 'Points', 'Role', 'Created'];
            $rows = [];

            foreach ($employees as $employee) {
                $rows[] = [
                    $employee->id_empleado,
                    $employee->nombre,
                    $employee->email ?: 'No email',
                    number_format($employee->puntos_totales),
                    $employee->rol_usuario,
                    $employee->created_at->format('Y-m-d H:i'),
                ];
            }

            $this->table($headers, $rows);
        } else {
            $this->warn('No employees found in database.');
        }

        $this->line('');
        $this->info('🔥 Firebase Authentication Status:');
        

        try {
            $auth = app(\Kreait\Firebase\Auth::class);
            $this->info('✅ Firebase Auth properly initialized');
            

            $this->line('');
            $this->info('👥 Firebase Users (first 10):');
            
            $listUsersResult = $auth->listUsers($maxResults = 10);
            $firebaseUsers = [];
            
            foreach ($listUsersResult as $userRecord) {
                $firebaseUsers[] = [
                    'uid' => $userRecord->uid,
                    'email' => $userRecord->email ?: 'No email',
                    'display_name' => $userRecord->displayName ?: 'No name',
                    'created' => $userRecord->metadata->createdAt ? $userRecord->metadata->createdAt->format('Y-m-d H:i') : 'Unknown',
                ];
            }
            
            if (!empty($firebaseUsers)) {
                $this->table(['UID', 'Email', 'Display Name', 'Created'], $firebaseUsers);
            } else {
                $this->warn('No Firebase users found.');
            }


            $this->line('');
            $this->info('🔍 Checking Firebase ↔ Database Mapping:');
            
            $employeeUids = $employees->pluck('id_empleado')->toArray();
            $firebaseUids = collect($firebaseUsers)->pluck('uid')->toArray();
            
            $missingInDb = array_diff($firebaseUids, $employeeUids);
            $missingInFirebase = array_diff($employeeUids, $firebaseUids);
            
            if (!empty($missingInDb)) {
                $this->warn('⚠️  Firebase users not in database: ' . implode(', ', $missingInDb));
                $this->line('   These users will be auto-registered on next login.');
            }
            
            if (!empty($missingInFirebase)) {
                $this->error('❌ Database employees not in Firebase: ' . implode(', ', $missingInFirebase));
                $this->line('   These employees cannot login until Firebase accounts are created.');
            }
            
            if (empty($missingInDb) && empty($missingInFirebase)) {
                $this->info('✅ All users properly mapped between Firebase and database.');
            }

        } catch (\Exception $e) {
            $this->error('❌ Firebase Auth initialization failed: ' . $e->getMessage());
            $this->line('   Check firebase-admin-credentials.json and Firebase configuration.');
        }

        $this->line('');
        $this->info('💡 Recent Authentication Logs:');
        $this->line('Check storage/logs/laravel.log for detailed authentication logs.');

        return 0;
    }
}
