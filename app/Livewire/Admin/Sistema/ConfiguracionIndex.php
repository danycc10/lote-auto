<?php

namespace App\Livewire\Admin\Sistema;

use App\Models\Configuracion;
use Livewire\Component;

class ConfiguracionIndex extends Component
{
    public bool $financiamientoActivo = true;

    public string $whatsapp   = '';
    public string $mapsEmbed  = '';
    public string $instagram  = '';
    public string $facebook   = '';

    public string $notifCorreoAsunto = '';
    public string $notifCorreoCuerpo = '';
    public string $notifWaMensaje    = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('seguridad.roles'), 403);

        $this->financiamientoActivo = Configuracion::esActivo('modulo.financiamiento');
        $this->whatsapp             = Configuracion::obtener('contact.whatsapp', '');
        $this->mapsEmbed            = Configuracion::obtener('contact.maps_embed', '');
        $this->instagram            = Configuracion::obtener('contact.instagram', '');
        $this->facebook             = Configuracion::obtener('contact.facebook', '');

        $this->notifCorreoAsunto = Configuracion::obtener('notif.correo_asunto', '');
        $this->notifCorreoCuerpo = Configuracion::obtener('notif.correo_cuerpo', '');
        $this->notifWaMensaje    = Configuracion::obtener('notif.wa_mensaje', '');
    }

    public function toggleFinanciamiento(): void
    {
        $this->financiamientoActivo = ! $this->financiamientoActivo;

        Configuracion::establecer('modulo.financiamiento', $this->financiamientoActivo ? '1' : '0');

        $this->dispatch('toast',
            type: 'success',
            message: $this->financiamientoActivo
                ? 'Módulo de financiamiento activado.'
                : 'Módulo de financiamiento desactivado.'
        );
    }

    public function guardarContacto(): void
    {
        $this->validate([
            'whatsapp'  => ['required', 'string', 'max:20', 'regex:/^\d+$/'],
            'mapsEmbed' => ['nullable', 'string', 'max:2000', 'url'],
            'instagram' => ['nullable', 'string', 'max:255', 'url'],
            'facebook'  => ['nullable', 'string', 'max:255', 'url'],
        ], [
            'whatsapp.required' => 'El número de WhatsApp es obligatorio.',
            'whatsapp.regex'    => 'Solo se permiten dígitos (sin +, espacios ni guiones).',
            'mapsEmbed.url'     => 'Debe ser una URL válida.',
            'instagram.url'     => 'Debe ser una URL válida (ej: https://instagram.com/...).',
            'facebook.url'      => 'Debe ser una URL válida (ej: https://facebook.com/...).',
        ]);

        Configuracion::establecer('contact.whatsapp', $this->whatsapp);
        Configuracion::establecer('contact.maps_embed', $this->mapsEmbed);
        Configuracion::establecer('contact.instagram', $this->instagram);
        Configuracion::establecer('contact.facebook', $this->facebook);

        $this->dispatch('toast', type: 'success', message: 'Configuración de contacto guardada.');
    }

    public function guardarPlantillasNotif(): void
    {
        $this->validate([
            'notifCorreoAsunto' => ['required', 'string', 'max:200'],
            'notifCorreoCuerpo' => ['required', 'string', 'max:2000'],
            'notifWaMensaje'    => ['required', 'string', 'max:500'],
        ], [
            'notifCorreoAsunto.required' => 'El asunto es obligatorio.',
            'notifCorreoCuerpo.required' => 'El cuerpo del correo es obligatorio.',
            'notifWaMensaje.required'    => 'El mensaje de WhatsApp es obligatorio.',
        ]);

        Configuracion::establecer('notif.correo_asunto', $this->notifCorreoAsunto);
        Configuracion::establecer('notif.correo_cuerpo', $this->notifCorreoCuerpo);
        Configuracion::establecer('notif.wa_mensaje',    $this->notifWaMensaje);

        $this->dispatch('toast', type: 'success', message: 'Plantillas de notificación guardadas.');
    }

    public function render()
    {
        return view('livewire.admin.sistema.configuracion-index')
            ->layout('layouts.app')
            ->title('Configuración del sistema');
    }
}
