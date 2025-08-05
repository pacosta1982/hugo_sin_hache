<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favorite;
use PDO;

class FavoriteSeeder extends Seeder
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

            $stmt = $pdo->query('SELECT * FROM favorites');
            $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($favorites as $favoriteData) {
                Favorite::create([
                    'empleado_id' => $favoriteData['empleado_id'],
                    'producto_id' => $favoriteData['producto_id'],
                    'fecha_agregado' => $favoriteData['fecha_agregado'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info('Imported ' . count($favorites) . ' favorites from SQLite database.');

        } catch (\Exception $e) {
            $this->command->error('Error importing favorites: ' . $e->getMessage());
        }
    }
}