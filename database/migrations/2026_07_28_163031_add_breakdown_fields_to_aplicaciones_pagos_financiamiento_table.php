<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('aplicaciones_pagos_financiamiento', function (Blueprint $table) {
            $table->decimal('monto_extra', 12, 2)->default(0)->after('monto_interes');
            $table->decimal('recargo_generado', 12, 2)->default(0)->after('monto_extra');
            $table->unique(
                ['pago_financiamiento_id', 'cuota_financiamiento_id'],
                'aplicaciones_pago_cuota_unique',
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aplicaciones_pagos_financiamiento', function (Blueprint $table) {
            $table->dropUnique('aplicaciones_pago_cuota_unique');
            $table->dropColumn(['monto_extra', 'recargo_generado']);
        });
    }
};
