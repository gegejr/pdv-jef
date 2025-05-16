<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .periodo {
            text-align: center;
            font-size: 11px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }

        td {
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

    <h2>Relatório de Vendas</h2>
    @if(request('data_inicial') && request('data_final'))
        <div class="periodo">
            {{ \Carbon\Carbon::parse(request('data_inicial'))->format('d/m/Y') }} a {{ \Carbon\Carbon::parse(request('data_final'))->format('d/m/Y') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Cliente</th>
                <th class="text-right">Desconto</th>
                <th class="text-right">Total</th>
                <th>Método</th>
                <th>Usuário</th>
                <th>Caixa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vendas as $venda)
                <tr>
                    <td>{{ $venda->id }}</td>
                    <td>{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $venda->cliente->nome ?? 'Não informado' }}</td>
                    <td class="text-right">{{ number_format($venda->desconto_total, 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($venda->total - $venda->desconto_total, 2, ',', '.') }}</td>
                    <td>
                        @foreach ($venda->pagamentos as $pagamento)
                            <div style="white-space: nowrap;">
                                {{ ucfirst($pagamento->tipo) }} - R$ {{ number_format($pagamento->valor, 2, ',', '.') }}
                            </div>
                        @endforeach
                    </td>
                    <td>{{ $venda->user->name ?? 'N/A' }}</td>
                    <td>{{ $venda->caixa->nome ?? 'N/A' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold; border-top: 2px solid #000;">Total Geral:</td>
                <td class="text-right" style="font-weight: bold; border-top: 2px solid #000;">
                    R$ {{ number_format($totalGeral, 2, ',', '.') }}
                </td>
                <td colspan="3" style="border-top: 2px solid #000;"></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Relatório gerado em {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
