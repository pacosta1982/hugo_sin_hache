<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('id_empleado')->primary(); // Firebase UID
            $table->string('nombre');
            $table->string('email')->nullable()->index();
            $table->integer('puntos_totales')->default(0);
            $table->integer('puntos_canjeados')->default(0);
            $table->string('rol_usuario')->default('Empleado')->index();
            $table->timestamps(); // Laravel's created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};