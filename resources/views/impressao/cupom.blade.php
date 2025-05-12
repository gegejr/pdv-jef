<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupom Fiscal</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 10px; }
        .header { text-align: center; font-size: 20px; font-weight: bold; }
        .items { margin-top: 20px; }
        .item { display: flex; justify-content: space-between; padding: 5px 0; }
        .footer { margin-top: 20px; text-align: center; }
        .total { font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p>Loja XYZ</p>
            <p>Rua Exemplo, 123 - Bairro - Cidade</p>
            <p>CNPJ: 00.000.000/0001-00</p>
            <p><strong>CUPOM FISCAL</strong></p>
            <p>{{ date('d/m/Y H:i:s') }}</p>
        </div>

        <div class="items">
            @foreach ($venda->itemVendas as $item)
                <div class="item">
                    <span>{{ $item->produto->nome }}</span>
                    <span>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }} x {{ $item->quantidade }}</span>
                    <span>R$ {{ number_format($item->valor_unitario * $item->quantidade, 2, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="footer">
            <div class="total">
                <p>Total: R$ {{ number_format($venda->total, 2, ',', '.') }}</p>
                <p>Desconto: R$ {{ number_format($venda->desconto_total, 2, ',', '.') }}</p>
                <p><strong>Total a Pagar: R$ {{ number_format($venda->total - $venda->desconto_total, 2, ',', '.') }}</strong></p>
            </div>
            <p>Obrigado pela compra!</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close(); // Fecha a janela após a impressão
            };
        };
    </script>
</body>
</html>
