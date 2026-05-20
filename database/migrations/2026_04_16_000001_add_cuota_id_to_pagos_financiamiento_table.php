<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos_financiamiento', function (Blueprint $table) {
            $table->foreignId('cuota_id')
                ->nullable()
                ->after('contrato_financiamiento_id')
                ->constrained('cuotas_financiamiento')
                ->nullOnDelete();
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'sqlite') {
            DB::statement("UPDATE pagos_financiamiento p
                LEFT JOIN aplicaciones_pagos_financiamiento ap ON ap.pago_financiamiento_id = p.id
                SET p.cuota_id = ap.cuota_financiamiento_id
                WHERE p.cuota_id IS NULL");
        }
    }

    public function down(): void
    {
        Schema::table('pagos_financiamiento', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cuota_id');
        });
    }
};
