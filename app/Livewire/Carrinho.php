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
    public $busca_produto = '';
    public $campo_visivel = false;
    public $mensagem_erro = null;
    public $mensagem_sucesso = null;
    public $sugestoes = [];
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

        // ✅ 1. Verifica se o carrinho está vazio
        if (empty($this->carrinho)) {
            session()->flash('error', 'O carrinho está vazio.');
            return;
        }

        // ✅ 2. Verifica se a forma de pagamento foi selecionada
        if (empty($this->metodo_pagamento)) {
            session()->flash('error', 'Selecione uma forma de pagamento.');
            return;
        }

        // ✅ 3. Verifica se todos os produtos têm estoque suficiente
        foreach ($this->carrinho as $item) {
            $produto = Produto::find($item['produto']->id);
            if (!$produto) {
                session()->flash('error', 'Produto não encontrado: ' . $item['produto']->nome);
                return;
            }

            if ($produto->estoque <= 0) {
                session()->flash('error', 'O produto "' . $produto->nome . '" está com estoque zerado.');
                return;
            }

            if ($produto->estoque < $item['quantidade']) {
                session()->flash('error', 'Estoque insuficiente para o produto "' . $produto->nome . '". Disponível: ' . $produto->estoque);
                return;
            }
        }

        // 🔄 Abertura do caixa
        $caixa = Caixa::where('user_id', $user_id)->whereNull('fechado_em')->first();
        if (!$caixa) {
            $caixa = $this->abrirCaixa(0, $user_id);
        }

        // 🧾 Criar venda
        $venda = Venda::create([
            'user_id' => $user_id,
            'caixa_id' => $caixa->id,
            'total' => $this->total,
            'desconto_total' => $this->desconto_total,
        ]);

        // 🧺 Criar itens e atualizar estoque
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

        // 💰 Registrar pagamento
        Pagamento::create([
            'venda_id' => $venda->id,
            'tipo' => $this->metodo_pagamento,
            'valor' => $this->total - $this->desconto_total,
        ]);

        // 🔄 Resetar carrinho e estados
        session()->forget('carrinho');
        $this->atualizarCarrinho();
        $this->desconto_total = 0;
        $this->metodo_pagamento = null;

        session()->flash('message', 'Venda finalizada com sucesso!');

        return redirect()->route('imprimir.cupom', ['venda_id' => $venda->id]);
    }



    public function toggleCampoBusca()
    {
        $this->campo_visivel = !$this->campo_visivel;
    }

    public function adicionarProduto()
    {
        $produto = Produto::where('id', $this->busca_produto)
            ->orWhere('codigo_barras', $this->busca_produto)
            ->orWhere('nome', 'like', '%' . $this->busca_produto . '%')
            ->first();

        if (!$produto) {
            session()->flash('error', 'Produto não encontrado.');
            return;
        }

        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$produto->id])) {
            $carrinho[$produto->id]['quantidade'] += 1;
        } else {
            $carrinho[$produto->id] = [
                'produto' => $produto,
                'quantidade' => 1,
                'valor_total' => $produto->valor,
                'desconto' => 0
            ];
        }

        session()->put('carrinho', $carrinho);
        $this->busca_produto = '';
        $this->atualizarCarrinho();
        session()->flash('message', 'Produto adicionado ao carrinho.');
    }


    public function updatedBuscaProduto()
    {
    $this->sugestoes = Produto::where('nome', 'like', '%' . $this->busca_produto . '%')
        ->orWhere('codigo_barras', 'like', '%' . $this->busca_produto . '%')
        ->limit(10)
        ->get()
        ->toArray();
    }    

    public function selecionarProduto($input)
    {
        // Agora, $input é o valor do texto digitado
        $produto = is_numeric($input)
            ? Produto::find($input)
            : Produto::where('codigo_barras', $input)
                ->orWhere('nome', 'like', '%' . $input . '%')
                ->first();

        if (!$produto) {
            session()->flash('error', 'Produto não encontrado.');
            return;
        }

        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$produto->id])) {
            $carrinho[$produto->id]['quantidade'] += 1;
        } else {
            $carrinho[$produto->id] = [
                'produto' => $produto,
                'quantidade' => 1,
                'valor_total' => $produto->valor,
                'desconto' => 0
            ];
        }

        session()->put('carrinho', $carrinho);

        // Limpar sugestões e campo de busca
        $this->busca_produto = '';
        $this->sugestoes = [];

        $this->atualizarCarrinho();
        session()->flash('message', 'Produto adicionado ao carrinho.');
    }


}
