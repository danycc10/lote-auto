<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aplicaciones_pagos_financiamiento', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pago_financiamiento_id');
            $table->unsignedBigInteger('cuota_financiamiento_id');

            $table->decimal('monto', 12, 2);
            $table->decimal('monto_recargo', 12, 2)->default(0);
            $table->decimal('monto_capital', 12, 2)->default(0);
            $table->decimal('monto_interes', 12, 2)->default(0);

            $table->timestamps();

            $table->foreign('pago_financiamiento_id', 'fk_apli_pago_fin')
                ->references('id')
                ->on('pagos_financiamiento')
                ->onDelete('cascade');

            $table->foreign('cuota_financiamiento_id', 'fk_apli_cuota_fin')
                ->references('id')
                ->on('cuotas_financiamiento')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aplicaciones_pagos_financiamiento');
    }
};