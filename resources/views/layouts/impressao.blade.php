@extends('layouts.impressao')

@section('conteudo_impressao')
    <h1>Relatório de Vendas</h1>
<!-- layouts/impressao.blade.php -->
<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caixa</th>
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
