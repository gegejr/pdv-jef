<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Produto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FecharVenda extends Component
{
    public $metodo_pagamento, $total, $desconto_total, $caixa_id;

    protected $listeners = ['atualizarCarrinho'];

    public function mount()
    {
        $this->total = array_sum(array_map(function ($item) {
            return $item['valor_total'];
        }, session()->get('carrinho', [])));
        
        $this->desconto_total = 0; // Defina o desconto, se houver.
        $this->caixa_id = 1; // Defina o caixa conforme necessário.
    }

    // Fechar a venda
    public function fecharVenda()
    {
        $carrinho = session()->get('carrinho', []);
        
        // Criando a venda
        $venda = Venda::create([
            'user_id' => Auth::id(),
            'total' => $this->total - $this->desconto_total,
            'desconto_total' => $this->desconto_total,
            'caixa_id' => $this->caixa_id,
        ]);

        // Adicionando os itens da venda
        foreach ($carrinho as $item) {
            $valor_total_item = $item['valor_total'] - $item['desconto'];

            ItemVenda::create([
                'venda_id' => $venda->id,
                'produto_id' => $item['produto']->id,
                'quantidade' => $item['quantidade'],
                'valor_unitario' => $item['produto']->valor,
                'desconto' => $item['desconto'],
            ]);

            // Atualizando o estoque do produto
            Produto::where('id', $item['produto']->id)
                ->decrement('estoque', $item['quantidade']);
        }

        // Limpando o carrinho da sessão
        session()->forget('carrinho');

        session()->flash('sucesso', 'Venda concluída com sucesso!');
        return redirect()->route('vendas.lista'); // redirecione para a página de vendas ou outra desejada
    }

    public function render()
    {
        return view('livewire.fechar-venda');
    }
}
