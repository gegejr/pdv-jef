<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioFinanceiroController extends Controller
{
    public function exportarPdf()
    {
        $lancamentos = FinancialTransaction::query()
            ->when(request('tipo') && request('tipo') !== 'todos', fn($q) => $q->where('tipo', request('tipo')))
            ->when(request('data_inicial'), fn($q) => $q->whereDate('data_vencimento', '>=', request('data_inicial')))
            ->when(request('data_final'), fn($q) => $q->whereDate('data_vencimento', '<=', request('data_final')))
            ->when(request('categoria'), fn($q) => $q->where('categoria', 'like', '%' . request('categoria') . '%'))
            ->get();

        $total = $lancamentos->sum('valor');
        $totalPago = $lancamentos->where('pago', true)->sum('valor');
        $totalPendente = $lancamentos->where('pago', false)->sum('valor');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.relatorio-financeiro', compact('lancamentos', 'total', 'totalPago', 'totalPendente'));
        return $pdf->download('relatorio-financeiro.pdf');
    }

}
