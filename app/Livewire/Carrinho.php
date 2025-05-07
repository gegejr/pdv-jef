<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produto;

class Carrinho extends Component
{
    public $carrinho = [];
    public $total = 0;
    public $desconto_total = 0; // <- adiciona esta linha

    public function mount()
    {
        $this->atualizarCarrinho();
    }

    public function atualizarCarrinho()
    {
        $this->carrinho = session()->get('carrinho', []);
        $this->total = array_sum(array_column($this->carrinho, 'valor_total'));
    }

    public function atualizarQuantidade($produtoId, $novaQuantidade)
    {
        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$produtoId])) {
            $novaQuantidade = max(1, (int) $novaQuantidade); // evita quantidade zero ou negativa
            $produto = $carrinho[$produtoId]['produto'];
            $valorUnitario = $produto->valor;
            $desconto = $carrinho[$produtoId]['desconto'] ?? 0;

            $carrinho[$produtoId]['quantidade'] = $novaQuantidade;
            $carrinho[$produtoId]['valor_total'] = ($valorUnitario * $novaQuantidade) - $desconto;

            session()->put('carrinho', $carrinho);
        }

        $this->atualizarCarrinho();
    }

    public function removerItem($produtoId)
    {
        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$produtoId])) {
            unset($carrinho[$produtoId]);
            session()->put('carrinho', $carrinho);
        }

        $this->atualizarCarrinho();
    }

    public function render()
    {
        // Passando o valor do total do carrinho para a view
        return view('livewire.carrinho', [
            'totalCarrinho' => $this->total
        ]);
    }

    public function fecharVenda()
    {
        // Aqui você pode salvar os dados no banco, limpar o carrinho, etc.

        // Exemplo básico:
        session()->forget('carrinho'); // limpa o carrinho
        $this->atualizarCarrinho(); // atualiza o estado do componente
        session()->flash('message', 'Venda finalizada com sucesso!');
    }

        
}
