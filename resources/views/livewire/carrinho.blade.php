<div class="p-4">
    <h2 class="text-xl font-bold mb-4">Carrinho de Compras</h2>

    @if (count($carrinho) > 0)
        <table class="w-full table-auto border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Produto</th>
                    <th class="p-2 border">Preço</th>
                    <th class="p-2 border">Quantidade</th>
                    <th class="p-2 border">Total</th>
                    <th class="p-2 border">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carrinho as $item)
                    <tr>
                        <td class="p-2 border">{{ $item['produto']->nome }}</td>
                        <td class="p-2 border">R$ {{ number_format($item['produto']->valor, 2, ',', '.') }}</td>
                        <td class="p-2 border">
                            <input type="number" min="1" class="border rounded px-2 py-1 w-20"
                                wire:change="atualizarQuantidade({{ $item['produto']->id }}, $event.target.value)"
                                value="{{ $item['quantidade'] }}">
                        </td>
                        <td class="p-2 border">R$ {{ number_format($item['valor_total'], 2, ',', '.') }}</td>
                        <td class="p-2 border text-center">
                            <button wire:click="removerItem({{ $item['produto']->id }})"
                                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                Remover
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 text-right">
            <strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong>
        </div>

        <div class="mt-4 text-right">
            <a href="{{ route('fechar-venda') }}"
               class="px-4 py-2 rounded text-white
                      {{ count($carrinho) > 0 ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-400 cursor-not-allowed' }}"
               {{ count($carrinho) > 0 ? '' : 'onclick=return false;' }}>
                Fechar Venda
            </a>
        </div>
    @else
        <div class="text-center p-4">Carrinho vazio</div>
    @endif
</div>
