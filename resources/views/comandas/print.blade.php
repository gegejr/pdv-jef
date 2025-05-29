<!DOCTYPE html>
<html>
<head>
    <title>Comanda #{{ $venda->id }}</title>
    <style>
        body {
            font-family: monospace, monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
            width: 80mm; /* largura típica da impressora térmica */
        }
        h1 {
            font-size: 14px;
            text-align: center;
            margin-bottom: 5px;
        }
        p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            padding: 2px 0;
            text-align: left;
        }
        th {
            border-bottom: 1px dashed #000;
        }
        td.qty, td.price, td.total {
            text-align: right;
            min-width: 40px;
        }
        .total {
            margin-top: 10px;
            font-weight: bold;
            text-align: right;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <h1>Comanda #{{ $venda->id }}</h1>
    <p><strong>Mesa:</strong> {{ $venda->mesa->numero ?? 'N/A' }}</p>
    <p><strong>Cliente:</strong> {{ $venda->cliente->nome ?? 'N/A' }}</p>

    <table>
        <thead>
            <tr>
                <th>Pedido</th>
                <th class="qty">Qtd</th>
                <th class="price">Preço</th>
                <th class="total">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($venda->itens as $item)
            <tr>
                <td>{{ $item->produto->nome ?? 'Produto removido' }}</td>
                <td class="qty">{{ $item->quantidade }}</td>
                <td class="price">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                <td class="total">R$ {{ number_format($item->quantidade * $item->valor_unitario, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">
        Total da Comanda: R$ {{ number_format($venda->total, 2, ',', '.') }}
    </p>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
