<?php

namespace App\Mail;

use App\Models\Prospecto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevoProspectoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Prospecto $prospecto,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo prospecto web — ' . $this->prospecto->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nuevo-prospecto',
            with: [
                'prospecto' => $this->prospecto->load('auto.marca', 'auto.modelo'),
            ],
        );
    }
}
