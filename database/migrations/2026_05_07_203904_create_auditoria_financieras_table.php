<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria_financieras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('accion');
            $table->string('modelo')->nullable();
            $table->unsignedBigInteger('modelo_id')->nullable();

            $table->json('antes')->nullable();
            $table->json('despues')->nullable();

            $table->text('observaciones')->nullable();

            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index(['accion', 'modelo']);
            $table->index(['modelo', 'modelo_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_financieras');
    }
};