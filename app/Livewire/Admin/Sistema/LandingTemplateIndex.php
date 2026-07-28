<?php

namespace App\Livewire\Admin\Sistema;

use App\Models\Configuracion;
use Livewire\Component;

class LandingTemplateIndex extends Component
{
    public string $templateActual = 'oscuro';

    public array $templates = [
        'oscuro' => [
            'nombre' => 'Oscuro',
            'descripcion' => 'Dark fintech premium. Fondo negro profundo con acentos azul y esmeralda.',
            'estilo' => 'bg-[#06091a] border-blue-500/30',
            'badge' => 'bg-blue-500/20 text-blue-300',
            'preview' => ['#06091a', '#3b82f6', '#10b981'],
        ],
        'moderno' => [
            'nombre' => 'Moderno',
            'descripcion' => 'Clean y minimalista. Fondo blanco con colores primarios configurables.',
            'estilo' => 'bg-white border-indigo-300',
            'badge' => 'bg-indigo-100 text-indigo-700',
            'preview' => ['#ffffff', '#6366f1', '#f1f5f9'],
        ],
        'bold' => [
            'nombre' => 'Bold',
            'descripcion' => 'Tipografía masiva y alto contraste. Negro con acentos ámbar/naranja.',
            'estilo' => 'bg-[#0a0a0a] border-amber-500/40',
            'badge' => 'bg-amber-400/20 text-amber-300',
            'preview' => ['#0a0a0a', '#f59e0b', '#1c1c1c'],
        ],
        'elegante' => [
            'nombre' => 'Elegante',
            'descripcion' => 'Lujo premium. Navy profundo con toques dorados y tipografía refinada.',
            'estilo' => 'bg-[#070c1a] border-yellow-600/30',
            'badge' => 'bg-yellow-500/20 text-yellow-300',
            'preview' => ['#070c1a', '#c9a95c', '#0d1635'],
        ],
        'vibrante' => [
            'nombre' => 'Vibrante',
            'descripcion' => 'Gradiente púrpura-esmeralda con glassmorphism. Energético y moderno.',
            'estilo' => 'bg-gradient-to-br from-[#0f0c29] to-[#0b3d2e] border-purple-500/30',
            'badge' => 'bg-purple-500/20 text-purple-300',
            'preview' => ['#0f0c29', '#7c3aed', '#059669'],
        ],
    ];

    public function mount(): void
    {
        $this->authorizeConfiguration();

        $this->templateActual = Configuracion::obtener('landing.template', 'oscuro');
    }

    public function seleccionar(string $template): void
    {
        $this->authorizeConfiguration();

        if (! array_key_exists($template, $this->templates)) {
            return;
        }

        Configuracion::establecer('landing.template', $template);
        $this->templateActual = $template;

        $this->dispatch('notify', type: 'success', message: 'Plantilla "'.$this->templates[$template]['nombre'].'" activada.');
    }

    public function render()
    {
        return view('livewire.admin.sistema.landing-template-index')
            ->layout('layouts.app', ['title' => 'Plantillas de Landing']);
    }

    private function authorizeConfiguration(): void
    {
        abort_unless(auth()->user()?->can('sistema.configurar'), 403);
    }
}
