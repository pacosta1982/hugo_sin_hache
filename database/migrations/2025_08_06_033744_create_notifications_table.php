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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('empleado_id')->nullable(); // null for admin notifications
            $table->enum('type', ['info', 'success', 'warning', 'error', 'low_stock', 'order_update', 'points_awarded']);
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data like product_id, order_id, etc.
            $table->boolean('read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('created_by')->nullable(); // admin who created it
            $table->timestamps();
            
            $table->foreign('empleado_id')->references('id_empleado')->on('employees')->onDelete('cascade');
            $table->index(['empleado_id', 'read', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
