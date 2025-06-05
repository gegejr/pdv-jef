@if($modalProdutosAberto)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-[22rem] max-h-[80vh] overflow-y-auto p-4 relative">
            <h3 class="text-lg font-semibold mb-2">Selecione um Produto</h3>
            <button
                wire:click="fecharModalProdutos"
                class="absolute top-1 right-2 text-gray-600 hover:text-gray-900 font-bold"
                title="Fechar"
            >&times;</button>

            <!-- Campo de busca -->
            <input
                type="text"
                wire:model.live="searchTerm"
                class="form-input w-full mb-3 text-sm"
                placeholder="Buscar produto..."
            >

            <ul class="divide-y">
                @foreach($todos_produtos as $produto)
                    @if(
                            str_contains(strtolower($produto['nome']), strtolower($searchTerm)) ||
                            str_contains((string)$produto['codigo_barras'], $searchTerm)
                        )
                            <li
                                wire:click="selecionarProduto({{ $produto['id'] }})"
                                class="p-3 hover:bg-gray-100 cursor-pointer text-sm"
                            >
                                <div class="font-semibold text-gray-800">{{ $produto['nome'] }}</div>
                                <div class="text-gray-600 text-xs">
                                    Código: <span class="font-mono">{{ $produto['codigo_barras'] }}</span> |
                                    Estoque: <strong>{{ $produto['estoque'] }}</strong>
                                </div>
                                <div class="text-green-600 font-semibold text-sm mt-1">
                                    R$ {{ number_format($produto['valor'], 2, ',', '.') }}
                                </div>
                            </li>
                        @endif
                @endforeach
            </ul>
        </div>
    </div>
@endif