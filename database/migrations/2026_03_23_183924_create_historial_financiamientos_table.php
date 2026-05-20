<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historiales_financiamiento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contrato_financiamiento_id')->constrained('contratos_financiamiento')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('evento');
            $table->json('antes')->nullable();
            $table->json('despues')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historiales_financiamiento');
    }
};