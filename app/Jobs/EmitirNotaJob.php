<?php

namespace App\Jobs;

use App\Models\Venda;
use App\Services\NFeService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EmitirNotaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $vendaId) {}

    public function handle(NFeService $service)
    {
        $venda = Venda::with(['cliente', 'itens.produto', 'nota_fiscal'])
                      ->findOrFail($this->vendaId);

        DB::transaction(function () use ($service, $venda) {
            $resposta = $service->emitirNota($venda);

            if (isset($resposta['id'])) {
                // Atualiza ou cria a nota fiscal
                $venda->nota_fiscal()->updateOrCreate(
                    ['venda_id' => $venda->id], // critério de busca
                    [
                        'nfe_io_id' => $resposta['id'],
                        'chave'     => $resposta['accessKey'],
                        'link_pdf'  => $resposta['danfePdfUrl'],
                        'status'    => $resposta['status'],
                    ]
                );
            } else {
                // registra o erro para acompanhamento
                $venda->notaFiscalErro()->create([
                    'detalhes' => $resposta,
                ]);
            }
        });
    }
}
