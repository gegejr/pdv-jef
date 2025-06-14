<div class="ml-64 pt-[72px] p-6 bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Menu lateral -->
        <x-sidebar />

        <!-- Conteúdo principal -->
        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300 space-y-8">
            <!-- Topbar -->
            <x-topbar />

            <!-- MESAS LIVRES -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-800">Mesas Livres</h3>
                    <button wire:click="criarMesa" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                        Cadastrar Mesa
                    </button>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach ($mesasFree as $mesa)
    <div class="bg-white p-4 rounded-xl shadow-md space-y-2">
        <div class="text-lg font-semibold text-gray-700 text-center">Mesa: {{ $mesa->numero }}</div>

        <div class="flex items-center justify-center gap-4 mt-2">
            <img src="{{ asset('build/assets/mesa.png') }}" alt="Ícone" class="w-16 h-16 rounded-md shadow" />

            <button wire:click="abrirPedido({{ $mesa->id }})"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-1.5 rounded hover:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7v10l8-5-8-5z"/>
                </svg>
                Abrir pedido
            </button>
        </div>
    </div>
@endforeach

                </div>
            </div>

            <!-- MESAS OCUPADAS -->
             <!-- MESAS OCUPADAS -->
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mt-10">Mesas Ocupadas</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-4">
                    @foreach ($mesasBusy as $mesa)
                        <div class="bg-red-100 p-4 rounded-xl shadow-md">
                            <div class="text-center text-lg font-bold text-red-800">Mesa: {{ $mesa->numero }}</div>
                            @php $venda = $mesa->ultimaVenda; @endphp
                            <div class="flex justify-center mt-2">
                                <img src="{{ asset('build/assets/mesa.png') }}" alt="Ícone" class="w-16 h-16 rounded-md shadow">
                            </div>
                            @if ($venda)
                                <ul class="text-sm mt-3 text-gray-700 list-disc list-inside space-y-1">
                                    @foreach ($venda->itens as $item)
                                        <li>{{ $item->quantidade }}× {{ $item->produto->nome }}</li>
                                    @endforeach
                                </ul>
                                <p class="mt-2 font-semibold text-right text-red-900">
                                    Total: R$ {{ number_format($venda->total, 2, ',', '.') }}
                                </p>
                            @endif
                            
                            <div class="mt-4 flex flex-col gap-2">
                                <button wire:click="verDetalhes({{ $mesa->id }})"
                                        class="inline-flex items-center justify-center gap-2 bg-gray-700 text-white px-4 py-1.5 rounded hover:bg-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Ver detalhes
                                </button>
                                <button wire:click="adicionarProdutos({{ $mesa->id }})"
                                        class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-1.5 rounded hover:bg-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Adicionar produtos
                                </button>

                                <button wire:click="finalizarMesa({{ $mesa->id }})"
                                        class="inline-flex items-center justify-center gap-2 bg-green-600 text-white px-4 py-1.5 rounded hover:bg-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Finalizar mesa
                                </button>

                            </div>
                        </div>
                    @endforeach
                </div>
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
                                       @foreach ($produtosFiltrados as $p)
                                            <button
                                                wire:click="{{ $p->estoque > 0 ? 'addItem(' . $p->id . ')' : '' }}"
                                                class="border px-3 py-2 rounded hover:bg-gray-100 text-left
                                                    {{ $p->estoque <= 0 ? 'bg-gray-100 opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $p->estoque <= 0 ? 'disabled' : '' }}
                                            >
                                                <div class="font-semibold">{{ $p->nome }}</div>
                                                <div class="text-sm text-gray-600">Código: {{ $p->codigo_barras ?? '—' }}</div>
                                                <div class="{{ $p->estoque <= 0 ? 'text-red-500 font-bold' : 'text-green-700 font-bold' }}">
                                                    {{ $p->estoque <= 0 ? 'Estoque esgotado' : 'R$ ' . number_format($p->valor, 2, ',', '.') }}
                                                </div>
                                            </button>
                                        @endforeach
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
                            @if ($vendaId)
                                <button wire:click="atualizarVenda">Atualizar Pedido</button>
                            @else
                                <button wire:click="finalizarPedido">Finalizar Pedido</button>
                            @endif
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
