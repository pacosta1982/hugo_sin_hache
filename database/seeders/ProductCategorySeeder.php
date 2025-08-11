<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tecnología',
                'description' => 'Dispositivos electrónicos, gadgets y accesorios tecnológicos',
                'slug' => 'tecnologia',
                'icon' => 'laptop',
                'color' => '#3B82F6',
                'sort_order' => 1,
            ],
            [
                'name' => 'Hogar y Decoración',
                'description' => 'Artículos para el hogar, decoración y utensilios',
                'slug' => 'hogar-decoracion',
                'icon' => 'home',
                'color' => '#10B981',
                'sort_order' => 2,
            ],
            [
                'name' => 'Deportes y Fitness',
                'description' => 'Equipamiento deportivo, ropa de ejercicio y accesorios fitness',
                'slug' => 'deportes-fitness',
                'icon' => 'dumbbell',
                'color' => '#F59E0B',
                'sort_order' => 3,
            ],
            [
                'name' => 'Libros y Educación',
                'description' => 'Libros, cursos, material educativo y de formación',
                'slug' => 'libros-educacion',
                'icon' => 'book',
                'color' => '#8B5CF6',
                'sort_order' => 4,
            ],
            [
                'name' => 'Alimentación y Bebidas',
                'description' => 'Productos gastronómicos, bebidas especiales y vouchers restaurantes',
                'slug' => 'alimentacion-bebidas',
                'icon' => 'utensils',
                'color' => '#EF4444',
                'sort_order' => 5,
            ],
            [
                'name' => 'Salud y Bienestar',
                'description' => 'Productos para el cuidado personal, salud y bienestar',
                'slug' => 'salud-bienestar',
                'icon' => 'heart',
                'color' => '#EC4899',
                'sort_order' => 6,
            ],
            [
                'name' => 'Entretenimiento',
                'description' => 'Juegos, música, películas y experiencias de entretenimiento',
                'slug' => 'entretenimiento',
                'icon' => 'gamepad',
                'color' => '#06B6D4',
                'sort_order' => 7,
            ],
            [
                'name' => 'Viajes y Experiencias',
                'description' => 'Vouchers de viaje, experiencias únicas y actividades',
                'slug' => 'viajes-experiencias',
                'icon' => 'plane',
                'color' => '#84CC16',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $categoryData) {
            ProductCategory::create($categoryData);
        }

        // Add some subcategories
        $techCategory = ProductCategory::where('slug', 'tecnologia')->first();
        if ($techCategory) {
            ProductCategory::create([
                'name' => 'Smartphones',
                'description' => 'Teléfonos inteligentes y accesorios móviles',
                'slug' => 'smartphones',
                'icon' => 'mobile-alt',
                'color' => '#3B82F6',
                'parent_id' => $techCategory->id,
                'sort_order' => 1,
            ]);
            
            ProductCategory::create([
                'name' => 'Computadoras',
                'description' => 'Laptops, PCs y periféricos',
                'slug' => 'computadoras',
                'icon' => 'desktop',
                'color' => '#3B82F6',
                'parent_id' => $techCategory->id,
                'sort_order' => 2,
            ]);
        }

        $homeCategory = ProductCategory::where('slug', 'hogar-decoracion')->first();
        if ($homeCategory) {
            ProductCategory::create([
                'name' => 'Cocina',
                'description' => 'Utensilios y electrodomésticos de cocina',
                'slug' => 'cocina',
                'icon' => 'blender',
                'color' => '#10B981',
                'parent_id' => $homeCategory->id,
                'sort_order' => 1,
            ]);
        }
    }
}