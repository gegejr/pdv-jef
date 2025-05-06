<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venda;
use App\Models\ItemVenda;

class RelatorioVendas extends Component
{
    public $data_inicial, $data_final, $metodo_pagamento, $caixa_id;
    public $vendaSelecionada = null; // Para armazenar a venda selecionada

    protected $listeners = ['filtrarRelatorio'];

    public function render()
    {
        $query = Venda::query();

        if ($this->data_inicial) {
            $query->where('created_at', '>=', $this->data_inicial);
        }

        if ($this->data_final) {
            $query->where('created_at', '<=', $this->data_final);
        }

        if ($this->metodo_pagamento) {
            $query->where('metodo_pagamento', $this->metodo_pagamento);
        }

        if ($this->caixa_id) {
            $query->where('caixa_id', $this->caixa_id);
        }

        $vendas = $query->with('usuario', 'caixa')->get(); // Relacionamento de vendas com usuário e caixa

        return view('livewire.relatorio-vendas', compact('vendas'));
    }

    public function filtrarRelatorio()
    {
        $this->render();
    }

    // Método para selecionar a venda e exibir os detalhes
    public function detalhesVenda($vendaId)
    {
        $this->vendaSelecionada = Venda::with('itens.produto')
                                        ->where('id', $vendaId)
                                        ->first();
    }
}
