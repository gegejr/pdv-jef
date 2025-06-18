@section('title', 'Contas Pagar | Receber')
<div class="ml-64 pt-[72px] p-6 bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Menu lateral -->
        <div>
            <x-sidebar class="no-print" />
        </div>
        <x-topbar class="no-print" />

        <!-- Conteúdo principal -->
        <div class="flex-1 ml-64 md:ml-0 transition-all duration-300">
            <div class="p-6 space-y-6 bg-white shadow rounded-xl">

                @if (session()->has('message'))
                    <div class="flex items-center bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4" />
                            <path d="M12 22C6.48 22 2 17.52 2 12S6.48 2 12 2s10 4.48 10 10-4.48 10-10 10z" />
                        </svg>
                        <span>{{ session('message') }}</span>
                    </div>
                @endif

                <!-- Formulário de Lançamento -->
                <form wire:submit.prevent="salvar" class="mb-8 space-y-5 bg-white p-6 rounded-xl shadow-lg border">

                    <h2 class="text-xl font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5A2.121 2.121 0 0 1 19 6v0a2.121 2.121 0 0 1-2.5 2.5L12 12l-2 5-5-5 5-2 3.5-3.5z" />
                        </svg>
                        Novo Lançamento
                    </h2>

                    <div>
                        <label class="block font-semibold mb-1">Descrição</label>
                        <input type="text" wire:model.defer="descricao" class="w-full border rounded px-3 py-2" />
                        @error('descricao') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Tipo</label>
                        <select wire:model.defer="tipo" class="w-full border rounded px-3 py-2">
                            <option value="pagar">Conta a Pagar</option>
                            <option value="receber">Conta a Receber</option>
                        </select>
                        @error('tipo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Valor</label>
                        <input type="number" wire:model.defer="valor" step="0.01" min="0" class="w-full border rounded px-3 py-2" />
                        @error('valor') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Data de Vencimento</label>
                        <input type="date" wire:model.defer="data_vencimento" class="w-full border rounded px-3 py-2" />
                        @error('data_vencimento') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Categoria (opcional)</label>
                        <input type="text" wire:model.defer="categoria" placeholder="ex: aluguel, água..." class="w-full border rounded px-3 py-2" />
                        @error('categoria') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4" />
                        </svg>
                        Salvar
                    </button>
                </form>

                <!-- Filtros -->
                <div class="mb-4 flex gap-2">
                    @foreach(['todos' => 'Todos', 'pagar' => 'A Pagar', 'receber' => 'A Receber'] as $key => $label)
                        <button wire:click="$set('filtro', '{{ $key }}')" 
                            class="px-3 py-1 rounded-full text-sm transition {{ $filtro === $key ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <!-- Listagem -->
                <div wire:loading.class="opacity-50 pointer-events-none relative">
                    <table class="min-w-full bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden text-sm">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Descrição</th>
                                <th class="px-4 py-2 text-left">Tipo</th>
                                <th class="px-4 py-2 text-left">Valor</th>
                                <th class="px-4 py-2 text-left">Vencimento</th>
                                <th class="px-4 py-2 text-left">Categoria</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $t)
                                <tr class="{{ $t->pago ? 'bg-green-50' : 'hover:bg-gray-50' }}">
                                    <td class="px-4 py-2">{{ $t->descricao }}</td>
                                    <td class="px-4 py-2 capitalize text-gray-700">{{ $t->tipo == 'pagar' ? 'Pagar' : 'Receber' }}</td>
                                    <td class="px-4 py-2 font-semibold text-gray-800">R$ {{ number_format($t->valor, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($t->data_vencimento)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $t->categoria ?? '-' }}</td>
                                    <td class="px-4 py-2">
                                        @if ($t->pago)
                                            <span class="text-green-700 font-medium flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"><path d="M5 13l4 4L19 7" /></svg>
                                                Pago ({{ \Carbon\Carbon::parse($t->data_pagamento)->format('d/m/Y') }})
                                            </span>
                                        @else
                                            <span class="text-red-600 font-medium flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" /></svg>
                                                Pendente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @if (!$t->pago)
                                            <button wire:click="marcarComoPago({{ $t->id }})" 
                                                class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"><path d="M5 13l4 4L19 7" /></svg>
                                                Pagar
                                            </button>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center px-4 py-6 text-gray-500">Nenhum lançamento encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
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
            </div> <!-- fecha max-w-4xl -->
        </div> <!-- fecha justify-center -->
    </div>
</div>
