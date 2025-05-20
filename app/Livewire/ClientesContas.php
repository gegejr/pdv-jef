<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venda;
use App\Models\Pagamento;

class ClientesContas extends Component
{
    public $vendas;
    public $modalOpen = false;
    public $vendaId;
    public $clienteNome;
    public $valor;
    public $metodo_pagamento;
    public $venda;
    public $contasPendentes;
    public $contasPagas;
    public $valor_pagamento  = null;   // valor digitado agora
    public $pagamentos_adicionados = [];   // [ ['tipo'=>'dinheiro','valor'=>10] ... ]
    public function mount()
    {
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
        $this->carregarContas();
    }

    public function carregarContas()
    {
        // Contas pendentes
        $this->contasPendentes = Venda::whereHas('pagamentos', function ($q) {
            $q->where('tipo', 'conta')->where('pago', false);
        })->with(['cliente', 'pagamentos'])->get();

        // Contas pagas
        $this->contasPagas = Venda::whereHas('pagamentos', function ($q) {
            $q->where('tipo', 'conta')->where('pago', true);
        })->with(['cliente', 'pagamentos'])->get();
    }

    public function openModal($vendaId)
    {
        $this->vendaId = $vendaId;  // <-- Adicione esta linha
        $this->vendaSelecionada = Venda::find($vendaId);

        if ($this->vendaSelecionada) {
            $cliente = $this->vendaSelecionada->cliente;
            $pagamentoConta = $this->vendaSelecionada->pagamentos->firstWhere('tipo', 'conta');

            $this->clienteNome = $cliente->nome;
            $this->valor = $pagamentoConta->valor ?? 0;
            $this->modalOpen = true;
        }
    }

    public function fecharModal()
    {
        // Fecha o modal
        $this->modalOpen = false;
        $this->metodo_pagamento = null;  // Reseta o método de pagamento ao fechar o modal
    }

    public function confirmarPagamento()
    {
        if (empty($this->pagamentos_adicionados)) {
            session()->flash('error', 'Adicione pelo menos um pagamento.');
            return;
        }

        // Soma total dos pagamentos
        $totalPagamentos = collect($this->pagamentos_adicionados)->sum('valor');

        // Regra de negócio: deve ser exatamente igual ao valor da venda
        if ($totalPagamentos < $this->valor) {
            session()->flash('error', 'Valor pago inferior ao total da venda.');
            return;
        }
        if ($totalPagamentos > $this->valor) {
            session()->flash('error', 'Valor pago excede o total da venda.');
            return;
        }

        $venda           = Venda::findOrFail($this->vendaId);
        $pagamentoConta  = $venda->pagamentos->firstWhere('tipo', 'conta');

        if (!$pagamentoConta || $pagamentoConta->pago) {
            session()->flash('error', 'Esta conta já foi paga ou não possui pagamento pendente.');
            $this->fecharModal();
            return;
        }

        // Marca a “conta” como quitada
        $pagamentoConta->update(['pago' => true]);

        // Registra cada pagamento parcial
        foreach ($this->pagamentos_adicionados as $p) {
            Pagamento::create([
                'venda_id' => $venda->id,
                'tipo'     => $p['tipo'],   // agora sempre “dinheiro/debito/...” – nunca “#”
                'valor'    => $p['valor'],
                'pago'     => 1,
            ]);
        }

        // Limpeza & feedback
        $this->pagamentos_adicionados = [];
        $this->carregarContas();
        $this->fecharModal();
        session()->flash('message', 'Pagamento confirmado com sucesso!');
    }
    public function adicionarPagamento()
    {
        // ⚠️ Validação básica
        if (!$this->metodo_pagamento) {
            session()->flash('error', 'Escolha um método de pagamento.');
            return;
        }

        if (!$this->valor_pagamento || $this->valor_pagamento <= 0) {
            session()->flash('error', 'Informe um valor válido.');
            return;
        }

        // ⚠️ Soma atual + novo valor não pode exceder o total da venda
        $totalAtual = collect($this->pagamentos_adicionados)->sum('valor');
        if (($totalAtual + $this->valor_pagamento) > $this->valor) {
            session()->flash('error', 'A soma dos pagamentos ultrapassa o valor da venda.');
            return;
        }

        $this->pagamentos_adicionados[] = [
            'tipo'  => $this->metodo_pagamento,
            'valor' => $this->valor_pagamento,
        ];

        // Limpa os campos
        $this->metodo_pagamento = '';
        $this->valor_pagamento  = null;
    }

    
    public function render()
    {
        return view('livewire.cliente-conta');
    }
}