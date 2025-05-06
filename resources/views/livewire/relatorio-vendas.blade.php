<div class="p-4">
    <h2 class="text-xl font-bold mb-4">Relatório de Vendas</h2>

    @if (count($vendas) > 0)
        <table class="w-full table-auto border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">ID Venda</th>
                    <th class="p-2 border">Cliente</th>
                    <th class="p-2 border">Data</th>
                    <th class="p-2 border">Total</th>
                    <th class="p-2 border">Método de Pagamento</th>
                    <th class="p-2 border">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vendas as $venda)
                    <tr>
                        <td class="p-2 border">{{ $venda->id }}</td>
                        <td class="p-2 border">{{ $venda->usuario->name }}</td>
                        <td class="p-2 border">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-2 border">R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                        <td class="p-2 border">{{ ucfirst($venda->metodo_pagamento) }}</td>
                        <td class="p-2 border text-center">
                            <a href="#" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600"
                               wire:click.prevent="detalhesVenda({{ $venda->id }})">
                                Ver Detalhes
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center p-4">Nenhuma venda encontrada.</div>
    @endif

    @if ($vendaSelecionada)
        <div class="mt-4">
            <h3 class="text-lg font-semibold mb-2">Detalhes da Venda #{{ $vendaSelecionada->id }}</h3>
            <table class="w-full table-auto border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Produto</th>
                        <th class="p-2 border">Preço</th>
                        <th class="p-2 border">Quantidade</th>
                        <th class="p-2 border">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendaSelecionada->itens as $item)
                        <tr>
                            <td class="p-2 border">{{ $item->produto->nome }}</td>
                            <td class="p-2 border">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                            <td class="p-2 border">{{ $item->quantidade }}</td>
                            <td class="p-2 border">R$ {{ number_format($item->valor_unitario * $item->quantidade, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <strong>Total: R$ {{ number_format($vendaSelecionada->total, 2, ',', '.') }}</strong>
            </div>
        </div>
    @endif
</div>
