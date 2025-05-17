<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produto;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class ProdutoLista extends Component
{

    public function mount()
    {
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
    }
    
    use WithPagination;

    public $pesquisa = '';
    public $carrinho = [];
    public $produtoId; // Para armazenar o ID do produto a ser editado
    public $nome, $codigo_barras, $descricao, $valor, $estoque, $desconto_padrao, $imagem;
    public $imagem_existente; // Para armazenar a imagem existente
    public $registrar_perda = false;
    public $quantidade_perda;
    public $motivo_perda;
    protected $paginationTheme = 'tailwind';

    protected $listeners = ['editarProduto']; // Adicionando o listener para o evento

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
        $this->nome = $produto->nome;
        $this->codigo_barras = $produto->codigo_barras;
        $this->descricao = $produto->descricao;
        $this->valor = $produto->valor;
        $this->estoque = $produto->estoque;
        $this->desconto_padrao = $produto->desconto_padrao;
        $this->imagem_existente = $produto->imagem;
    
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
        $produtos = Produto::where('nome', 'like', '%' . $this->pesquisa . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

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
}
