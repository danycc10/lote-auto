<?php

namespace App\Mail;

use App\Models\PagoFinanciamiento;
use App\Models\ReciboFinanciamiento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PagoConfirmadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly PagoFinanciamiento $pago,
        public readonly ReciboFinanciamiento $recibo,
    ) {}

    public function envelope(): Envelope
    {
        $folio = $this->recibo->folio;

        return new Envelope(
            subject: "Confirmación de pago — Recibo {$folio}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pago-confirmado',
            with: [
                'pago' => $this->pago->load(['contrato.auto.marca', 'contrato.auto.modelo', 'cuota']),
                'recibo' => $this->recibo,
            ],
        );
    }
}
