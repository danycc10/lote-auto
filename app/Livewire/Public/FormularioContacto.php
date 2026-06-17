<?php

namespace App\Livewire\Public;

use App\Mail\NuevoProspectoMail;
use App\Models\Configuracion;
use App\Models\Prospecto;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class FormularioContacto extends Component
{
    public string $nombre    = '';
    public string $telefono  = '';
    public string $correo    = '';
    public string $mensaje   = '';
    public bool   $enviado   = false;

    protected function rules(): array
    {
        return [
            'nombre'   => 'required|string|max:255',
            'telefono' => 'nullable|string|max:30',
            'correo'   => 'nullable|email|max:255',
            'mensaje'  => 'nullable|string|max:1000',
        ];
    }

    protected function messages(): array
    {
        return [
            'nombre.required' => 'Por favor escribe tu nombre.',
            'correo.email'    => 'El correo no parece válido.',
        ];
    }

    public function enviar(): void
    {
        $this->validate();

        $prospecto = Prospecto::create([
            'nombre'       => trim($this->nombre),
            'telefono'     => $this->telefono ?: null,
            'correo'       => $this->correo ?: null,
            'observaciones'=> $this->mensaje ?: null,
            'origen'       => 'web',
            'estatus'      => 'nuevo',
        ]);

        $this->notificarAdmin($prospecto);

        $this->enviado = true;
    }

    private function notificarAdmin(Prospecto $prospecto): void
    {
        try {
            $correo = Configuracion::obtener('correo_notificacion')
                   ?: config('mail.from.address');

            if ($correo) {
                Mail::to($correo)->queue(new NuevoProspectoMail($prospecto));
            }
        } catch (\Throwable $e) {
            // No bloquea el envío del formulario si falla el correo, pero sí queda registrado.
            report($e);
        }
    }

    public function render()
    {
        return view('livewire.public.formulario-contacto');
    }
}
