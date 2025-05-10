<?php

namespace App\Livewire;

use Livewire\Component;

class ConfigurarSistema extends Component
{
    public $mostrarConfiguracoes = false;

    public function render()
    {
        return view('livewire.configurar-sistema');
    }
}