<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;

class VendasResumo extends Component
{
    public $totalVendas = 0;
    public $numeroVendas = 0;

    public function mount()
    {
        $this->loadDados();
    }

    public function loadDados()
    {
        $query = Venda::where('status', '!=', 'estornada');

        $this->totalVendas = $query->sum(\DB::raw('total - desconto_total'));
        $this->numeroVendas = $query->count();
    }

    // Atualiza os dados a cada 10 segundos
    public function refresh()
    {
        $this->loadDados();
    }

    public function render()
    {
        return view('livewire.vendas-resumo');
    }
}