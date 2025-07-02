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
use Illuminate\Support\Facades\Log;

class EmitirNotaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $vendaId) {}

    public function handle(NFeService $service)
    {
        try {
            $venda = Venda::with(['cliente', 'itens.produto', 'nota_fiscal'])
                          ->findOrFail($this->vendaId);

            DB::transaction(function () use ($service, $venda) {
                $resposta = $service->emitirNota($venda);
                Log::info('Resposta da NFe.io:', $resposta);

                if (empty($resposta['id'])) {
                    Log::warning("Resposta da emissão sem ID de nota. Erro?");
                    $venda->notaFiscalErro()->create([
                        'detalhes' => json_encode($resposta),
                    ]);
                    return;
                }

                // Tenta consultar até 5 vezes (esperando o processamento assíncrono da nota)
                $detalhes = null;
                for ($i = 0; $i < 5; $i++) {
                    sleep(2); // aguarda 2s entre tentativas
                    $detalhes = $service->consultarNota($resposta['id']);

                    if (!empty($detalhes['accessKey']) && !empty($detalhes['danfePdfUrl'])) {
                        break;
                    }

                    Log::info("Tentativa {$i} - Nota ainda sem accessKey...");
                }

                if (!empty($detalhes['accessKey']) && !empty($detalhes['danfePdfUrl'])) {
                    $venda->nota_fiscal()->updateOrCreate(
                        ['venda_id' => $venda->id],
                        [
                            'nfe_io_id' => $resposta['id'],
                            'chave'     => $detalhes['accessKey'],
                            'link_pdf'  => $detalhes['danfePdfUrl'],
                            'status'    => $detalhes['status'] ?? 'emitida',
                        ]
                    );
                    Log::info("Nota emitida com sucesso para venda ID {$venda->id}");
                } else {
                    Log::warning("Consulta da nota {$resposta['id']} não retornou dados completos.", $detalhes ?? []);
                    $venda->notaFiscalErro()->create([
                        'detalhes' => json_encode($detalhes ?? []),
                    ]);
                }
            });
        } catch (\Throwable $e) {
            Log::error("Erro ao emitir nota fiscal para venda ID {$this->vendaId}: " . $e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
}
