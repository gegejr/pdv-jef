 @if($showMaisVendidos)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" wire:click="fecharMaisVendidos"></div>

        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-xl p-6">
            <button class="absolute top-2 right-3 text-2xl" wire:click="fecharMaisVendidos">&times;</button>
            <h3 class="text-lg font-semibold mb-4">Top 10 Produtos Mais Vendidos</h3>

            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr>
                        <th class="text-left px-4 py-2">Produto</th>
                        <th class="text-left px-4 py-2">Quantidade Vendida</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($produtosMaisVendidos as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->produto->nome ?? 'Produto excluído' }}</td>
                            <td class="px-4 py-2">{{ $item->total_vendido }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-2 text-center text-gray-500">Nenhuma venda encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif