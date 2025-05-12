<div class="ml-64 pt-[72px] p-6">
    <div>
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
                        <label for="data_inicial" class="block text-sm">Data Inicial</label>
                        <input type="date" wire:model="data_inicial" class="w-full border p-2 rounded">
                    </div>
                    <div>
                        <label for="data_final" class="block text-sm">Data Final</label>
                        <input type="date" wire:model="data_final" class="w-full border p-2 rounded">
                    </div>
                    <div>
                        <label for="metodo_pagamento" class="block text-sm">Método de Pagamento</label>
                        <select wire:model="metodo_pagamento" class="w-full border p-2 rounded">
                            <option value="">Selecione...</option>
                            <option value="credito">Crédito</option>
                            <option value="debito">Débito</option>
                            <option value="dinheiro">Dinheiro</option>
                            <option value="pix">Pix</option>
                        </select>
                    </div>
                    <div>
                        <label for="caixa_id" class="block text-sm">Caixa</label>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caixa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($vendas as $venda)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $venda->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($venda->total, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @foreach($venda->pagamentos as $pagamento)
                                        <p>{{ ucfirst($pagamento->tipo) }} - R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</p>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->caixa->nome }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <button wire:click="detalhesVenda({{ $venda->id }})" class="text-blue-500 hover:text-blue-700">Ver detalhes</button>
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

        <!-- Detalhes da Venda -->
        @if ($vendaSelecionada)
            <div class="mt-8 p-4 bg-gray-100 rounded-lg">
                <h3 class="text-xl font-bold">Detalhes da Venda #{{ $vendaSelecionada->id }}</h3>
                <div class="mt-4">
                    <h4 class="text-lg font-semibold">Itens Vendidos:</h4>
                    <ul class="list-disc pl-6">
                            @foreach($vendaSelecionada->itens as $item)
                            <li>{{ $item->produto->nome }} - Quantidade: {{ $item->quantidade }} - Preço: R$ {{ number_format($item->preco, 2, ',', '.') }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-4">
                    <p><strong>Total da Venda:</strong> R$ {{ number_format($vendaSelecionada->total, 2, ',', '.') }}</p>
                    <p><strong>Método de Pagamento:</strong></p>
                        <ul class="list-disc pl-6">
                            @foreach($vendaSelecionada->pagamentos as $pagamento)
                                <li>{{ ucfirst($pagamento->tipo) }} - R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</li>
                            @endforeach
                        </ul>
                </div>
            </div>
        @endif
    </div>
    </div>

    <!-- SCRIPT E ESTILO DEVEM FICAR DENTRO DO MESMO ROOT -->
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
    </style>
    </div>
</div>