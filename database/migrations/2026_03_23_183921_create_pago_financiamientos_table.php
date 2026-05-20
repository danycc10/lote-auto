<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos_financiamiento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contrato_financiamiento_id')->constrained('contratos_financiamiento')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('capturado_por')->nullable()->constrained('users')->nullOnDelete();

            $table->date('fecha_pago');
            $table->decimal('monto', 12, 2);
            $table->decimal('monto_aplicado', 12, 2)->default(0);
            $table->decimal('monto_restante', 12, 2)->default(0);

            $table->enum('forma_pago', [
                'efectivo',
                'transferencia',
                'tarjeta',
                'deposito',
                'otro',
            ])->default('efectivo');

            $table->string('referencia')->nullable();
            $table->text('observaciones')->nullable();

            $table->string('evidencia_path')->nullable();
            $table->string('firma_path')->nullable();

            $table->enum('estatus', [
                'aplicado',
                'cancelado',
            ])->default('aplicado');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_financiamiento');
    }
};