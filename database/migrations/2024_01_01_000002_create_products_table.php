<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('categoria')->nullable()->index();
            $table->integer('costo_puntos')->index();
            $table->integer('stock')->default(-1); // -1 means unlimited stock
            $table->boolean('activo')->default(true)->index();
            $table->boolean('integra_jira')->default(false);
            $table->boolean('envia_email')->default(false);
            $table->text('terminos_condiciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};