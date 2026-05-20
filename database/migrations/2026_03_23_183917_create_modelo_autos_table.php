<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modelos_autos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_auto_id')->constrained('marcas_autos')->cascadeOnDelete();
            $table->string('nombre');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['marca_auto_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modelos_autos');
    }
};