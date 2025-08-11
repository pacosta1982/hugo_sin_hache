<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock_inicial')->default(0)->after('stock');
            $table->integer('stock_minimo')->default(5)->after('stock_inicial');
            $table->boolean('notificar_stock_bajo')->default(true)->after('stock_minimo');
            $table->timestamp('ultima_alerta_stock')->nullable()->after('notificar_stock_bajo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_inicial', 'stock_minimo', 'notificar_stock_bajo', 'ultima_alerta_stock']);
        });
    }
};
