<table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg shadow-sm overflow-hidden">
    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
        <tr>
            <th class="px-4 py-2">Código de Barras</th>
            <th class="px-4 py-2">Produto</th>
            <th class="px-4 py-2">Preço</th>
            <th class="px-4 py-2">Qtd</th>
            <th class="px-4 py-2">Total</th>
            <th class="px-4 py-2 text-center">Ações</th>
        </tr>
    </thead>
    <tbody class="divide-y">
        @foreach ($carrinho as $item)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ $item['produto']->codigo_barras }}</td>
                <td class="px-4 py-2">{{ $item['produto']->nome }}</td>
                <td class="px-4 py-2">R$ {{ number_format($item['produto']->valor, 2, ',', '.') }}</td>
                <td class="px-4 py-2">
                    <input 
                        type="number" min="1"
                        wire:change="atualizarQuantidade({{ $item['produto']->id }}, $event.target.value)"
                        value="{{ $item['quantidade'] }}"
                        class="w-20 border-gray-300 rounded p-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </td>
                <td class="px-4 py-2">R$ {{ number_format($item['valor_total'], 2, ',', '.') }}</td>
                <td class="px-4 py-2 text-center">
                    <button wire:click="removerItem({{ $item['produto']->id }})"
                        class="inline-flex items-center text-red-600 hover:text-red-800 font-medium text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
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