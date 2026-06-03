<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CotizacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly array $datos,
        private readonly string $pdfContent,
    ) {}

    public function envelope(): Envelope
    {
        $auto    = $this->datos['auto'];
        $empresa = $this->datos['empresa']['nombre'] ?? config('app.name');
        $nombre  = trim(($auto?->marca?->nombre ?? '') . ' ' . ($auto?->modelo?->nombre ?? '') . ' ' . ($auto?->anio ?? ''));

        return new Envelope(
            subject: "Cotización – {$nombre} | {$empresa}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cotizacion',
            with: $this->datos,
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->pdfContent,
                'cotizacion.pdf',
            )->withMime('application/pdf'),
        ];
    }
}
