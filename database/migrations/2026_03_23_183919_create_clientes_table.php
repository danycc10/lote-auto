<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();

            $table->string('telefono')->nullable()->index();
            $table->string('correo')->nullable()->index();

            $table->string('curp')->nullable()->unique();
            $table->string('rfc')->nullable();

            $table->text('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('estado')->nullable();
            $table->string('codigo_postal')->nullable();

            $table->string('ocupacion')->nullable();
            $table->decimal('ingreso_mensual', 12, 2)->nullable();

            $table->string('ruta_ine')->nullable();
            $table->string('ruta_comprobante_domicilio')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};