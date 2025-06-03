<div class="ml-64 pt-[72px] p-6">
    <div class="flex">
        <!-- Menu lateral -->
        <div>
            <x-sidebar class="no-print" />
        </div>

        <!-- Conteúdo principal -->
        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <!-- Topbar -->
            <div>
                <x-topbar class="no-print" />
            </div>
            <div 
                x-data="{ message: '', show: false }" 
                x-on:notify.window="
                    message = $event.detail.message;
                    show = true;
                    setTimeout(() => show = false, 3000);
                "
                x-show="show"
                x-transition
                class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow"
            >
                <span x-text="message"></span>
            </div>

             <!-- Título e Botão de Impressão -->
            <div class="flex justify-between items-center mb-4">
               <!-- <button onclick="Livewire.dispatch('imprimir-relatorio')"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 no-print">
                    Imprimir Relatório
                </button>
                -->
                            <!-- Botão Exportar -->
                <button onclick="document.getElementById('modal-exportar').classList.remove('hidden')"
                    class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 no-print">
                    Exportar PDF
                </button>
                <!-- Botão Produtos Mais Vendidos -->
                <button wire:click="abrirMaisVendidos"
                    class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 no-print">
                    Produtos Mais Vendidos
                </button>
            </div>

            <h2 class="text-xl font-bold mb-4">Relatório de Vendas</h2>

            <!-- Filtros -->
            <div class="mb-4 no-print">
                <form wire:submit.prevent="filtrarRelatorio">
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm">Data Inicial</label>
                            <input type="date" wire:model="data_inicial" class="w-full border p-2 rounded">
                        </div>
                        <div>
                            <label class="block text-sm">Data Final</label>
                            <input type="date" wire:model="data_final" class="w-full border p-2 rounded">
                        </div>
                        <div>
                            <label class="block text-sm">Método de Pagamento</label>
                            <select wire:model="metodo_pagamento" class="w-full border p-2 rounded">
                                <option value="">Selecione...</option>
                                <option value="credito">Crédito</option>
                                <option value="debito">Débito</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="pix">Pix</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm">Caixa</label>
                            <select wire:model="caixa_id" class="w-full border p-2 rounded">
                                <option value="">Selecione...</option>
                                @foreach($caixas as $caixa)
                                    <option value="{{ $caixa->id }}">{{ $caixa->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-4 mt-4">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ÁREA A SER IMPRESSA -->
            <div class="print-area">
                <div class="overflow-x-auto bg-white shadow rounded-lg max-w-full">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3">Data</th>
                                <th class="px-6 py-3">Cliente</th>
                                <th class="px-6 py-3">Desconto</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3">Método</th>
                                <th class="px-6 py-3">Usuário</th>
                                <th class="px-6 py-3">Caixa</th>
                                {{-- nova coluna --}}
                                <th class="px-6 py-3">NF-e</th>
                                <th class="px-6 py-3">Nota Fiscal</th>
                                <th class="px-6 py-3">Estorno</th>
                                <th class="px-6 py-3">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($vendas as $venda)
                                <tr wire:key="venda-{{ $venda->id }}">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $venda->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->cliente->nome ?? 'Não informado' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($venda->desconto_total, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">R$ {{ number_format($venda->total - $venda->desconto_total, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @foreach($venda->pagamentos->where('tipo', '!=', 'conta') as $pagamento)
                                            <p>{{ ucfirst($pagamento->tipo) }} - R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</p>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->caixa->nome }}</td>
                                    {{-- dentro do loop --}}
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if($venda->nota_fiscal)
                                            <a href="{{ $venda->nota_fiscal->link_pdf }}"
                                            target="_blank"
                                            class="text-green-600 hover:underline">
                                                DANFE
                                            </a>
                                        @else
                                            <button wire:click="emitirNota({{ $venda->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="text-indigo-600 hover:underline">
                                                Emitir
                                            </button>
                                            <span wire:loading wire:target="emitirNota({{ $venda->id }})">⏳</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if($venda->nota_fiscal)
                                            <p>Status: {{ $venda->nota_fiscal->status }}</p>
                                            <a href="{{ $venda->nota_fiscal->link_pdf }}" target="_blank" class="text-blue-600 hover:underline">Ver DANFE</a>
                                        @else
                                            <p>Não emitida</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if($venda->status === 'estornada')
                                            <span class="text-red-600 font-semibold">Estornada</span>
                                        @else
                                            <span class="text-green-600">OK</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 relative">
                    
                                        <button wire:click="detalhesVenda({{ $venda->id }})" class="w-full text-left px-4 py-2 hover:bg-gray-100">Ver Detalhes</button>
                                        <button wire:click="confirmarEstorno({{ $venda->id }})" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">Estornar</button>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>

                <div class="mt-4">
                    {{ $vendas->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE DETALHES -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50" wire:click="closeModal"></div>

            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
                <button class="absolute top-2 right-3 text-2xl" wire:click="closeModal">&times;</button>
                <livewire:detalhes-venda :venda-selecionada="$vendaSelecionada->id"
                         :key="'detalhes-'.$vendaSelecionada->id" />
            </div>
        </div>
    @endif

    <!-- SCRIPT E ESTILO -->
    <script>
        window.addEventListener('imprimir-pagina', event => {
            window.print();
        });
    </script>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .print-area, .print-area * {
                visibility: visible;
            }

            .print-area {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    <div id="modal-exportar" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('modal-exportar').classList.add('hidden')"></div>

    <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h3 class="text-lg font-semibold mb-4">Exportar Relatório</h3>
        <form method="GET" action="{{ route('relatorio.exportar') }}" target="_blank">
            <div class="mb-4">
                <label class="block text-sm">Data Inicial</label>
                <input type="date" name="data_inicial" class="w-full border p-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-sm">Data Final</label>
                <input type="date" name="data_final" class="w-full border p-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-sm">Método de Pagamento</label>
                <select name="metodo_pagamento" class="w-full border p-2 rounded">
                    <option value="todos">Todos</option>
                    <option value="credito">Crédito</option>
                    <option value="debito">Débito</option>
                    <option value="dinheiro">Dinheiro</option>
                    <option value="pix">Pix</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Exportar</button>
            </div>
        </form>
    </div>
</div>
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
@if($confirmandoEstorno)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" wire:click="$set('confirmandoEstorno', false)"></div>

        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Confirmar Estorno</h3>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Motivo do Estorno</label>
                <textarea wire:model="motivoEstorno" class="w-full border p-2 rounded" rows="3"></textarea>
                @error('motivoEstorno') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <button wire:click="$set('confirmandoEstorno', false)"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>

                <button wire:click="realizarEstorno"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Confirmar Estorno</button>
            </div>
        </div>
    </div>
@endif
</div>
