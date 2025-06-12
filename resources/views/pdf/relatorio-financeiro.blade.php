<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Financeiro</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .totais {
            margin-bottom: 20px;
        }
        .totais p {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2>Relatório Financeiro</h2>

    <div class="totais">
        <p><strong>Total:</strong> R$ {{ number_format($total, 2, ',', '.') }}</p>
        <p><strong>Pago:</strong> R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
        <p><strong>Pendente:</strong> R$ {{ number_format($totalPendente, 2, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Vencimento</th>
                <th>Pago</th>
                <th>Categoria</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lancamentos as $l)
                <tr>
                    <td>{{ $l->descricao }}</td>
                    <td>{{ ucfirst($l->tipo) }}</td>
                    <td>R$ {{ number_format($l->valor, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($l->data_vencimento)->format('d/m/Y') }}</td>
                    <td>{{ $l->pago ? 'Sim' : 'Não' }}</td>
                    <td>{{ $l->categoria }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Nenhum lançamento encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
