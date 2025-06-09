<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Produtos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2>Relatório de Produtos</h2>
    <p>Data de geração: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>SKU</th>
                <th>Cód. Barras</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Produto as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->nome }}</td>
                    <td>{{ $p->categoria->nome ?? '—' }}</td>
                    <td>R$ {{ number_format($p->valor, 2, ',', '.') }}</td>
                    <td>{{ $p->estoque }}</td>
                    <td>{{ $p->sku ?? '—' }}</td>
                    <td>{{ $p->codigo_barras ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
