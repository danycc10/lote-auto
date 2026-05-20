<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recibos_financiamiento', function (Blueprint $table) {
            $table->id();

            $table->string('folio')->unique();

            $table->foreignId('contrato_financiamiento_id')->constrained('contratos_financiamiento')->cascadeOnDelete();
            $table->foreignId('pago_financiamiento_id')->nullable()->constrained('pagos_financiamiento')->nullOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();

            $table->date('fecha_recibo');
            $table->decimal('monto', 12, 2);

            $table->text('concepto')->nullable();
            $table->text('observaciones')->nullable();

            $table->enum('estatus', ['vigente', 'cancelado'])->default('vigente');
            $table->timestamp('cancelado_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recibos_financiamiento');
    }
};