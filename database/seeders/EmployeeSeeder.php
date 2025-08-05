<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use PDO;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $sqliteDbPath = base_path('../database/ugo.db');
        
        if (!file_exists($sqliteDbPath)) {
            $this->command->warn('SQLite database not found at: ' . $sqliteDbPath);
            return;
        }

        try {
            $pdo = new PDO('sqlite:' . $sqliteDbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->query('SELECT * FROM employees');
            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($employees as $employeeData) {
                Employee::create([
                    'id_empleado' => $employeeData['id_empleado'],
                    'nombre' => $employeeData['nombre'],
                    'email' => $employeeData['email'],
                    'puntos_totales' => $employeeData['puntos_totales'],
                    'puntos_canjeados' => $employeeData['puntos_canjeados'],
                    'rol_usuario' => $employeeData['rol_usuario'],
                    'created_at' => $employeeData['fecha_creacion'] ?? now(),
                    'updated_at' => $employeeData['fecha_actualizacion'] ?? now(),
                ]);
            }

            $this->command->info('Imported ' . count($employees) . ' employees from SQLite database.');

        } catch (\Exception $e) {
            $this->command->error('Error importing employees: ' . $e->getMessage());
        }
    }
}