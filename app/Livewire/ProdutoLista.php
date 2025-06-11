<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produto;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Models\ItemVenda;
use Illuminate\Support\Facades\DB;

class ProdutoLista extends Component
{

    public function mount()
    {
        //if (!auth()->user()->hasValidSubscription()) {
          //  return redirect()->route('subscription.expired');
        //}
        $this->searchTerm = request()->query('searchTerm', $this->searchTerm);
    }
    
    use WithPagination;

    public $searchTerm = '';
    public $pesquisa = '';
    public $carrinho = [];
    public $produtoId; // Para armazenar o ID do produto a ser editado
    public $nome, $codigo_barras, $descricao, $valor, $estoque, $desconto_padrao, $imagem;
    public $imagem_existente; // Para armazenar a imagem existente
    public $registrar_perda = false;
    public $quantidade_perda;
    public $motivo_perda;
    protected $paginationTheme = 'tailwind';
    public $produtosMaisVendidos = [];
    public $showMaisVendidos = false;
    public $pedidoDeProdutos = [];
    public $produtosEmFalta = [];
    public $showFalta = false;
    protected $listeners = ['editarProduto']; // Adicionando o listener para o evento
    public $produto_id;      // usado para update
       // compatibilidade com o emit anterior


    public $sku;

    public $ncm;

    public $preco_custo;

    public $unidade_medida = 'un';
   
    public $cst_icms;
    public $icms_rate;
    public $cst_ipi;
    public $ipi_rate;
    public $cst_pis;
    public $pis_rate;
    public $cst_cofins;
    public $cofins_rate;
    public $categoria_id;
    public $status = 'ativo';
    public $tamanho;
    public $cor;
    public $genero;
    public $marca;
    public $material;
    public $modelo;
    public $colecao;

    public string $tipo_produto = 'geral';
    public function atualizarPagina()
    {
        $this->resetPage();
    }

    public function excluir($id)
    {
        $produto = Produto::findOrFail($id);
        if ($produto->imagem) {
            Storage::disk('public')->delete($produto->imagem);
        }
        $produto->delete();
        session()->flash('sucesso', 'Produto excluído com sucesso.');
    }

    public function adicionarCarrinho($id)
    {
        $produto = Produto::findOrFail($id);

        $carrinho = session()->get('carrinho', []);
        
        if (isset($carrinho[$id])) {
            $carrinho[$id]['quantidade']++;
        } else {
            $carrinho[$id] = [
                'produto' => $produto,
                'quantidade' => 1,
                'valor_total' => $produto->valor
            ];
        }

        session()->put('carrinho', $carrinho);
        $this->dispatch('carrinhoAtualizado'); // opcional para atualizar a lista
    }

    // Método para tratar o evento de editar
    public function editarProduto($id)
    {
        $this->produtoId = $id;  // Guardar o ID do produto a ser editado
        $produto = Produto::findOrFail($id);
    
        // Preencher os campos do formulário com os dados do produto
        $this->produto_id       = $produto->id;
        $this->nome             = $produto->nome;
        $this->codigo_barras    = $produto->codigo_barras;
        $this->sku              = $produto->sku;
        $this->descricao        = $produto->descricao;
        $this->ncm              = $produto->ncm;
        $this->valor            = $produto->valor;
        $this->preco_custo      = $produto->preco_custo;
        $this->estoque          = $produto->estoque;
        $this->unidade_medida   = $produto->unidade_medida;
        $this->desconto_padrao  = $produto->desconto_padrao;
        $this->categoria_id     = $produto->categoria_id;
        $this->status           = $produto->status;
        $this->imagem_existente = $produto->imagem;
        $this->tamanho = $produto->tamanho;
        $this->cor = $produto->cor;
        $this->genero = $produto->genero;
        $this->marca = $produto->marca;
        $this->material = $produto->material;
        $this->modelo = $produto->modelo;
        $this->colecao = $produto->colecao;
        $this->cst_icms     = $produto->cst_icms;
        $this->icms_rate    = $produto->icms_rate;
        $this->cst_ipi      = $produto->cst_ipi;
        $this->ipi_rate     = $produto->ipi_rate;
        $this->cst_pis      = $produto->cst_pis;
        $this->pis_rate     = $produto->pis_rate;
        $this->cst_cofins   = $produto->cst_cofins;
        $this->cofins_rate  = $produto->cofins_rate;
    
        // Emitir o evento para abrir o modal
        $this->dispatch('openModal');
    }

    // Método para salvar as alterações de um produto
    public function salvar()
    {
        $produto = Produto::findOrFail($this->produtoId);

        // Validação
        $this->validate([
            'nome' => 'required|string|max:255',
            'codigo_barras' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'valor' => 'required|numeric',
            'estoque' => 'required|integer',
            'desconto_padrao' => 'nullable|numeric',
            'imagem' => 'nullable|image|max:2048',
            'quantidade_perda' => 'nullable|integer|min:1',
            'motivo_perda' => 'nullable|in:quebra,descarte,perda',
        ]);

        // Se for carregada uma nova imagem, processa a imagem
        if ($this->imagem) {
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $this->imagem = $this->imagem->store('produtos', 'public');
        }

        // Atualiza os dados do produto
        $produto->update([
            'nome' => $this->nome,
            'codigo_barras' => $this->codigo_barras,
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'estoque' => $this->estoque,
            'desconto_padrao' => $this->desconto_padrao,
            'imagem' => $this->imagem ?: $produto->imagem,
        ]);

        // Registrar a perda
        if ($this->registrar_perda && $this->quantidade_perda > 0 && $this->motivo_perda) {
            \App\Models\ProdutoPerda::create([
                'produto_id' => $produto->id,
                'quantidade' => $this->quantidade_perda,
                'valor' => $produto->valor * $this->quantidade_perda,
                'motivo' => $this->motivo_perda,
            ]);

            // Decrementa o estoque do produto
            $produto->decrement('estoque', $this->quantidade_perda);

            // Emitir o evento para atualizar a lista de perdas
        $this->dispatch('atualizarListaPerdas', $produto->id)->to('produto-perda-lista');
        }

        session()->flash('sucesso', 'Produto atualizado com sucesso.');
        return redirect()->route('produtos.lista'); // com “s”
    }


    public function render()
    {
        $produtos = Produto::query()
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('nome', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('codigo_barras', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('descricao', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
     
            foreach ($produtos as $produto) {
                $produto->vendas_no_mes = $produto->vendasNoMes();
            }
        return view('livewire.produto-lista', compact('produtos'));
    }
    public function fecharModal()
    {
        $this->reset([
            'produtoId', 'nome', 'codigo_barras', 'descricao',
            'valor', 'estoque', 'imagem', 'imagem_existente',
            'desconto_padrao', 'registrar_perda', 'quantidade_perda', 'motivo_perda'
        ]);
    }

    // dentro do seu componente
    public function updatedPesquisa()
    {
        $this->resetPage();
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

    public function abrirMaisVendidos()
    {
        $this->produtosMaisVendidos = ItemVenda::select('produto_id', DB::raw('SUM(quantidade) as total_vendido'))
            ->groupBy('produto_id')
            ->orderByDesc('total_vendido')
            ->with('produto')
            ->take(10)
            ->get();

        $this->showMaisVendidos = true;
    }

    public function fecharMaisVendidos()
    {
        $this->showMaisVendidos = false;
    }

    public function consultarFalta()
    {
        // Produtos mais vendidos (mantém sua lógica original)
        $this->pedidoDeProdutos = ItemVenda::select('produto_id', DB::raw('SUM(quantidade) as total_vendido'))
            ->groupBy('produto_id')
            ->orderByDesc('total_vendido')
            ->with('produto')
            ->take(10)
            ->get();

        // Produtos com estoque abaixo de 10 unidades
        $this->produtosEmFalta = Produto::where('estoque', '<', 10)
            ->orderBy('estoque', 'asc')
            ->get();

        $this->showFalta = true;
    }

    public function fecharFalta()
    {
        $this->showFalta = false;
    }
}
