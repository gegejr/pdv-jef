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
    
    public function mount()
    {
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
        if (!$this->metodo_pagamento) {
            session()->flash('error', 'Por favor, selecione um método de pagamento.');
            return;
        }

        $venda = Venda::findOrFail($this->vendaId);

        // Verifica se o pagamento do tipo "conta" já foi pago
        $pagamentoConta = $venda->pagamentos->firstWhere('tipo', 'conta');

        if (!$pagamentoConta || $pagamentoConta->pago) {
            session()->flash('error', 'Esta conta já foi paga ou não possui pagamento pendente.');
            $this->fecharModal();
            return;
        }

        // Marca o pagamento "conta" como pago
        $pagamentoConta->update(['pago' => true]);

        // Cria o novo pagamento com o método selecionado
        Pagamento::create([
            'venda_id' => $venda->id,
            'tipo' => $this->metodo_pagamento,
            'valor' => $this->valor,
            'pago' => 1,
        ]);

        $this->carregarContas(); // Atualiza as listas
        $this->fecharModal();

        session()->flash('message', 'Pagamento confirmado com sucesso!');
    }


    public function render()
    {
        return view('livewire.cliente-conta');
    }
}