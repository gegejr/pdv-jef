<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VendasResumo extends Component
{
    public $totalVendas = 0;
    public $numeroVendas = 0;

    public $inicio;
    public $fim;

    public function mount($inicio = null, $fim = null)
    {
        $this->inicio = $inicio;
        $this->fim = $fim;

        $this->loadDados();
    }

    public function loadDados()
    {
        $query = Venda::where('status', '!=', 'estornada');

        // Aplicar filtros de data, se informados
        if ($this->inicio) {
            $query->whereDate('created_at', '>=', Carbon::parse($this->inicio)->startOfDay());
        }

        if ($this->fim) {
            $query->whereDate('created_at', '<=', Carbon::parse($this->fim)->endOfDay());
        }

        $this->totalVendas = $query->sum(DB::raw('total - desconto_total'));
        $this->numeroVendas = $query->count();
    }

     // ✅ Esse método precisa ser público
    public function refresh()
    {
        $this->loadDados();
    }
    public function render()
    {
        return view('livewire.vendas-resumo');
    }
}
