<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produto;
use App\Models\Caixa;
use App\Models\Venda;
use App\Models\Pagamento;  // Certifique-se de incluir o modelo Pagamento

class Carrinho extends Component
{
    public $carrinho = [];
    public $total = 0;
    public $desconto_total = 0;
    public $metodo_pagamento;  // <-- Adiciona essa linha para a propriedade do método de pagamento

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
            'totalCarrinho' => $this->total
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

    $caixa = Caixa::where('user_id', $user_id)->whereNull('fechado_em')->first();

    if (!$caixa) {
        $caixa = $this->abrirCaixa(0, $user_id);
    }

    // Criar a venda
    $venda = Venda::create([
        'user_id' => $user_id,
        'caixa_id' => $caixa->id,
        'total' => $this->total,
        'desconto_total' => $this->desconto_total,
    ]);

    // Registrar os itens da venda
    foreach ($this->carrinho as $item) {
        $venda->itemVendas()->create([
            'produto_id' => $item['produto']->id,
            'quantidade' => $item['quantidade'],
            'valor_unitario' => $item['produto']->valor,
            'desconto' => $item['desconto'] ?? 0,
        ]);
    }

    // Registrar o pagamento
    $metodoPagamento = $this->metodo_pagamento;  // Método de pagamento selecionado
    $valorPagamento = $this->total;  // Valor do pagamento

    // Verificar se o tipo de pagamento está presente
    if (empty($metodoPagamento)) {
        session()->flash('error', 'O tipo de pagamento é obrigatório.');
        return;
    }

    // Aqui, você pode adicionar um log para garantir que o tipo de pagamento está correto
    \Log::info('Método de pagamento selecionado: ' . $metodoPagamento);

    // Criar o pagamento
    $pagamento = Pagamento::create([
        'venda_id' => $venda->id,
        'tipo' => $metodoPagamento,
        'valor' => $valorPagamento,
    ]);

    // Atualizar o caixa
    $caixa->valor_inicial += $this->total;
    $caixa->save();

    // Limpar o carrinho da sessão
    session()->forget('carrinho');
    $this->carrinho = []; // Atualiza a variável carrinho no componente

    // Atualizar o total após limpar o carrinho
    $this->total = 0;
    $this->desconto_total = 0;

    // Garantir que a tela seja atualizada
    $this->atualizarCarrinho();

    session()->flash('message', 'Venda finalizada com sucesso!');
}
}
