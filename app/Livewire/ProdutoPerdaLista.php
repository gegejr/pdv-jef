<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProdutoPerda;
use App\Models\Produto;
use App\Livewire\ProdutoLista;
use Livewire\WithPagination;

class ProdutoPerdaLista extends Component
{
    use WithPagination;
    public $produto_id;
    protected $paginationTheme = 'tailwind';

    protected $listeners = ['atualizarListaPerdas'];

    public function mount($produto_id = null)
    {
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
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
        $query = ProdutoPerda::query();

        // Se tiver produto_id, aplica o filtro
        if ($this->produto_id) {
            $query->where('produto_id', $this->produto_id);
        }

        $perdas = $query->latest()->paginate(10);

        return view('livewire.produto-perda-lista', compact('perdas'));
    }

    public function produto()
    {
        return $this->belongsTo(\App\Models\Produto::class);
    }
}

