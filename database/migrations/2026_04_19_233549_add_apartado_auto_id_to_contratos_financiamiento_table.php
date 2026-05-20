<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contratos_financiamiento', function (Blueprint $table) {
            $table->foreignId('apartado_auto_id')
                ->nullable()
                ->after('cliente_id')
                ->constrained('apartados_autos')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('contratos_financiamiento', function (Blueprint $table) {
            $table->dropConstrainedForeignId('apartado_auto_id');
        });
    }
};