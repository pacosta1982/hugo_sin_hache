<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use PDO;

class OrderSeeder extends Seeder
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

            $stmt = $pdo->query('SELECT * FROM orders');
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($orders as $orderData) {
                Order::create([
                    'empleado_id' => $orderData['empleado_id'],
                    'producto_id' => $orderData['producto_id'],
                    'fecha' => $orderData['fecha'],
                    'estado' => $orderData['estado'],
                    'puntos_utilizados' => $orderData['puntos_utilizados'],
                    'producto_nombre' => $orderData['producto_nombre'],
                    'empleado_nombre' => $orderData['empleado_nombre'],
                    'observaciones' => $orderData['observaciones'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info('Imported ' . count($orders) . ' orders from SQLite database.');

        } catch (\Exception $e) {
            $this->command->error('Error importing orders: ' . $e->getMessage());
        }
    }
}