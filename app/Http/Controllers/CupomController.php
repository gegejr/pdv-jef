<?php

namespace App\Http\Controllers;

use App\Models\Venda;

class CupomController extends Controller
{
    public function imprimir($venda_id)
    {
        // Obter os dados da venda
        $venda = Venda::with('itemVendas.produto')->findOrFail($venda_id);

        // Exibir a view com os dados da venda
        return view('impressao.cupom', compact('venda'));
    }
}
