<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarjetas_cobro', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('banco', 100)->nullable();
            $table->enum('tipo', ['tarjeta', 'transferencia', 'deposito']);
            $table->string('numero', 100)->nullable()->comment('Últimos dígitos, CLABE parcial, etc.');
            $table->string('titular', 150)->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarjetas_cobro');
    }
};
