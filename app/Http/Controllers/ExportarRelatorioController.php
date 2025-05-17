<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportarRelatorioController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasValidSubscription()) {
                return redirect()->route('subscription.expired');
            }
            return $next($request);
        });
    }
    
    public function exportar(Request $request)
    {
        $query = Venda::with(['pagamentos', 'cliente', 'user', 'caixa']);

        if ($request->filled('data_inicial')) {
            $query->where('created_at', '>=', $request->data_inicial . ' 00:00:00');
        }

        if ($request->filled('data_final')) {
            $query->where('created_at', '<=', $request->data_final . ' 23:59:59');
        }

        if ($request->filled('metodo_pagamento') && $request->metodo_pagamento != 'todos') {
            $query->whereHas('pagamentos', function ($q) use ($request) {
                $q->where('tipo', $request->metodo_pagamento);
            });
        }

        // ✅ Primeiro obtém os dados
        $vendas = $query->get();

        // ✅ Depois calcula o total
        $totalGeral = $vendas->sum(function ($venda) {
            return $venda->total - $venda->desconto_total;
        });

        $pdf = Pdf::loadView('pdf.relatorio', [
            'vendas' => $vendas,
            'totalGeral' => $totalGeral,
        ]);

        return $pdf->download('relatorio-vendas.pdf');
    }
}
