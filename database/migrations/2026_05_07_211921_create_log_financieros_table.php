<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs_financieros', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('tipo');
            $table->string('modulo')->nullable();
            $table->string('referencia')->nullable();

            $table->nullableMorphs('loggable');

            $table->decimal('monto', 14, 2)->nullable();
            $table->decimal('saldo_anterior', 14, 2)->nullable();
            $table->decimal('saldo_nuevo', 14, 2)->nullable();

            $table->string('moneda', 10)->default('MXN');

            $table->string('titulo');
            $table->text('descripcion')->nullable();

            $table->json('metadata')->nullable();

            $table->string('nivel')->default('info');

            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index('tipo');
            $table->index('modulo');
            $table->index('referencia');
            $table->index('nivel');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs_financieros');
    }
};