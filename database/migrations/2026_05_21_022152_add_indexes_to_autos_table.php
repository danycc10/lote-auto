<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('autos', function (Blueprint $table) {
            $table->index(['estatus', 'activo'], 'idx_autos_estatus_activo');
        });
    }

    public function down(): void
    {
        Schema::table('autos', function (Blueprint $table) {
            $table->dropIndex('idx_autos_estatus_activo');
        });
    }
};
