<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FinancialTransaction;
use Livewire\WithPagination;

class RelatorioFinanceiro extends Component
{
    use WithPagination;

    public $tipo = 'todos';
    public $data_inicial;
    public $data_final;
    public $categoria;

    public function render()
    {
        $query = FinancialTransaction::query();

        if ($this->tipo && $this->tipo !== 'todos') {
            $query->where('tipo', $this->tipo);
        }

        if ($this->data_inicial) {
            $query->whereDate('data_vencimento', '>=', $this->data_inicial);
        }

        if ($this->data_final) {
            $query->whereDate('data_vencimento', '<=', $this->data_final);
        }

        if ($this->categoria) {
            $query->where('categoria', 'like', '%' . $this->categoria . '%');
        }

        $lancamentos = $query->orderBy('data_vencimento', 'desc')->paginate(10);

        $total = $query->sum('valor');
        $totalPago = $query->where('pago', true)->sum('valor');
        $totalPendente = $query->where('pago', false)->sum('valor');

        return view('livewire.relatorio-financeiro', compact('lancamentos', 'total', 'totalPago', 'totalPendente'));
    }

   public function aplicarFiltro()
    {
        $this->resetPage(); // volta para a página 1 ao aplicar filtros
    }
}
