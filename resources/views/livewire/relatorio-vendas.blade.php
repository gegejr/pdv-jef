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

            <!-- Título e Botão de Impressão -->
            <div class="flex justify-between items-center mb-4">
                <button onclick="Livewire.dispatch('imprimir-relatorio')"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 no-print">
                    Imprimir Relatório
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
                                <th class="px-6 py-3">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($vendas as $venda)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $venda->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->cliente->nome ?? 'Não informado' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($venda->desconto_total, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">R$ {{ number_format($venda->total - $venda->desconto_total, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @foreach($venda->pagamentos as $pagamento)
                                            <p>{{ ucfirst($pagamento->tipo) }} - R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</p>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->caixa->nome }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <button class="text-blue-500 hover:underline" wire:click="detalhesVenda({{ $venda->id }})">Ver detalhes</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $vendas->links('pagination::tailwind') }}
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
                @include('livewire.detalhes-venda')
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
</div>
