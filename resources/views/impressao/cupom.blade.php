<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cupom Fiscal</title>
<style>
    body {
        font-family: monospace, Arial, sans-serif;
        font-size: 10px;
        margin: 0;
        padding: 0;
        width: 58mm; /* largura comum em impressora térmica de 58mm */
    }
    .container {
        padding: 5px 2px;
        width: 100%;
    }
    .header, .footer {
        text-align: center;
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 4px;
    }
    .items {
        margin-top: 4px;
        margin-bottom: 4px;
    }
    .item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2px;
        line-height: 1.1;
    }
    .item span {
        display: inline-block;
    }
    .item span:first-child {
        width: 50%; /* nome do produto */
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .item span:nth-child(2) {
        width: 25%; /* quantidade e preço unit */
        text-align: right;
    }
    .item span:last-child {
        width: 25%; /* preço total */
        text-align: right;
    }
    .total p {
        margin: 2px 0;
        font-size: 11px;
    }
</style>

</head>
<body>

@php
    // Define $venda com base na variável disponível
    $venda = $vendaSelecionada ?? $venda ?? null;

    if (!$venda) {
        echo '<p>Venda não encontrada.</p>';
        exit;
    }
@endphp

<div class="container">
    <div class="header">
        <p>Sonic Distribuidora </p>
        <p>Rua Estados Unidos, Quadra 17 - C 03 - Novo mundo - Varzea Grande</p>
        <p>CNPJ: 53.875.245.0001-29</p>
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

    <div class="items">
        @foreach ($venda->pagamentos as $p)
            <div class="item">
                <span>Pagamento: {{ ucfirst($p->tipo) }}</span>
                <span>R$ {{ number_format($p->valor, 2, ',', '.') }}</span>
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
            window.close();
        };
    };
</script>

</body>
</html>
