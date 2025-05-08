@extends('layouts.impressao')

@section('conteudo_impressao')
    <h1>Relatório de Vendas</h1>
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Total</th>
                <th>Método</th>
                <th>Caixa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vendas as $venda)
                <tr>
                    <td>{{ $venda->id }}</td>
                    <td>{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                    <td>R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                    <td>
                        @foreach($venda->pagamentos as $pagamento)
                            {{ ucfirst($pagamento->tipo) }} - R$ {{ number_format($pagamento->valor, 2, ',', '.') }}<br>
                        @endforeach
                    </td>
                    <td>{{ $venda->caixa->nome }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
