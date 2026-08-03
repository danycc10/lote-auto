<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estado_operaciones', function (Blueprint $table): void {
            $table->id();
            $table->string('clave', 80)->unique();
            $table->string('estado', 20);
            $table->text('mensaje')->nullable();
            $table->timestamp('ejecutado_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estado_operaciones');
    }
};
