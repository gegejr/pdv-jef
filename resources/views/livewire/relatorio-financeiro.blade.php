<div class="ml-64 pt-[72px] p-6 bg-gray-100 min-h-screen">
    <div class="flex">
        <x-sidebar />

        <div class="flex-1 ml-64 md:ml-0 transition-all duration-300">
            <x-topbar />
            <div class="p-6 space-y-6 bg-white shadow rounded-xl">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800">Relatório Financeiro</h2>
                    <a href="{{ route('exportar-financeiro-pdf') }}"
                       class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
                        Exportar PDF
                    </a>
                </div>

                <!-- Filtros -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select wire:model.defer="tipo" class="w-full border-gray-300 rounded shadow-sm">
                            <option value="todos">Todos</option>
                            <option value="pagar">A Pagar</option>
                            <option value="receber">A Receber</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Data Inicial</label>
                        <input type="date" wire:model.defer="data_inicial" class="w-full border-gray-300 rounded shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Data Final</label>
                        <input type="date" wire:model.defer="data_final" class="w-full border-gray-300 rounded shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Categoria</label>
                        <input type="text" wire:model.defer="categoria" class="w-full border-gray-300 rounded shadow-sm" placeholder="Ex: aluguel">
                    </div>
                    <div>
                        <button wire:click="aplicarFiltro"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Aplicar Filtros
                        </button>
                    </div>
                </div>

                <!-- Totais -->
                <div class="bg-gray-100 p-4 rounded-lg shadow-inner space-y-1 text-gray-700 text-sm">
                    <p><strong>Total:</strong> R$ {{ number_format($total, 2, ',', '.') }}</p>
                    <p><strong>Pago:</strong> R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
                    <p><strong>Pendente:</strong> R$ {{ number_format($totalPendente, 2, ',', '.') }}</p>
                </div>

                <!-- Tabela -->
                <div wire:loading.class="opacity-50 pointer-events-none relative">
                    <table class="w-full text-sm text-left border rounded shadow overflow-hidden">
                        <thead class="bg-gray-200 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="p-2 border">Descrição</th>
                                <th class="p-2 border">Tipo</th>
                                <th class="p-2 border">Valor</th>
                                <th class="p-2 border">Vencimento</th>
                                <th class="p-2 border">Pago</th>
                                <th class="p-2 border">Categoria</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lancamentos as $l)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-2 border">{{ $l->descricao }}</td>
                                    <td class="p-2 border">{{ ucfirst($l->tipo) }}</td>
                                    <td class="p-2 border">R$ {{ number_format($l->valor, 2, ',', '.') }}</td>
                                    <td class="p-2 border">{{ \Carbon\Carbon::parse($l->data_vencimento)->format('d/m/Y') }}</td>
                                    <td class="p-2 border">{{ $l->pago ? 'Sim' : 'Não' }}</td>
                                    <td class="p-2 border">{{ $l->categoria }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center p-4 text-gray-500">Nenhum lançamento encontrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $lancamentos->links() }}
                    </div>

                    <!-- Loader -->
                    <div wire:loading
                        class="absolute inset-0 bg-white bg-opacity-70 flex items-center justify-center">
                        <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
