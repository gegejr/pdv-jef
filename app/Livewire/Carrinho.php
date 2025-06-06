<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produto;
use App\Models\Caixa;
use App\Models\Venda;
use App\Models\Pagamento;
use App\Models\Cliente;
use App\Services\NFeService;
use Illuminate\Support\Facades\DB;

class Carrinho extends Component
{
    public $venda;
    public $carrinho = [];
    public $total = 0;
    public $desconto_total = 0;
    public $metodo_pagamento;
    public $busca_produto = '';
    public $searchTerm = '';
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
    public $modalClientesAberto = false;
    public $modalProdutosAberto = false;
    public $busca_modal_cliente = '';
    public $busca_modal_produto = '';
    public $pagamentos = [
        ['tipo' => '', 'valor' => 0]
    ];
    public $usuarioContaId = null; // ID do usuário para pagamento "conta"
    public $vendaFinalizadaId;
    protected $listeners = ['clienteSelecionado'];
    public $todos_clientes = [];
    public $todos_produtos = [];
    public $confirmarImpressaoCupom;

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
        $this->campo_visivel = true;
        $this->todos_clientes = \App\Models\Cliente::select('id', 'nome', 'telefone')->get()->toArray();
        $this->todos_produtos = \App\Models\Produto::select('id', 'nome', 'valor', 'estoque', 'codigo_barras')->get()->toArray();
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

        // 1. Carrinho vazio?
        if (empty($this->carrinho)) {
            session()->flash('error', 'O carrinho está vazio.');
            return;
        }

        // 2. Validação dos pagamentos
        if (!$this->validarPagamentos()) {
            return;
        }

        // 3. Valida produtos e estoque
        foreach ($this->carrinho as &$item) {
            $item['produto'] = Produto::find($item['produto']->id);

            if (!$item['produto'] || $item['produto']->estoque < $item['quantidade']) {
                session()->flash('error', 'Estoque insuficiente para "' . $item['produto']->nome . '".');
                return;
            }
        }

        try {
            DB::beginTransaction();

            // 4. Garante caixa aberto
            $caixa = Caixa::firstOrCreate(
                ['user_id' => $user_id, 'fechado_em' => null],
                ['nome' => 'Caixa', 'valor_inicial' => 0, 'aberto_em' => now()]
            );

            // 5. Descrição dos métodos de pagamento
            $this->metodo_pagamento = implode(' + ', array_column($this->pagamentos, 'tipo'));

            // 6. Cria venda
           $venda = Venda::create([
                'user_id'         => $user_id,
                'caixa_id'        => $caixa->id,
                'cliente_id'      => $this->cliente_id,
                'total'           => $this->total,
                'desconto_total'  => $this->desconto_total,
                'metodo_pagamento'=> $this->metodo_pagamento,
            ]);

            /*if (!$venda || !$venda->id) {
                throw new \Exception('Venda criada, mas sem ID. Debug: ' . json_encode($venda));
            }

            // Debug detalhado da venda criada
            dd([
                'Venda criada com ID' => $venda->id,
                'Atributos salvos' => $venda->toArray(),
            ]);
            */

            // 7. Itens & estoque
            foreach ($this->carrinho as $item) {
                $produto = $item['produto'];
                $produto->decrement('estoque', $item['quantidade']);

                $venda->itemVendas()->create([
                    'produto_id'     => $produto->id,
                    'total'          => $item['valor_total'],
                    'quantidade'     => $item['quantidade'],
                    'valor_unitario' => $produto->valor,
                    'desconto'       => $item['desconto'] ?? 0,
                ]);
            }

            // 8. Pagamentos
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
                DB::rollBack();
                session()->flash('error',
                    'Pagamentos somam R$ ' . number_format($totalRecebido, 2, ',', '.') .
                    ' mas o total é R$ ' . number_format($valorEsperado, 2, ',', '.')
                );
                return;
            }

            DB::commit();

            // Define o ID da venda finalizada
            $this->vendaFinalizadaId = $venda->id;

            // Carrega venda com nota fiscal (caso exista)
            $this->venda = $venda->load('nota_fiscal');

            // 9. Limpa estado
            session()->forget('carrinho');
            $this->reset([
                'carrinho', 'total', 'desconto_total',
                'cliente_id', 'cliente_nome',
                'pagamentos'
            ]);
            $this->pagamentos = [['tipo' => '', 'valor' => 0]];

            session()->flash('message', 'Venda finalizada com sucesso!');          
            
            
            
            $this->confirmarImpressaoCupom = true;
            //$this->dispatch('imprimir-cupom', venda_id: $this->vendaFinalizadaId); // Laravel 10+
           // $this->vendaFinalizadaId = null;

            return;
            
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao finalizar a venda: ' . $e->getMessage());
            return;
        }
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
            $this->searchTerm = trim($this->searchTerm); // remove espaços

            $produto = Produto::query()
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('nome', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('codigo_barras', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('descricao', 'like', '%' . $this->searchTerm . '%');
                });
            })->first();

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

        $this->searchTerm = '';
        $this->sugestoes = [];
        $this->atualizarCarrinho();

        session()->flash('message', 'Produto adicionado ao carrinho.');
    }

    public function updatedBuscaProduto()
    {
        if ($this->searchTerm === '') {
            $this->sugestoes = [];
            return;
        }

        $this->sugestoes = Produto::query()
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('nome', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('codigo_barras', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('descricao', 'like', '%' . $this->searchTerm . '%');
                });
            })->get()
            ->toArray();
    }

    public function selecionarProduto($produtoId = null)
    {
        // Se veio com $produtoId, seleciona direto, senão usa busca_produto
        if ($produtoId) {
            $produto = Produto::find($produtoId);
            if (!$produto) {
                session()->flash('error', 'Produto não encontrado.');
                return;
            }
        } else {
            $input = $this->searchTerm;

            $produto = Produto::where('codigo_barras', $input)->first();

            if (!$produto && is_numeric($input)) {
                $produto = Produto::find((int)$input);
            }

            if (!$produto) {
                $produto = Produto::where('id', 'like', '%' . $input . '%')->first();
            }
            if (!$produto) {
                $produto = Produto::where('nome', 'like', '%' . $input . '%')->first();
            }
            if (!$produto) {
                $produto = Produto::where('codigo_barras', 'like', '%' . $input . '%')->first();
            }

            if (!$produto) {
                session()->flash('error', 'Produto não encontrado.');
                return;
            }
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

        $this->searchTerm = '';
        $this->sugestoes = [];
        $this->atualizarCarrinho();
        $this->campo_visivel = true;

        // Fecha o modal se estiver aberto
        $this->modalProdutosAberto = false;

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
            $this->campo_visivel = true;

            // Fecha modal clientes se aberto
            $this->modalClientesAberto = false;
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

        $produto = Produto::query()
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('nome', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('codigo_barras', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('descricao', 'like', '%' . $this->searchTerm . '%');
                });
            })->get();

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

    public function abrirModalClientes()
    {
        $this->modalClientesAberto = true;
    }

    public function fecharModalClientes()
    {
        $this->modalClientesAberto = false;
    }

    public function abrirModalProdutos()
    {
        $this->modalProdutosAberto = true;
    }

    public function fecharModalProdutos()
    {
        $this->modalProdutosAberto = false;
    }
        protected $queryString = [
        'searchTerm' => ['except' => ''],
    //    'selectedCategories' => ['except' => []],
    //    'selectedBrands' => ['except' => []]
    ];

    public function updatedSearchTerm()
    {
        $this->dispatch('updateQueryString', 'searchTerm', $this->searchTerm)->self();
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
        //$this->clearCategories();
        //$this->clearBrands();
    }

    public function getTotalInformadoProperty()
    {
        return collect($this->pagamentos)
            ->sum(function ($p) {
                return floatval($p['valor'] ?? 0);
            });
    }
    
    public function updatedPagamentos()
    {
        $esperado = $this->total - $this->desconto_total;
        $recebido = $this->totalInformado;

        if ($recebido > $esperado) {
            session()->flash('error', 'O valor informado excede o total da venda.');
        }
    }

    public function confirmarEImprimirCupom()
    {
        if ($this->vendaFinalizadaId) {
            $this->dispatch('imprimir-cupom', venda_id: $this->vendaFinalizadaId);
        }

        $this->confirmarImpressaoCupom = false;
        $this->vendaFinalizadaId = null;
    }
}
