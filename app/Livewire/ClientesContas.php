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

    public function mount()
    {
        $this->carregarContas();
    }

    public function carregarContas()
    {
        $this->vendas = Venda::whereHas('pagamentos', function ($q) {
            $q->where('tipo', 'conta')->where('pago', false);
        })->with(['cliente', 'pagamentos'])->get();
    }

    public function openModal($vendaId)
    {
        // Busca a venda para preencher os detalhes do modal
        $venda = Venda::findOrFail($vendaId);
        $this->vendaId = $vendaId;
        $this->clienteNome = $venda->cliente->nome;
        $this->valor = $venda->pagamentos->firstWhere('tipo', 'conta')->valor;

        // Abre o modal
        $this->modalOpen = true;
    }

    public function fecharModal()
    {
        // Fecha o modal
        $this->modalOpen = false;
        $this->metodo_pagamento = null;  // Reseta o método de pagamento ao fechar o modal
    }

    public function confirmarPagamento()
    {
        // Valida se o método de pagamento foi selecionado
        if (!$this->metodo_pagamento) {
            session()->flash('error', 'Por favor, selecione um método de pagamento.');
            return;
        }

        // Busca a venda
        $venda = Venda::findOrFail($this->vendaId);

        // Atualiza os pagamentos para "pago"
        foreach ($venda->pagamentos as $pagamento) {
            if ($pagamento->tipo === 'conta') {
                $pagamento->update(['pago' => true]);
            }
        }

        // Cria um novo registro de pagamento com o método selecionado
        Pagamento::create([
            'venda_id' => $venda->id,
            'tipo' => $this->metodo_pagamento,
            'valor' => $this->valor,
        ]);

        // Atualiza a lista de vendas
        $this->carregarContas();

        // Fecha o modal
        $this->fecharModal();

        // Exibe uma mensagem de sucesso
        session()->flash('message', 'Pagamento confirmado com sucesso!');
    }

    public function render()
    {
        return view('livewire.cliente-conta');
    }
}
