<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('marca_auto_id')->constrained('marcas_autos')->restrictOnDelete();
            $table->foreignId('modelo_auto_id')->constrained('modelos_autos')->restrictOnDelete();

            $table->string('codigo_inventario')->nullable()->unique();
            $table->string('vin')->nullable()->unique();
            $table->string('placa')->nullable()->unique();

            $table->year('anio');
            $table->string('version')->nullable();
            $table->string('color')->nullable();
            $table->unsignedInteger('kilometraje')->default(0);
            $table->string('transmision')->nullable();
            $table->string('tipo_combustible')->nullable();

            $table->decimal('precio_contado', 12, 2)->default(0);
            $table->decimal('precio_financiado', 12, 2)->default(0);

            $table->enum('estatus', [
                'disponible',
                'apartado',
                'vendido_contado',
                'financiado',
                'liquidado',
                'recuperado',
                'inactivo',
            ])->default('disponible');

            $table->text('descripcion')->nullable();
            $table->boolean('destacado')->default(false);
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autos');
    }
};