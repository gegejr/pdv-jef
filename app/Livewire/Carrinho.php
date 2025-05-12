<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produto;
use App\Models\Caixa;
use App\Models\Venda;
use App\Models\Pagamento;

class Carrinho extends Component
{
    public $carrinho = [];
    public $total = 0;
    public $desconto_total = 0;
    public $metodo_pagamento;

    public $mensagem_erro = null;
    public $mensagem_sucesso = null;

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
            $novaQuantidade = max(1, (int) $novaQuantidade);
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
        return view('livewire.carrinho', [
            'totalCarrinho' => $this->total,
            'carrinho' => $this->carrinho,
            'mensagem_erro' => $this->mensagem_erro,
            'mensagem_sucesso' => $this->mensagem_sucesso,
            'total' => $this->total,
            'desconto_total' => $this->desconto_total,
        ]);
    }

    protected function abrirCaixa($valorInicial = 0, $userId)
    {
        return Caixa::create([
            'user_id' => $userId,
            'valor_inicial' => $valorInicial,
            'aberto_em' => now(),
        ]);
    }

    public function fecharVenda()
    {
        $user_id = auth()->id();

        // Verificação de estoque
        foreach ($this->carrinho as $item) {
            $produto = Produto::find($item['produto']->id);
            if (!$produto) {
                session()->flash('error', 'Produto não encontrado: ' . $item['produto']->nome);
                return;
            }

            if ($produto->estoque < $item['quantidade']) {
                session()->flash('error', 'Estoque insuficiente para o produto "' . $produto->nome . '". Disponível: ' . $produto->estoque);
                return;
            }
        }

        $caixa = Caixa::where('user_id', $user_id)->whereNull('fechado_em')->first();

        if (!$caixa) {
            $caixa = $this->abrirCaixa(0, $user_id);
        }

        $venda = Venda::create([
            'user_id' => $user_id,
            'caixa_id' => $caixa->id,
            'total' => $this->total, // <- CORRETO
            'desconto_total' => $this->desconto_total,
        ]);

        foreach ($this->carrinho as $item) {
            $produto = Produto::find($item['produto']->id);
            $produto->estoque -= $item['quantidade'];
            $produto->save();

            $venda->itemVendas()->create([
                'produto_id' => $item['produto']->id,
                'quantidade' => $item['quantidade'],
                'valor_unitario' => $item['produto']->valor,
                'desconto' => $item['desconto'] ?? 0,
            ]);
        }

        // Aqui você pode salvar o pagamento com $this->metodo_pagamento se desejar
        Pagamento::create([
            'venda_id' => $venda->id,
            'tipo' => $this->metodo_pagamento,
            'valor' => $this->total - $this->desconto_total,
        ]);

        session()->forget('carrinho');
        $this->atualizarCarrinho();
        $this->desconto_total = 0;
        $this->metodo_pagamento = null;

        session()->flash('message', 'Venda finalizada com sucesso!');
    }
}
