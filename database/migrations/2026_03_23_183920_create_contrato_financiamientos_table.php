<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos_financiamiento', function (Blueprint $table) {
            $table->id();

            $table->string('folio')->unique();

            $table->foreignId('auto_id')->constrained('autos')->restrictOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('vendedor_id')->nullable()->constrained('users')->nullOnDelete();

            $table->date('fecha_contrato');
            $table->date('fecha_primer_pago')->nullable();

            $table->decimal('precio_contado', 12, 2)->default(0);
            $table->decimal('precio_venta', 12, 2)->default(0);
            $table->decimal('enganche', 12, 2)->default(0);
            $table->decimal('comision_apertura', 12, 2)->default(0);
            $table->decimal('monto_seguro', 12, 2)->default(0);
            $table->decimal('monto_gps', 12, 2)->default(0);

            $table->decimal('monto_financiado', 12, 2)->default(0);
            $table->decimal('tasa_interes', 8, 4)->default(0);

            $table->unsignedInteger('plazo');
            $table->enum('frecuencia', ['semanal', 'quincenal', 'mensual'])->default('semanal');
            $table->decimal('monto_cuota', 12, 2)->default(0);

            $table->decimal('total_pagar', 12, 2)->default(0);
            $table->decimal('total_pagado', 12, 2)->default(0);
            $table->decimal('saldo_actual', 12, 2)->default(0);

            $table->unsignedInteger('dias_gracia')->default(0);
            $table->enum('tipo_recargo', ['fijo', 'porcentaje'])->nullable();
            $table->decimal('valor_recargo', 12, 2)->default(0);

            $table->enum('estatus', [
                'borrador',
                'activo',
                'atrasado',
                'liquidado',
                'cancelado',
                'reestructurado',
                'recuperado',
            ])->default('borrador');

            $table->text('observaciones')->nullable();
            $table->string('ruta_contrato_firmado')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos_financiamiento');
    }
};