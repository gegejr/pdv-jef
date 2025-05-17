<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produto;
use App\Models\Caixa;
use App\Models\Venda;
use App\Models\Pagamento;
use App\Models\Cliente;

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
    public $cliente_id = null;
    public $cliente_nome = null;
    public $busca_cliente = '';
    public $sugestoes_clientes = [];
    public $caixaAberto = false;
    public $nome = '';
    public $valor_inicial = 0;
    public $pagamentos = [
        ['tipo' => '', 'valor' => 0]
    ];

    protected $listeners = ['clienteSelecionado'];

    public function mount()
    {
        $user = auth()->user()->fresh();

        if (!$user->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
        $this->atualizarCarrinho();
        $this->verificarCaixa();

        // Inicializa array de pagamentos
        $this->pagamentos = [['tipo' => '', 'valor' => 0]];
    }

    public function verificarCaixa()
    {
        $this->caixaAberto = Caixa::where('user_id', auth()->id())
            ->whereNull('fechado_em')
            ->exists();
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

    public function abrirCaixa()
    {
        $userId = auth()->id();

        Caixa::create([
            'user_id' => $userId,
            'nome' => $this->nome,
            'valor_inicial' => $this->valor_inicial ?? 0,
            'aberto_em' => now(),
        ]);

        $this->verificarCaixa();
        $this->reset(['nome', 'valor_inicial']);
    }

    public function fecharVenda()
    {
        $user_id = auth()->id();

        /* 1. Carrinho vazio? */
        if (empty($this->carrinho)) {
            session()->flash('error', 'O carrinho está vazio.');
            return;
        }

        /* 2. Validação dos pagamentos */
        if (!$this->validarPagamentos()) {
            return; // mensagens já disparadas pelo método
        }

        /* 3. Estoque suficiente? */
        foreach ($this->carrinho as $item) {
            $produto = Produto::find($item['produto']->id);

            if (!$produto || $produto->estoque < $item['quantidade']) {
                session()->flash('error',
                    'Estoque insuficiente para "' . $item['produto']->nome . '".');
                return;
            }
        }

        /* 4. Garante caixa aberto */
        $caixa = Caixa::firstOrCreate(
            ['user_id' => $user_id, 'fechado_em' => null],
            ['nome' => 'Caixa', 'valor_inicial' => 0, 'aberto_em' => now()]
        );

        /* 5. Monta descrição resumida dos métodos */
        $this->metodo_pagamento = implode(' + ', array_column($this->pagamentos, 'tipo'));

        /* 6. Cria venda */
        $venda = Venda::create([
            'user_id'         => $user_id,
            'caixa_id'        => $caixa->id,
            'cliente_id'      => $this->cliente_id,
            'total'           => $this->total,
            'desconto_total'  => $this->desconto_total,
            'metodo_pagamento'=> $this->metodo_pagamento,
        ]);

        /* 7. Itens & estoque */
        foreach ($this->carrinho as $item) {
            $produto = Produto::find($item['produto']->id);
            $produto->decrement('estoque', $item['quantidade']);

            $venda->itemVendas()->create([
                'produto_id'    => $produto->id,
                'total'         => $item['valor_total'],
                'quantidade'    => $item['quantidade'],
                'valor_unitario'=> $produto->valor,
                'desconto'      => $item['desconto'] ?? 0,
            ]);
        }

        /* 8. Pagamentos */
        $totalRecebido = 0;
        foreach ($this->pagamentos as $p) {
            Pagamento::create([
                'venda_id' => $venda->id,
                'tipo'     => $p['tipo'],
                'valor'    => $p['valor'],
            ]);
            $totalRecebido += $p['valor'];
        }

        $valorEsperado = $this->total - $this->desconto_total;
        if (abs($totalRecebido - $valorEsperado) > 0.01) {
            session()->flash('error',
                'Pagamentos somam R$ ' . number_format($totalRecebido,2,',','.') .
                ' mas o total é R$ ' . number_format($valorEsperado,2,',','.'));
            return;
        }

        /* 9. Limpa estado */
        session()->forget('carrinho');
        $this->reset([
            'carrinho', 'total', 'desconto_total',
            'cliente_id', 'cliente_nome',
            'pagamentos'
        ]);
        $this->pagamentos = [['tipo' => '', 'valor' => 0]];

        session()->flash('message', 'Venda finalizada com sucesso!');
        return redirect()->route('imprimir.cupom', ['venda_id' => $venda->id]);
    }

     public function adicionarPagamento()
    {
        $this->pagamentos[] = ['tipo' => '', 'valor' => 0];
    }

    public function removerPagamento($index)
    {
        unset($this->pagamentos[$index]);
        $this->pagamentos = array_values($this->pagamentos);
    }

    private function validarPagamentos(): bool
    {
        if (empty($this->pagamentos)) {
            session()->flash('error', 'Adicione pelo menos um método de pagamento.');
            return false;
        }

        foreach ($this->pagamentos as $p) {
            if ($p['tipo'] === '' || $p['valor'] <= 0) {
                session()->flash('error', 'Preencha tipo e valor de todos os pagamentos.');
                return false;
            }
        }

        $soma = array_sum(array_column($this->pagamentos, 'valor'));
        $totalDevido = $this->total - $this->desconto_total;

        if (bccomp($soma, $totalDevido, 2) !== 0) {
            session()->flash('error',
                'Pagamentos somam R$ ' . number_format($soma,2,',','.') .
                ' mas o total é R$ ' . number_format($totalDevido,2,',','.'));
            return false;
        }
        return true;
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
            $carrinho[$produto->id]['valor_total'] = $produto->valor * $carrinho[$produto->id]['quantidade'];
        } else {
            $carrinho[$produto->id] = [
                'produto' => $produto,
                'quantidade' => 1,
                'valor_total' => $produto->valor,
                'desconto' => 0,
            ];
        }

        session()->put('carrinho', $carrinho);

        $this->busca_produto = '';
        $this->sugestoes = [];
        $this->atualizarCarrinho();

        session()->flash('message', 'Produto adicionado ao carrinho.');
    }

    public function updatedBuscaProduto()
    {
        if ($this->busca_produto === '') {
            $this->sugestoes = [];
            return;
        }

        $this->sugestoes = Produto::where('nome', 'like', '%' . $this->busca_produto . '%')
            ->orWhere('codigo_barras', 'like', '%' . $this->busca_produto . '%')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function selecionarProduto($input)
    {
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
            $carrinho[$produto->id]['valor_total'] = $produto->valor * $carrinho[$produto->id]['quantidade'];
        } else {
            $carrinho[$produto->id] = [
                'produto' => $produto,
                'quantidade' => 1,
                'valor_total' => $produto->valor,
                'desconto' => 0,
            ];
        }

        session()->put('carrinho', $carrinho);

        $this->busca_produto = '';
        $this->sugestoes = [];
        $this->atualizarCarrinho();

        session()->flash('message', 'Produto adicionado ao carrinho.');
    }

    public function toggleCampoBusca()
    {
        $this->campo_visivel = !$this->campo_visivel;
    }

    // ----- CLIENTE -----

    public function updatedBuscaCliente()
    {
        if ($this->busca_cliente === '') {
            $this->sugestoes_clientes = [];
            return;
        }

        $this->sugestoes_clientes = Cliente::where('nome', 'like', '%' . $this->busca_cliente . '%')
            ->orWhere('telefone', 'like', '%' . $this->busca_cliente . '%')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function selecionarCliente($clienteId)
    {
        $cliente = Cliente::find($clienteId);

        if ($cliente) {
            $this->cliente_id = $cliente->id;
            $this->cliente_nome = $cliente->nome;
            $this->busca_cliente = '';
            $this->sugestoes_clientes = [];
        }
    }

    public function clienteSelecionado($clienteId, $clienteNome)
    {
        $this->cliente_id = $clienteId;
        $this->cliente_nome = $clienteNome;
    }

    // ----- RENDER -----

    public function render()
    {
        $this->verificarCaixa();

        return view('livewire.carrinho', [
            'totalCarrinho' => $this->total,
            'carrinho' => $this->carrinho,
            'mensagem_erro' => $this->mensagem_erro,
            'mensagem_sucesso' => $this->mensagem_sucesso,
            'total' => $this->total,
            'desconto_total' => $this->desconto_total,
            'cliente_nome' => $this->cliente_nome,
            'sugestoes_clientes' => $this->sugestoes_clientes,
        ]);
    }
}
