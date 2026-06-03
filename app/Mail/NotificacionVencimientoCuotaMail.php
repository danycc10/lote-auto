<?php

namespace App\Mail;

use App\Models\CuotaFinanciamiento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionVencimientoCuotaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly CuotaFinanciamiento $cuota,
        public readonly string $tipo,  // 'recordatorio' | 'vencimiento_hoy'
    ) {}

    public function envelope(): Envelope
    {
        $folio  = $this->cuota->contrato?->folio ?? '';
        $numero = $this->cuota->numero;

        $subject = match ($this->tipo) {
            'vencimiento_hoy' => "Pago de hoy — Cuota #{$numero} contrato {$folio}",
            default           => "Recordatorio de pago — Cuota #{$numero} vence en 3 días",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notificacion-vencimiento-cuota',
            with: [
                'cuota'  => $this->cuota->load([
                    'contrato.auto.marca',
                    'contrato.auto.modelo',
                    'contrato.cliente',
                ]),
                'tipo'   => $this->tipo,
            ],
        );
    }
}
