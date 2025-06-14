<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Servico;
use Livewire\WithPagination;

class ServicosLista extends Component
{
    //método de buscao
    use WithPagination;

    public $busca = '';
    public $porPagina = 10;
    public $categoriaFiltro = '';
    protected $updatesQueryString = ['busca'];

    public function updatingBusca()
    {
        $this->resetPage();
    }

    public function render()
    {
        $servicos = Servico::query()
            ->when($this->busca, fn($q) => $q->where('nome', 'like', '%' . $this->busca . '%'))
            ->when($this->categoriaFiltro, fn($q) => $q->where('categoria', $this->categoriaFiltro))
            ->orderBy('created_at', 'desc')
            ->paginate($this->porPagina);

        $categorias = Servico::select('categoria')->distinct()->pluck('categoria');

        return view('livewire.servicos-lista', compact('servicos', 'categorias'));
    }

    public function deletar($id)
    {
        $servico = Servico::find($id);

        if ($servico) {
            $servico->delete();
            session()->flash('sucesso', 'Serviço deletado com sucesso!');
        }
    }
}
