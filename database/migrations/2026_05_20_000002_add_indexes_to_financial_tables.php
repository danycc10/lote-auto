<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos_financiamiento', function (Blueprint $table) {
            $table->index(['contrato_financiamiento_id', 'estatus'], 'idx_pagos_contrato_estatus');
            $table->index('fecha_pago', 'idx_pagos_fecha_pago');
        });

        Schema::table('cuotas_financiamiento', function (Blueprint $table) {
            $table->index(['contrato_financiamiento_id', 'estatus'], 'idx_cuotas_contrato_estatus');
            $table->index('fecha_vencimiento', 'idx_cuotas_fecha_vencimiento');
        });

        Schema::table('recibos_financiamiento', function (Blueprint $table) {
            $table->index(['contrato_financiamiento_id', 'estatus'], 'idx_recibos_contrato_estatus');
            $table->index('fecha_recibo', 'idx_recibos_fecha_recibo');
        });
    }

    public function down(): void
    {
        Schema::table('pagos_financiamiento', function (Blueprint $table) {
            $table->dropIndex('idx_pagos_contrato_estatus');
            $table->dropIndex('idx_pagos_fecha_pago');
        });

        Schema::table('cuotas_financiamiento', function (Blueprint $table) {
            $table->dropIndex('idx_cuotas_contrato_estatus');
            $table->dropIndex('idx_cuotas_fecha_vencimiento');
        });

        Schema::table('recibos_financiamiento', function (Blueprint $table) {
            $table->dropIndex('idx_recibos_contrato_estatus');
            $table->dropIndex('idx_recibos_fecha_recibo');
        });
    }
};
