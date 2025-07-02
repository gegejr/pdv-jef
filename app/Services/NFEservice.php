<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class NFeService
{
    public function emitirNota($venda): array
    {
        $payload = $this->payload($venda);

        try {
            \Log::info('Payload enviado à NFe.io:', $payload);

            $response = Http::withHeaders([
                'Authorization' => 'Token ' . config('services.nfeio.token'),
                'Content-Type'  => 'application/json',
            ])
            ->withOptions([
                'verify' => false
            ])
            ->timeout(15)
            ->retry(3, 200)
            ->withBody(json_encode($payload), 'application/json')
            ->post(
                rtrim(config('services.nfeio.base_url'), '/') . '/v2/companies/' . config('services.nfeio.company_id') . '/productinvoices'
            )
            ->throw();

            return $response->json();

        } catch (RequestException $e) {
            return [
                'erro'     => true,
                'message'  => $e->getMessage(),
                'response' => optional($e->response)->json(),
                'raw'      => optional($e->response)->body(),
            ];
        }
    }

    public function consultarNota(string $nfeId): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . config('services.nfeio.token'),
                'Content-Type'  => 'application/json',
            ])
            ->get(
                rtrim(config('services.nfeio.base_url'), '/') . '/v1/companies/' . config('services.nfeio.company_id') . "/nfe/{$nfeId}"
            )
            ->throw();

            return $response->json();

        } catch (\Throwable $e) {
            \Log::error("Erro ao consultar nota {$nfeId}: " . $e->getMessage());
            return null;
        }
    }

    private function payload($venda): array
    {
        return [
            'body' => 'Venda de produtos no PDV ControlTruck',
            'buyer' => [
                'name'     => $venda->cliente->nome ?? 'Consumidor Final',
                'cpfCnpj'  => $venda->cliente->cpf_cnpj,
                'email'    => $venda->cliente->email,
                'address'  => [
                    'street'   => $venda->cliente->endereco ?? 'NÃO INFORMADO',
                    'number'   => $venda->cliente->numero ?? 'S/N',
                    'district' => $venda->cliente->bairro ?? 'Centro',
                    'city'     => [
                        'code' => $venda->cliente->codigo_ibge ?? '3550308',
                        'name' => $venda->cliente->cidade ?? 'São Paulo',
                    ],
                    'state'   => $venda->cliente->uf ?? 'SP',
                    'zipCode' => $venda->cliente->cep ?? '00000-000',
                ],
            ],
            'items' => $venda->itens->map(function ($item) {
                $produto = $item->produto;

                return [
                    'code'         => (string) ($produto->id ?? '0'),
                    'unitAmount'   => (float) $item->valor_unitario,
                    'quantity'     => (float) $item->quantidade,
                    'cfop'         => 5102,
                    'ncm'          => preg_replace('/\D/', '', $produto->ncm ?? '61091000'),
                    'codeGTIN'     => 'SEM GTIN',
                    'codeTaxGTIN'  => 'SEM GTIN',
                    'description'  => $produto->nome ?? 'Produto sem nome',
                    'tax' => [
                        'totalTax' => 0,
                        'icms' => [
                            'amount'            => 0,
                            'rate'              => (float) ($produto->icms_rate ?? 0),
                            'baseTax'           => 0,
                            'baseTaxSTReduction'=> '0',
                            'baseTaxModality'   => '3',
                            'csosn'             => $produto->csosn ?? '102',
                            'origin'            => '0',
                        ],
                        'pis' => [
                            'amount'   => 0,
                            'rate'     => (float) ($produto->pis_rate ?? 0),
                            'baseTax'  => 0,
                            'cst'      => $produto->cst_pis ?? '99',
                        ],
                        'cofins' => [
                            'amount'   => 0,
                            'rate'     => (float) ($produto->cofins_rate ?? 0),
                            'baseTax'  => 0,
                            'cst'      => $produto->cst_cofins ?? '99',
                        ],
                    ],
                ];
            })->values()->toArray(),
            'payments' => [
                [
                    'paymentType' => 1,
                    'value'       => (float) $venda->total,
                ],
            ],
            'asynchronous' => false,
        ];
    }
}
