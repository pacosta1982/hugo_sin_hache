<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {

        $indexes = [

            "CREATE INDEX IF NOT EXISTS products_active_category_index ON products (activo, categoria)",
            "CREATE INDEX IF NOT EXISTS products_active_cost_index ON products (activo, costo_puntos)",
            "CREATE INDEX IF NOT EXISTS products_active_stock_index ON products (activo, stock)",
            "CREATE INDEX IF NOT EXISTS products_categoria_index ON products (categoria)",
            "CREATE INDEX IF NOT EXISTS products_costo_puntos_index ON products (costo_puntos)",
            

            "CREATE INDEX IF NOT EXISTS orders_empleado_id_index ON orders (empleado_id)",
            "CREATE INDEX IF NOT EXISTS orders_producto_id_index ON orders (producto_id)",
            "CREATE INDEX IF NOT EXISTS orders_estado_index ON orders (estado)",
            "CREATE INDEX IF NOT EXISTS orders_fecha_index ON orders (fecha)",
            "CREATE INDEX IF NOT EXISTS orders_employee_status_index ON orders (empleado_id, estado)",
            "CREATE INDEX IF NOT EXISTS orders_employee_date_index ON orders (empleado_id, fecha)",
            "CREATE INDEX IF NOT EXISTS orders_status_date_index ON orders (estado, fecha)",
            

            "CREATE INDEX IF NOT EXISTS employees_puntos_totales_index ON employees (puntos_totales)",
            "CREATE INDEX IF NOT EXISTS employees_role_points_index ON employees (rol_usuario, puntos_totales)",
            

            "CREATE INDEX IF NOT EXISTS favorites_empleado_id_index ON favorites (empleado_id)",
            "CREATE INDEX IF NOT EXISTS favorites_producto_id_index ON favorites (producto_id)",
            "CREATE UNIQUE INDEX IF NOT EXISTS favorites_employee_product_unique ON favorites (empleado_id, producto_id)",
            

            "CREATE INDEX IF NOT EXISTS sessions_user_id_index ON sessions (user_id)",
            "CREATE INDEX IF NOT EXISTS sessions_last_activity_index ON sessions (last_activity)",
        ];

        foreach ($indexes as $sql) {
            try {
                DB::statement($sql);
            } catch (\Exception $e) {

                \Log::info("Index creation skipped: " . $e->getMessage());
            }
        }
    }

    public function down(): void
    {

        $indexes = [
            'products_active_category_index',
            'products_active_cost_index', 
            'products_active_stock_index',
            'products_categoria_index',
            'products_costo_puntos_index',
            'orders_empleado_id_index',
            'orders_producto_id_index',
            'orders_estado_index',
            'orders_fecha_index',
            'orders_employee_status_index',
            'orders_employee_date_index',
            'orders_status_date_index',
            'employees_puntos_totales_index',
            'employees_role_points_index',
            'favorites_empleado_id_index',
            'favorites_producto_id_index',
            'favorites_employee_product_unique',
            'sessions_user_id_index',
            'sessions_last_activity_index',
        ];

        foreach ($indexes as $indexName) {
            try {
                DB::statement("DROP INDEX IF EXISTS {$indexName}");
            } catch (\Exception $e) {

                \Log::info("Index drop skipped: " . $e->getMessage());
            }
        }
    }
};
