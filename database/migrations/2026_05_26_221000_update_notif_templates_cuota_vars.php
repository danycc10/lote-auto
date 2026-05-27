<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')
            ->where('clave', 'notif.correo_asunto')
            ->update(['valor' => 'Recordatorio de pago — Cuota #{numero_cuota} / Contrato {folio}']);

        DB::table('configuraciones')
            ->where('clave', 'notif.correo_cuerpo')
            ->update(['valor' => "Estimado/a {nombre},\n\nLe recordamos que la cuota #{numero_cuota} de su contrato {folio} está vencida.\n\n  Fecha de vencimiento: {fecha_vencimiento}\n  Días de atraso:        {dias_atraso} días\n  Monto pendiente:       \${monto_pendiente}\n\nLe pedimos ponerse al corriente a la brevedad. Para cualquier acuerdo de pago, comuníquese con nosotros.\n\nGracias por su preferencia."]);

        DB::table('configuraciones')
            ->where('clave', 'notif.wa_mensaje')
            ->update(['valor' => 'Hola {nombre}, le recordamos que la cuota #{numero_cuota} de su contrato {folio} venció el {fecha_vencimiento} ({dias_atraso} días de atraso). Monto pendiente: ${monto_pendiente}. Por favor comuníquese con nosotros. Gracias.']);

        DB::table('configuraciones')
            ->whereIn('clave', ['notif.correo_asunto', 'notif.correo_cuerpo', 'notif.wa_mensaje'])
            ->update(['descripcion' => 'Variables: {nombre}, {folio}, {numero_cuota}, {fecha_vencimiento}, {dias_atraso}, {monto_pendiente}, {monto_cuota}']);
    }

    public function down(): void {}
};
