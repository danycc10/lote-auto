<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuotas_financiamiento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contrato_financiamiento_id')->constrained('contratos_financiamiento')->cascadeOnDelete();

            $table->unsignedInteger('numero');
            $table->date('fecha_vencimiento');

            $table->decimal('monto_capital', 12, 2)->default(0);
            $table->decimal('monto_interes', 12, 2)->default(0);
            $table->decimal('monto_extra', 12, 2)->default(0);
            $table->decimal('monto', 12, 2)->default(0);

            $table->decimal('monto_pagado', 12, 2)->default(0);
            $table->decimal('recargo_aplicado', 12, 2)->default(0);
            $table->decimal('saldo', 12, 2)->default(0);

            $table->enum('estatus', [
                'pendiente',
                'parcial',
                'pagada',
                'vencida',
                'cancelada',
            ])->default('pendiente');

            $table->timestamp('fecha_pago')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->unique(['contrato_financiamiento_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas_financiamiento');
    }
};