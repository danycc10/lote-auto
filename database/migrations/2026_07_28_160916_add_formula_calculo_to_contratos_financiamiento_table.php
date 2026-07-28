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
            $table->string('formula_calculo', 30)
                ->default('plana_v1')
                ->after('tasa_interes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contratos_financiamiento', function (Blueprint $table) {
            $table->dropColumn('formula_calculo');
        });
    }
};
