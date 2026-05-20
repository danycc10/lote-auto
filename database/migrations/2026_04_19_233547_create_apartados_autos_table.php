<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apartados_autos', function (Blueprint $table) {
            $table->id();

            $table->string('folio')->unique();

            $table->foreignId('auto_id')
                ->constrained('autos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('cliente_id')
                ->nullable()
                ->constrained('clientes')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->date('fecha_apartado');
            $table->date('fecha_vencimiento');

            $table->decimal('monto_anticipo', 12, 2)->default(0);
            $table->decimal('saldo_pendiente', 12, 2)->default(0);

            $table->string('forma_pago', 50)->nullable();
            $table->string('referencia', 255)->nullable();

            $table->string('nombre_cliente_temporal')->nullable();
            $table->string('telefono_cliente_temporal', 30)->nullable();
            $table->string('correo_cliente_temporal')->nullable();

            $table->text('observaciones')->nullable();

            $table->enum('estatus', [
                'activo',
                'convertido',
                'vencido',
                'cancelado',
            ])->default('activo');

            $table->timestamp('cancelado_at')->nullable();
            $table->text('motivo_cancelacion')->nullable();

            $table->timestamps();

            $table->index(['estatus', 'fecha_vencimiento']);
            $table->index(['auto_id', 'estatus']);
            $table->index(['cliente_id', 'estatus']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apartados_autos');
    }
};