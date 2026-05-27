<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $rows = [
            [
                'clave'       => 'notif.correo_asunto',
                'valor'       => 'Recordatorio de pago - Contrato {folio}',
                'descripcion' => 'Asunto del correo de recordatorio de pago. Variables: {nombre}, {folio}, {monto_atrasado}, {cuotas_vencidas}',
            ],
            [
                'clave'       => 'notif.correo_cuerpo',
                'valor'       => "Estimado/a {nombre},\n\nLe recordamos que tiene {cuotas_vencidas} cuota(s) vencida(s) por un total de \${monto_atrasado} en su contrato {folio}.\n\nLe pedimos ponerse al corriente a la brevedad posible. Para cualquier duda o acuerdo de pago, comuníquese con nosotros.\n\nGracias por su preferencia.",
                'descripcion' => 'Cuerpo del correo de recordatorio. Variables: {nombre}, {folio}, {monto_atrasado}, {cuotas_vencidas}',
            ],
            [
                'clave'       => 'notif.wa_mensaje',
                'valor'       => 'Hola {nombre}, le recordamos que tiene pagos vencidos por ${monto_atrasado} en su contrato {folio}. Por favor comuníquese con nosotros para ponerse al corriente. Gracias.',
                'descripcion' => 'Mensaje de WhatsApp de recordatorio. Variables: {nombre}, {folio}, {monto_atrasado}',
            ],
        ];

        foreach ($rows as $row) {
            DB::table('configuraciones')->insertOrIgnore(array_merge($row, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    public function down(): void
    {
        DB::table('configuraciones')->whereIn('clave', [
            'notif.correo_asunto',
            'notif.correo_cuerpo',
            'notif.wa_mensaje',
        ])->delete();
    }
};
