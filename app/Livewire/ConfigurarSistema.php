<?php

namespace App\Livewire;

use Livewire\Component;

class ConfigurarSistema extends Component
{
        public function mount()
    {
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
    }
    
    public $mostrarConfiguracoes = false;

    public function render()
    {
        return view('livewire.configurar-sistema');
    }
}