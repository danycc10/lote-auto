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
        Schema::table('contratos_financiamiento', function (Blueprint $table) {
            $table->unique('apartado_auto_id', 'contratos_apartado_unique');
            $table->index(['estatus', 'id'], 'contratos_estatus_id_index');
            $table->index(['cliente_id', 'estatus'], 'contratos_cliente_estatus_index');
            $table->index(['auto_id', 'estatus'], 'contratos_auto_estatus_index');
        });

        Schema::table('cuotas_financiamiento', function (Blueprint $table) {
            $table->index(['estatus', 'fecha_vencimiento'], 'cuotas_estatus_vencimiento_index');
            $table->index(
                ['contrato_financiamiento_id', 'estatus', 'fecha_vencimiento'],
                'cuotas_contrato_estatus_vencimiento_index',
            );
        });

        Schema::table('pagos_financiamiento', function (Blueprint $table) {
            $table->index(['estatus', 'fecha_pago'], 'pagos_estatus_fecha_index');
            $table->index(
                ['contrato_financiamiento_id', 'estatus', 'fecha_pago'],
                'pagos_contrato_estatus_fecha_index',
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos_financiamiento', function (Blueprint $table) {
            $table->dropIndex('pagos_contrato_estatus_fecha_index');
            $table->dropIndex('pagos_estatus_fecha_index');
        });

        Schema::table('cuotas_financiamiento', function (Blueprint $table) {
            $table->dropIndex('cuotas_contrato_estatus_vencimiento_index');
            $table->dropIndex('cuotas_estatus_vencimiento_index');
        });

        Schema::table('contratos_financiamiento', function (Blueprint $table) {
            $table->dropIndex('contratos_auto_estatus_index');
            $table->dropIndex('contratos_cliente_estatus_index');
            $table->dropIndex('contratos_estatus_id_index');
            $table->dropUnique('contratos_apartado_unique');
        });
    }
};
