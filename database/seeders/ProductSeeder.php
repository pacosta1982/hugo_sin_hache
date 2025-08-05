<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use PDO;

class ProductSeeder extends Seeder
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

            $stmt = $pdo->query('SELECT * FROM products');
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($products as $productData) {
                Product::create([
                    'nombre' => $productData['nombre'],
                    'descripcion' => $productData['descripcion'],
                    'categoria' => $productData['categoria'],
                    'costo_puntos' => $productData['costo_puntos'],
                    'stock' => $productData['stock'],
                    'activo' => (bool) $productData['activo'],
                    'integra_jira' => (bool) $productData['integra_jira'],
                    'envia_email' => (bool) $productData['envia_email'],
                    'terminos_condiciones' => $productData['terminos_condiciones'] ?? null,
                    'created_at' => $productData['fecha_creacion'] ?? now(),
                    'updated_at' => $productData['fecha_actualizacion'] ?? now(),
                ]);
            }

            $this->command->info('Imported ' . count($products) . ' products from SQLite database.');

        } catch (\Exception $e) {
            $this->command->error('Error importing products: ' . $e->getMessage());
        }
    }
}