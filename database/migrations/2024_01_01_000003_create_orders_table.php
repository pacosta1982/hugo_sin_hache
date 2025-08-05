<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('empleado_id');
            $table->unsignedBigInteger('producto_id');
            $table->timestamp('fecha')->useCurrent();
            $table->string('estado')->default('Pendiente')->index(); // Pendiente, En curso, Realizado, Cancelado
            $table->integer('puntos_utilizados');
            $table->string('producto_nombre')->nullable(); // Denormalized for easier queries
            $table->string('empleado_nombre')->nullable(); // Denormalized for easier queries
            $table->text('observaciones')->nullable();
            $table->timestamps();


            $table->foreign('empleado_id')->references('id_empleado')->on('employees')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('products')->onDelete('cascade');


            $table->index('empleado_id');
            $table->index('producto_id');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};