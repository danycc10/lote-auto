<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recibos_financiamiento', function (Blueprint $table) {
            $table->foreignId('cuota_id')
                ->nullable()
                ->after('contrato_financiamiento_id')
                ->constrained('cuotas_financiamiento')
                ->nullOnDelete();

            $table->decimal('saldo_anterior', 12, 2)->default(0)->after('monto');
            $table->decimal('saldo_posterior', 12, 2)->default(0)->after('saldo_anterior');
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'sqlite') {
            DB::statement("UPDATE recibos_financiamiento r
                LEFT JOIN pagos_financiamiento p ON p.id = r.pago_financiamiento_id
                LEFT JOIN aplicaciones_pagos_financiamiento ap ON ap.pago_financiamiento_id = p.id
                SET r.cuota_id = COALESCE(r.cuota_id, p.cuota_id, ap.cuota_financiamiento_id)
                WHERE r.cuota_id IS NULL");
        }
    }

    public function down(): void
    {
        Schema::table('recibos_financiamiento', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cuota_id');
            $table->dropColumn(['saldo_anterior', 'saldo_posterior']);
        });
    }
};
