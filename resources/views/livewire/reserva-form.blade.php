<div class="max-w-7xl mx-auto px-6 pt-24">
    <div class="flex">
        <!-- Sidebar -->
        <x-sidebar class="no-print" />
        <x-topbar class="no-print" />
        <!-- Conteúdo -->
        <div class="flex-1 space-y-8 ml-0 md:ml-28">
            

            <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Reservas
            </h2>

            <!-- Filtros e Ação -->
            <div class="flex flex-wrap items-end gap-4">
                <button wire:click="abrirModal"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                    + Nova Reserva
                </button>

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Data</label>
                    <input type="date" wire:model="filtroData"
                        class="mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Cliente</label>
                    <input type="text" wire:model.debounce.500ms="filtroCliente"
                        placeholder="Nome do cliente"
                        class="mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Mensagem -->
           @if (session()->has('message'))
                <div 
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 3000)" 
                    x-show="show"
                    x-transition
                    class="fixed top-4 right-4 p-4 bg-green-100 text-green-800 rounded shadow-lg z-50"
                >
                    {{ session('message') }}
                </div>
            @endif

            <!-- Lista -->
            <div class="overflow-x-auto bg-white rounded-xl shadow-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase text-center">Serviço</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase text-center">Cliente</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase text-center">Data</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase text-center">Horário</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase text-center">Status</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($reservas as $reserva)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-center">{{ $reserva->servico }}</td>
                                <td class="px-4 py-2 text-center">{{ $reserva->cliente->nome }}</td>
                                <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($reserva->data)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 text-center">{{ $reserva->hora_inicio }} - {{ $reserva->hora_fim }}</td>
                                <td class="px-4 py-2 text-center">
                                    @php
                                        $cores = [
                                            'pendente' => 'bg-yellow-100 text-yellow-800',
                                            'concluida' => 'bg-green-100 text-green-800',
                                            'cancelada' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $cores[$reserva->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($reserva->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center space-x-2 text-sm">
                                    @if ($reserva->status === 'pendente')
                                        <button wire:click="concluir({{ $reserva->id }})"
                                            class="text-green-600 hover:underline">Concluir</button>
                                    @endif
                                    <button wire:click="editar({{ $reserva->id }})"
                                        class="text-blue-600 hover:underline">Editar</button>
                                    <button wire:click="cancelar({{ $reserva->id }})"
                                        class="text-red-600 hover:underline">Cancelar</button>
                                    <button wire:click="abrirModalPagamento({{ $reserva->id }})" class="text-indigo-600 hover:underline">
                                        Finalizar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-gray-500">Nenhuma reserva encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $reservas->links() }}
                </div>
            </div>

            <!-- Modal -->
            @if($showModal)
                <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                    <div class="bg-white rounded-xl shadow-lg w-full max-w-xl p-6 relative">
                        <button wire:click="fecharModal"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-xl">
                            &times;
                        </button>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Nova Reserva</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cliente</label>
                                <select wire:model="cliente_id" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                    <option value="">Selecione</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                                    @endforeach
                                </select>
                                @error('cliente_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Serviço</label>
                                <select wire:model="servico" class="w-full border rounded px-3 py-2">
                                    <option value="">Selecione um serviço</option>
                                    @foreach($servicos as $servico)
                                        <option value="{{ $servico->nome }}">{{ $servico->nome }}</option>
                                    @endforeach
                                </select>
                                @error('servico') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data</label>
                                <input type="date" wire:model="data" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                                @error('data') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Hora Início</label>
                                    <input type="time" wire:model="hora_inicio" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                                    @error('hora_inicio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Hora Fim</label>
                                    <input type="time" wire:model="hora_fim" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                                    @error('hora_fim') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea wire:model="observacoes" rows="3" class="w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                                @error('observacoes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="text-right">
                                <button wire:click="salvar"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">
                                    Salvar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if($showPagamentoModal)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Selecione o método de pagamento</h3>

                        <select wire:model="metodoPagamentoSelecionado" class="w-full border rounded px-3 py-2 mb-4">
                            <option value="">-- Selecione --</option>
                            @foreach($metodosPagamento as $metodo)
                                <option value="{{ $metodo }}">{{ ucfirst($metodo) }}</option>
                            @endforeach
                        </select>

                        @error('metodoPagamentoSelecionado') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                        <div class="flex justify-end space-x-3">
                            <button wire:click="$set('showPagamentoModal', false)" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                            <button wire:click="finalizarServico" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
