<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mesa;
use App\Models\Produto;
class Mesas extends Component
{


    public $showModalProdutos = false;
    public $buscaProduto = '';
    public $showModalPedido = false;
    public $showModalMesa = false;
    public array $carrinho = [];

    public $numero, $reserva_nome;          // modal mesa
    public $mesaSelecionada;                // id da mesa que receberá o pedido

    // campos do modal pedido
    public $cliente_id;
    public $itensCarrinho = [];             // ['produto_id' => ['produto' => obj, 'qtd' => 2]]
    public $vendaDetalhes;           // Venda mostrada no modal
    public $showModalDetalhes = false;
    protected $listeners = ['addItem', 'confirmarImpressaoComanda','mesaAtualizada' => 'carregarMesas'];     // virá de outro comp. ou JS se preferir
    public $vendaGeradaId = null;
    public $confirmarImpressao = false;
    public $vendaId; // 👈 Adicione isso aqui

    /** ---------- LISTAGEM ---------- */
    public function render()
    {
        return view('livewire.mesas', [
             // ➊ Livres
            'mesasFree' => Mesa::where('status', 'livre')
                               ->orderBy('numero')
                               ->get(),

            // ➋ Ocupadas
            'mesasBusy' => Mesa::where('status', 'ocupada')
                               ->with([
                                   'ultimaVenda.itens.produto',
                                   'ultimaVenda.cliente'
                               ])
                               ->orderBy('numero')
                               ->get(),

            // ➌ Finalizadas
            'mesasDone' => Mesa::whereNotNull('finalizada_em')
                            ->with([
                                'ultimaVenda.itens.produto',
                                'ultimaVenda.cliente'
                            ])
                            ->orderBy('numero')
                            ->get(),

            'clientes'           => \App\Models\Cliente::orderBy('nome')->get(),
            'produtos'           => \App\Models\Produto::orderBy('nome')->get(),
            'produtosFiltrados'  => $this->produtosFiltrados,
            
        ]);
    }

    public function mount()
    {
        $this->cliente_id = null;
        $this->itensCarrinho = [];
        $this->buscaProduto = '';
        $this->mesaSelecionada = null;
        $this->showModalPedido = false;
        $this->showModalProdutos = false;
        $this->showModalMesa = false;
        $this->showModalDetalhes = false;
    }

    /** ---------- CRUD MESA ---------- */
    public function abrirModalMesa() { /* igual ao seu */ }

    public function salvarMesa() { /* igual ao seu, status => 'livre' */ }

    /** ---------- PEDIDO ---------- */
        public function abrirPedido($mesaId)
        {
            $this->mesaSelecionada = Mesa::findOrFail($mesaId);
            $this->itensCarrinho   = [];
            $this->cliente_id      = null;
            $this->showModalPedido = true;
        }

    public function addItem($produtoId)
    {
        $produto = Produto::find($produtoId);
        if (!$produto) return;

        if ($produto->estoque <= 0) {
            session()->flash('erro', 'Produto sem estoque disponível.');
            return;
        }

        foreach ($this->itensCarrinho as &$item) {
            if ($item['produto']->id === $produtoId) {
                if ($item['qtd'] + 1 > $produto->estoque) {
                    session()->flash('erro', 'Quantidade excede o estoque disponível.');
                    return;
                }

                $item['qtd']++;
                $this->showModalProdutos = false;
                return;
            }
        }

        $this->itensCarrinho[] = [
            'produto' => $produto,
            'qtd' => 1
        ];

        $this->showModalProdutos = false;
    }




    public function removerItem($index)
    {
        unset($this->itensCarrinho[$index]);
        $this->itensCarrinho = array_values($this->itensCarrinho); // Reindexa
    }

    public function finalizarPedido()
    {
        if (!$this->mesaSelecionada || !$this->mesaSelecionada->id) {
            session()->flash('erro', 'Selecione uma mesa antes de finalizar o pedido.');
            return;
        }
        $mesa = $this->mesaSelecionada;
        $venda = $mesa->ultimaVenda;

        if ($venda) {
            $venda->update(['status' => 'ocupada']); // se tiver coluna status em vendas
        }

        $mesa->update(['status' => 'ocupada']);
        
        $this->validate([
            'itensCarrinho' => 'required|array|min:1',
        ]);

        $caixa = \App\Models\Caixa::where('user_id', auth()->id())
                    ->where('status', 'aberto')
                    ->latest()
                    ->first();

        if (!$caixa) {
            session()->flash('erro', 'Nenhum caixa aberto encontrado para este usuário.');
            return;
        }

        foreach ($this->itensCarrinho as $item) {
            if ($item['qtd'] > $item['produto']->estoque) {
                session()->flash('erro', "Produto {$item['produto']->nome} não possui estoque suficiente.");
                return;
            }
        }

        $venda = \App\Models\Venda::create([
            'cliente_id' => $this->cliente_id,
            'user_id' => auth()->id(),
            'mesa_id' => $this->mesaSelecionada->id,
            'caixa_id' => $caixa->id,
            'total' => $this->calcularTotal(),
        ]);

        foreach ($this->itensCarrinho as $item) {
            $venda->itens()->create([
                'produto_id' => $item['produto']->id,
                'quantidade' => $item['qtd'],
                'valor_unitario' => $item['produto']->valor,
            ]);
        // Dar baixa no estoque dos produtos
        foreach ($this->itensCarrinho as $item) {
            $produto = Produto::find($item['produto']->id);
            if ($produto) {
                $produto->estoque -= $item['qtd'];
                if ($produto->estoque < 0) $produto->estoque = 0;
                $produto->save();
            }
        }
        }

        $venda->pagamentos()->create([
            'tipo' => 'conta',
            'valor'=> $venda->total,
            
        ]);
        

        $this->mesaSelecionada->update(['status' => 'ocupada']);

        $this->imprimirComanda($venda);

        $this->showModalPedido = false;
        $this->vendaGeradaId = $venda->id; // salva para usar na confirmação

        $this->confirmarImpressao = true;  // exibe modal de confirmação
        $this->dispatch('vendaFinalizada');
    }

    public function finalizarMesa($mesaId)
    {
        $mesa = Mesa::findOrFail($mesaId);
        $mesa->update([
            'status'         => 'livre',   // libera a mesa
            'finalizada_em'  => now(),     // guarda histórico (opcional)
        ]);

        // Exibe mensagem para o usuário
        session()->flash('success', 'Mesa finalizada com sucesso.');

        // Opcional: se quiser forçar o Livewire atualizar a view, pode usar:
        $this->dispatch('mesaAtualizada'); // emitir evento para a view reagir, se precisar

        // Ou, se quiser recarregar os dados na tela, pode usar:
        // $this->reset(); // ou atualizar propriedades específicas
    }
    public function verDetalhes($mesaId)
    {
        $mesa = \App\Models\Mesa::with('ultimaVenda.itens.produto', 'ultimaVenda.cliente')->findOrFail($mesaId);

        if (!$mesa->ultimaVenda) {
            session()->flash('erro', 'Esta mesa ainda não possui pedidos.');
            return;
        }

        $this->vendaDetalhes = $mesa->ultimaVenda;
        $this->showModalDetalhes = true;
    }

    private function imprimirComanda($venda)
    {
        // Geração rápida via browser:
        $this->dispatch('printComanda', vendaId: $venda->id);

        /* Exemplo se preferir PDF:
        $pdf = \PDF::loadView('pdf.comanda', compact('venda'));
        $pdf->download('comanda_mesa_'.$venda->mesa->numero.'.pdf');
        */
    }

    public function criarMesa()
    {
        // Define o próximo número de mesa
        $proximoNumero = Mesa::max('numero') + 1;

        Mesa::create([
            'numero' => $proximoNumero,
            'status' => 'livre'
        ]);
    }
    public function excluirMesa($id)
    {
        $mesa = Mesa::find($id);

        if ($mesa) {
            $mesa->delete();
            session()->flash('success', 'Mesa excluída com sucesso.');
        } else {
            session()->flash('error', 'Mesa não encontrada.');
        }

        $this->dispatch('mesaAtualizada');
    }




    public function getProdutosFiltradosProperty()
    {
        return Produto::where('nome', 'like', '%' . $this->buscaProduto . '%')
            ->orWhere('codigo_barras', 'like', '%' . $this->buscaProduto . '%')
            ->get();
    }

    public function calcularTotal()
    {
        $total = 0;
            foreach ($this->itensCarrinho as $item) {
                $total += $item['produto']->valor * $item['qtd'];
            }
            return $total;
            }

    public function teste()
    {
        logger('Botão funcionando!');
    }        

    public function confirmarImpressaoComanda($vendaId)
    {
        $venda = \App\Models\Venda::with('mesa', 'itens.produto', 'cliente')->find($vendaId);

        if (!$venda) {
            session()->flash('erro', 'Venda não encontrada para impressão.');
            return;
        }

        $this->imprimirComanda($venda);

        // Resetar o modal de confirmação após impressão
        $this->confirmarImpressao = false;
        $this->vendaGeradaId = null;
    }


    public function atualizarVenda()
    {
        $venda = \App\Models\Venda::findOrFail($this->vendaId);

        // ➊ Repor estoque dos itens antigos
        foreach ($venda->itens as $item) {
            $produto = Produto::find($item->produto_id);
            if ($produto) {
                $produto->estoque += $item->quantidade;
                $produto->save();
            }
        }

        // ➋ Apaga os itens antigos
        $venda->itens()->delete();

        // ➌ Reinsere os novos itens e dá baixa no estoque
        foreach ($this->itensCarrinho as $item) {
            $venda->itens()->create([
                'produto_id' => $item['produto']->id,
                'quantidade' => $item['qtd'],
                'valor_unitario' => $item['produto']->valor,
            ]);

            $produto = Produto::find($item['produto']->id);
            if ($produto) {
                $produto->estoque -= $item['qtd'];
                if ($produto->estoque < 0) $produto->estoque = 0;
                $produto->save();
            }
        }

        // Atualiza o total da venda
        $novoTotal = $this->calcularTotal();
        $venda->update(['total' => $novoTotal]);

        // Atualiza o pagamento (assumindo um pagamento do tipo "conta")
        $pagamento = $venda->pagamentos()->where('tipo', 'conta')->first();

        if ($pagamento) {
            $pagamento->update(['valor' => $novoTotal]);
        } else {
            // Caso não exista pagamento (situação incomum), cria um novo
            $venda->pagamentos()->create([
                'tipo' => 'conta',
                'valor' => $novoTotal,
            ]);
        }

        $this->imprimirComanda($venda);

        $this->reset(['showModalPedido', 'itensCarrinho', 'mesaSelecionada', 'vendaId']);
        $this->dispatch('mesaAtualizada');
    }


    public function adicionarProdutos($mesaId)
    {
        $mesa = Mesa::with('ultimaVenda.itens.produto')->findOrFail($mesaId);

        if (!$mesa->ultimaVenda) {
            session()->flash('erro', 'Não há venda ativa para esta mesa.');
            return;
        }

        $this->mesaSelecionada = $mesa;
        $this->vendaId = $mesa->ultimaVenda->id;

        $this->itensCarrinho = [];

        foreach ($mesa->ultimaVenda->itens as $item) {
            $this->itensCarrinho[] = [
                'produto' => $item->produto,
                'qtd' => $item->quantidade
            ];
        }

        $this->showModalPedido = true;
    }
}


