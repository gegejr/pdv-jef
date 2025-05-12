<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venda;
use App\Models\Caixa;

class RelatorioVendas extends Component
{
    public $data_inicial, $data_final, $metodo_pagamento, $caixa_id;
    public $vendaSelecionada = null; // Para armazenar a venda selecionada
    public $exibirImpressao = false; // Flag para controlar a exibição do relatório de impressão

    protected $listeners = [
        'filtrarRelatorio',
        'imprimir-relatorio' => 'imprimirRelatorio',
    ];

    public function render()
    {
        $query = Venda::query();
    
        if ($this->data_inicial) {
            $query->where('created_at', '>=', $this->data_inicial . ' 00:00:00');
        }
    
        if ($this->data_final) {
            $query->where('created_at', '<=', $this->data_final . ' 23:59:59');
        }
    
        if ($this->metodo_pagamento) {
            $query->whereHas('pagamentos', function ($q) {
                $q->where('tipo', $this->metodo_pagamento);
            });
        }
    
        if ($this->caixa_id) {
            $query->where('caixa_id', $this->caixa_id);
        }
    
        $vendas = $query->with(['caixa', 'pagamentos', 'user'])->paginate(10);
        $caixas = Caixa::all();
    
        return view('livewire.relatorio-vendas', compact('vendas', 'caixas'));
    }

    public function detalhesVenda($vendaId)
    {
        $this->vendaSelecionada = Venda::with('itens.produto', 'pagamentos', 'caixa')->find($vendaId);

    }

    public function filtrarRelatorio()
    {
        // Apenas um gatilho para Livewire atualizar o componente
    }

    public function mostrarImpressao()
    {
        $this->exibirImpressao = true;
    }

    public function esconderImpressao()
    {
        $this->exibirImpressao = false;
    }

    public function imprimirRelatorio()
    {
        $this->js('window.dispatchEvent(new CustomEvent("imprimir-pagina"))');
    }
}