<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos_financiamiento', function (Blueprint $table) {
            $table->foreignId('tarjeta_cobro_id')
                ->nullable()
                ->after('referencia')
                ->constrained('tarjetas_cobro')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pagos_financiamiento', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\TarjetaCobro::class);
            $table->dropColumn('tarjeta_cobro_id');
        });
    }
};
