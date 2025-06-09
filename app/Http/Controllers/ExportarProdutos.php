<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use Barryvdh\DomPDF\Facade\Pdf;
class ExportarProdutos extends Controller
{
     public function exportar(Request $request)
    {
        $query = Produto::with('categoria');

        if ($request->filled('data_inicial')) {
            $query->where('created_at', '>=', $request->data_inicial . ' 00:00:00');
        }

        if ($request->filled('data_final')) {
            $query->where('created_at', '<=', $request->data_final . ' 23:59:59');
        }

        $produtos = $query->get();

        $pdf = Pdf::loadView('pdf.relatorio-produto', [
            'Produto' => $produtos,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('relatorio-produtos.pdf');
    }

}
