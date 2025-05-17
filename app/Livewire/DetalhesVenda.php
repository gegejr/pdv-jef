<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venda;

class DetalhesVenda extends Component
{
        public function mount()
    {
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
    }
    public ?Venda $vendaSelecionada = null;

    public function render()
    {
        return view('livewire.detalhes-venda');
    }
}