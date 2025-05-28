<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venda;

class DetalhesVenda extends Component
{
    public int $vendaSelecionada;   // ← apenas o ID

    public function mount()
    {
        // (só a checagem de assinatura)
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
    }

    public function render()
    {
        // Carrega o modelo a cada render;
        // não precisa (e nem deve) ficar guardado em propriedade pública
        $venda = Venda::with([
            'itens.produto',
            'pagamentos',
            'caixa',
            'cliente',
        ])->find($this->vendaSelecionada);

        return view('livewire.detalhes-venda', [
            'venda' => $venda,
        ]);
    }
}

