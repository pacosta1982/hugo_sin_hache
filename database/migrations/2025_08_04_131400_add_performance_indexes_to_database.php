<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('orders', function (Blueprint $table) {
            $table->index('empleado_id', 'idx_orders_empleado_id');
            $table->index('producto_id', 'idx_orders_producto_id');
            $table->index('estado', 'idx_orders_estado');
            $table->index('fecha', 'idx_orders_fecha');
            $table->index(['empleado_id', 'estado'], 'idx_orders_empleado_estado');
            $table->index(['estado', 'fecha'], 'idx_orders_estado_fecha');
        });


        Schema::table('products', function (Blueprint $table) {
            $table->index('activo', 'idx_products_activo');
            $table->index('categoria', 'idx_products_categoria');
            $table->index(['activo', 'categoria'], 'idx_products_activo_categoria');
            $table->index(['activo', 'stock'], 'idx_products_activo_stock');
        });


        Schema::table('favorites', function (Blueprint $table) {
            $table->index('empleado_id', 'idx_favorites_empleado_id');
            $table->index('producto_id', 'idx_favorites_producto_id');
            $table->unique(['empleado_id', 'producto_id'], 'idx_favorites_unique');
        });


        Schema::table('employees', function (Blueprint $table) {
            $table->index('email', 'idx_employees_email');
            $table->index('rol_usuario', 'idx_employees_rol');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_empleado_id');
            $table->dropIndex('idx_orders_producto_id');
            $table->dropIndex('idx_orders_estado');
            $table->dropIndex('idx_orders_fecha');
            $table->dropIndex('idx_orders_empleado_estado');
            $table->dropIndex('idx_orders_estado_fecha');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_activo');
            $table->dropIndex('idx_products_categoria');
            $table->dropIndex('idx_products_activo_categoria');
            $table->dropIndex('idx_products_activo_stock');
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->dropIndex('idx_favorites_empleado_id');
            $table->dropIndex('idx_favorites_producto_id');
            $table->dropIndex('idx_favorites_unique');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('idx_employees_email');
            $table->dropIndex('idx_employees_rol');
        });
    }
};
