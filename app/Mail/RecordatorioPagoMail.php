<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecordatorioPagoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $asunto,
        public readonly string $cuerpo,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->asunto);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.recordatorio-pago');
    }
}
