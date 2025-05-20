<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use Illuminate\Http\Request;

class ComandaController extends Controller
{
    public function print($vendaId)
    {
        $venda = Venda::with(['itens.produto', 'cliente', 'mesa'])->findOrFail($vendaId);

        return view('comandas.print', compact('venda'));
    }
}
