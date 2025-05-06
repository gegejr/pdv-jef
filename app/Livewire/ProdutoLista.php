<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produto;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class ProdutoLista extends Component
{
    use WithPagination;

    public $pesquisa = '';
    public $carrinho = [];

    protected $paginationTheme = 'tailwind';

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

    public function render()
    {
        $produtos = Produto::where('nome', 'like', '%' . $this->pesquisa . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.produto-lista', compact('produtos'));
    }
}
