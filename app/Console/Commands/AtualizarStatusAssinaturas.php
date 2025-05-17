<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Assinatura;
use Carbon\Carbon;

function atualizarStatusAssinaturas()
{
    $hoje = Carbon::now()->startOfDay();

    // Atualiza assinaturas que passaram do vencimento para 'atrasado', se ainda estiverem pendentes
    Assinatura::where('data_vencimento', '<', $hoje)
        ->where('status', 'pendente')
        ->update(['status' => 'atrasado']);
}
