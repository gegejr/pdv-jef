<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProdutoPerda;
use App\Models\Produto;
use App\Livewire\ProdutoLista;

class ProdutoPerdaLista extends Component
{
    public $produto_id;

    protected $listeners = ['atualizarListaPerdas'];

    public function mount($produto_id = null)
    {
        $this->produto_id = $produto_id;
    }

    // Método para atualizar a lista de perdas
    public function atualizarListaPerdas($produto_id)
    {
        $this->produto_id = $produto_id;
        $this->dispatch('$refresh');
    }

    public function render()
    {
        // Se $produto_id for definido, filtra. Senão, mostra todas.
        $perdas = $this->produto_id
            ? ProdutoPerda::where('produto_id', $this->produto_id)->get()
            : ProdutoPerda::all();

        return view('livewire.produto-perda-lista', compact('perdas'));
    }

    public function produto()
    {
        return $this->belongsTo(\App\Models\Produto::class);
    }
}

