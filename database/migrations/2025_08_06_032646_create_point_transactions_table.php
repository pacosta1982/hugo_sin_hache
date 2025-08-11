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
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('empleado_id');
            $table->enum('type', ['earned', 'spent', 'adjustment']);
            $table->integer('points');
            $table->string('description');
            $table->string('admin_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->foreign('empleado_id')->references('id_empleado')->on('employees');
            $table->foreign('admin_id')->references('id_empleado')->on('employees');
            $table->index(['empleado_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
