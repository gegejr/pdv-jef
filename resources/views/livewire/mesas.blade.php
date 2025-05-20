<div class="ml-64 pt-[72px] p-6">
    <div class="flex">
        <!-- Menu lateral -->
        <x-sidebar />

        <!-- Conteúdo principal -->
        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <!-- Topbar -->
            <x-topbar />

            <!-- ======= MESAS LIVRES ======= -->
            <h3 class="font-bold mt-6">Mesas Livres</h3>
            <div class="flex justify-end mb-4">
                <button wire:click="criarMesa" class="bg-green-600 text-white px-4 py-2 rounded">
                    + Cadastrar Mesa
                </button>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($mesasFree as $mesa)
                    <div class="p-4 border rounded shadow text-center">
                        <div class="text-xl font-semibold">Mesa: {{ $mesa->numero }}</div>
                        <button wire:click="abrirPedido({{ $mesa->id }})"
                                class="bg-blue-600 text-white px-3 py-1 mt-2 rounded">
                            Abrir pedido
                        </button>
                    </div>
                @endforeach
            </div>

            <!-- ======= MESAS OCUPADAS ======= -->
            <h3 class="font-bold mt-10">Mesas Ocupadas</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($mesasBusy as $mesa)
                    <div class="p-4 border rounded shadow bg-red-50">
                        <div class="text-xl font-semibold text-center">#{{ $mesa->numero }}</div>

                        @php $venda = $mesa->ultimaVenda; @endphp

                        @if ($venda)
                            <ul class="text-left mt-2 text-sm list-disc pl-4">
                                @foreach ($venda->itens as $item)
                                    <li>{{ $item->quantidade }}× {{ $item->produto->nome }}</li>
                                @endforeach
                            </ul>
                            <p class="font-semibold mt-1">
                                Total: R$ {{ number_format($venda->total, 2, ',', '.') }}
                            </p>
                        @endif

                        <div class="flex flex-col gap-2 mt-3">
                            <button wire:click="verDetalhes({{ $mesa->id }})"
                                    class="bg-gray-700 text-white px-3 py-1 rounded">
                                Ver detalhes
                            </button>
                            <button wire:click="finalizarMesa({{ $mesa->id }})"
                                    class="bg-green-600 text-white px-3 py-1 rounded">
                                Finalizar mesa
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- ======= MODAL PEDIDO (criação) ======= -->
            @if($showModalPedido)
                <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
                    <div class="bg-white w-full max-w-2xl p-6 rounded">
                        <h2 class="text-xl font-bold mb-4">Pedido – Mesa #{{ $mesaSelecionada->numero }}</h2>

                        <!-- Cliente -->
                        <label class="block mb-1 font-semibold">Cliente (opcional)</label>
                        <select wire:model="cliente_id" class="border rounded w-full px-3 py-2 mb-4">
                            <option value="">-- Selecionar --</option>
                            @foreach ($clientes as $c)
                                <option value="{{ $c->id }}">{{ $c->nome }}</option>
                            @endforeach
                        </select>

                        <!-- Lista de produtos -->
                        <label class="block mb-1 font-semibold">Adicionar item</label>
                        <div class="grid grid-cols-2 gap-2 mb-4 max-h-40 overflow-y-auto">
                            <button wire:click="$set('showModalProdutos', true)"
                                    class="bg-blue-600 text-white px-3 py-2 rounded">
                                + Adicionar Item
                            </button>
                        </div>

                        @if($showModalProdutos)
                            <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
                                <div class="bg-white w-full max-w-2xl p-6 rounded shadow-lg">
                                    <h2 class="text-lg font-bold mb-4">Buscar Produto</h2>

                                    <!-- Campo de busca -->
                                    <input type="text" wire:model.debounce.300ms="buscaProduto"
                                           placeholder="Buscar por nome ou código de barras..."
                                           class="border px-3 py-2 w-full rounded mb-4">

                                    <!-- Lista de produtos -->
                                    <div class="max-h-72 overflow-y-auto grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @forelse($produtosFiltrados as $p)
                                            <button wire:click="addItem({{ $p->id }})"
                                                    class="border px-3 py-2 rounded hover:bg-gray-100 text-left">
                                                <div class="font-semibold">{{ $p->nome }}</div>
                                                <div class="text-sm text-gray-600">Código: {{ $p->codigo_barras ?? '—' }}</div>
                                                <div class="text-green-700 font-bold">R$ {{ number_format($p->valor, 2, ',', '.') }}</div>
                                            </button>
                                        @empty
                                            <div class="text-center text-gray-500 col-span-2">Nenhum produto encontrado</div>
                                        @endforelse
                                    </div>

                                    <!-- Botão fechar -->
                                    <div class="text-right mt-4">
                                        <button wire:click="$set('showModalProdutos', false)"
                                                class="px-4 py-2 bg-gray-300 rounded">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Carrinho -->
                        <h3 class="font-semibold mb-2">Itens do pedido</h3>
                        <ul class="mb-4 space-y-1">
                            @foreach ($itensCarrinho as $index => $item)
                                <li class="flex justify-between items-center">
                                    {{ $item['qtd'] }} × {{ $item['produto']->nome }}
                                    <button wire:click="removerItem({{ $index }})"
                                            class="text-red-500 ml-2">×</button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Ações -->
                        <div class="flex justify-end gap-2">
                            <button wire:click="$set('showModalPedido', false)"
                                    class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                            <button wire:click="finalizarPedido"
                                    class="px-4 py-2 bg-green-600 text-white rounded">Finalizar</button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ======= MODAL DETALHES (somente leitura) ======= -->
            @if ($showModalDetalhes && $vendaDetalhes)
                <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
                    <div class="bg-white w-full max-w-lg p-6 rounded">
                        <h2 class="text-xl font-bold mb-4">
                            Detalhes – Mesa #{{ $vendaDetalhes->mesa->numero }}
                        </h2>

                        <p class="mb-2"><strong>Cliente:</strong>
                            {{ $vendaDetalhes->cliente->nome ?? '—' }}
                        </p>

                        <ul class="list-disc pl-4 mb-4">
                            @foreach ($vendaDetalhes->itens as $item)
                                <li>{{ $item->quantidade }}× {{ $item->produto->nome }}
                                    (R$ {{ number_format($item->produto->valor, 2, ',', '.') }})
                                </li>
                            @endforeach
                        </ul>

                        <p class="font-semibold">
                            Total: R$ {{ number_format($vendaDetalhes->total, 2, ',', '.') }}
                        </p>

                        <div class="text-right mt-6">
                            <button wire:click="$set('showModalDetalhes', false)"
                                    class="bg-gray-300 px-4 py-2 rounded">Fechar</button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ======= CONFIRMAR IMPRESSÃO ======= -->
            @if ($confirmarImpressao)
                <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
                    <div class="bg-white p-6 rounded shadow w-full max-w-md">
                        <h2 class="text-lg font-bold mb-4">Deseja imprimir a comanda?</h2>
                        <div class="flex justify-end gap-4">
                            <button wire:click="$set('confirmarImpressao', false)"
                                    class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                            <button wire:click="confirmarImpressaoComanda({{ $vendaGeradaId }})"
                                    class="bg-green-600 text-white px-4 py-2 rounded">
                                Sim
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        window.addEventListener('printComanda', event => {
            const vendaId = event.detail.vendaId;
            const url = `/comanda/print/${vendaId}`;
            const printWindow = window.open(url, '_blank');
        });
    </script>
</div>
