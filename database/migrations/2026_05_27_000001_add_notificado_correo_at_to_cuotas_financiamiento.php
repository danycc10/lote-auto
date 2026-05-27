<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuotas_financiamiento', function (Blueprint $table) {
            $table->timestamp('notificado_correo_at')->nullable()->after('observaciones');
        });
    }

    public function down(): void
    {
        Schema::table('cuotas_financiamiento', function (Blueprint $table) {
            $table->dropColumn('notificado_correo_at');
        });
    }
};
