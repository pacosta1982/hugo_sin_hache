<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->string('empleado_id');
            $table->unsignedBigInteger('producto_id');
            $table->timestamp('fecha_agregado')->useCurrent();
            $table->timestamps();


            $table->foreign('empleado_id')->references('id_empleado')->on('employees')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('products')->onDelete('cascade');


            $table->unique(['empleado_id', 'producto_id']);


            $table->index('empleado_id');
            $table->index('producto_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};