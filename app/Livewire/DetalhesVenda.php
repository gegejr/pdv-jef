<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venda;

class DetalhesVenda extends Component
{
    public ?Venda $vendaSelecionada = null;

    public function render()
    {
        return view('livewire.detalhes-venda');
    }
}