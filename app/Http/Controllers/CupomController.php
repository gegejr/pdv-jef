<?php

namespace App\Http\Controllers;

use App\Models\Venda;

class CupomController extends Controller
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
    
    public function imprimir($venda_id)
    {
        // Obter os dados da venda
        $venda = Venda::with('itemVendas.produto')->findOrFail($venda_id);

        // Exibir a view com os dados da venda
        return view('impressao.cupom', compact('venda'));
    }
}
