<?php

namespace App\Livewire;

use Livewire\Component;

class Produto extends Component
{
        public function mount()
    {
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
    }
    
    public function render()
    {
        return view('livewire.produto');
    }
}
