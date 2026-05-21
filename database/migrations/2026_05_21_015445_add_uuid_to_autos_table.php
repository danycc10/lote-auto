<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('autos', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        // Llenar registros existentes con UUID único
        DB::table('autos')->orderBy('id')->each(function (object $auto) {
            DB::table('autos')
                ->where('id', $auto->id)
                ->update(['uuid' => (string) Str::uuid()]);
        });

        // Hacer NOT NULL después de poblar
        Schema::table('autos', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('autos', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
