<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospectos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('auto_id')->nullable()->constrained('autos')->nullOnDelete();
            $table->foreignId('usuario_asignado_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('nombre');
            $table->string('telefono')->nullable()->index();
            $table->string('correo')->nullable()->index();

            $table->enum('estatus', [
                'nuevo',
                'contactado',
                'interesado',
                'negociacion',
                'ganado',
                'perdido',
            ])->default('nuevo');

            $table->string('origen')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamp('ultimo_contacto_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospectos');
    }
};