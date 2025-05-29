<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venda;
use App\Models\Caixa;
use Livewire\WithPagination;
use App\Jobs\EmitirNotaJob; // coloque isso no topo do seu componente

class RelatorioVendas extends Component
{
     use WithPagination;

    public $data_inicial, $data_final, $metodo_pagamento, $caixa_id;
    public $vendaSelecionada = null; // Para armazenar a venda selecionada
    public $exibirImpressao = false; // Flag para controlar a exibição do relatório de impressão
    public $showModal        = false;   // ← flag do modal

    protected $listeners = [
        'filtrarRelatorio',
        'imprimir-relatorio' => 'imprimirRelatorio',
        'vendaFinalizada' => 'carregarVendas',
    ];
    
    public function mount()
    {
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
        $this->resetModal();   // ← toda vez que o componente é montado
        $this->carregarVendas();
    }

    public function render()
    {
        $query = Venda::query();
    
        if ($this->data_inicial) {
            $query->where('created_at', '>=', $this->data_inicial . ' 00:00:00');
        }
    
        if ($this->data_final) {
            $query->where('created_at', '<=', $this->data_final . ' 23:59:59');
        }
    
        if ($this->metodo_pagamento && $this->metodo_pagamento !== 'conta') {
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

    public function carregarVendas()
    {
        $this->vendas = Venda::with(['pagamentos', 'nota_fiscal'])->latest()->paginate(10);
    }

    public function detalhesVenda($id)
    {
        $this->vendaSelecionada = Venda::with(['itens.produto', 'pagamentos', 'caixa', 'cliente'])->find($id);
        $this->showModal = true;
        
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->vendaSelecionada = null;
    }
    protected function resetModal()
    {
        // se quiser, troque por $this->reset(['showModal', 'vendaSelecionada']);
        $this->showModal        = false;
        $this->vendaSelecionada = null;
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
        $this->dispatch('imprimir-pagina');

        // Após 1 segundo (tempo da impressão abrir), redireciona para a mesma página para resetar Livewire
        $this->js("setTimeout(() => window.location.href = '".route('relatorio-vendas')."', 1000)");
    }

   public function emitirNota($vendaId)
    {
        $venda = Venda::with('itens.produto')->find($vendaId);
        $resultado = app(\App\Services\NFeService::class)->emitirNota($venda);

        dd($resultado);
    }

    }