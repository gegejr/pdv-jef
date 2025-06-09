<div class="max-w-4xl mx-auto p-4">
    <div class="flex">
    <!-- Menu lateral -->
    <div>
        <x-sidebar class="no-print" />
    </div>
    <x-topbar class="no-print" />
    <!-- Conteúdo principal -->
    <div class="flex-1 flex justify-center items-start mt-24 transition-all duration-300">
    <div class="w-full max-w-4xl">
      

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <!-- Formulário de Lançamento -->
        <form wire:submit.prevent="salvar" class="mb-6 space-y-4 bg-white p-6 rounded shadow">

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

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Salvar</button>
        </form>

        <!-- Filtros -->
        <div class="mb-4 space-x-2">
            <button wire:click="$set('filtro', 'todos')" class="px-3 py-1 rounded {{ $filtro === 'todos' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Todos</button>
            <button wire:click="$set('filtro', 'pagar')" class="px-3 py-1 rounded {{ $filtro === 'pagar' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">A Pagar</button>
            <button wire:click="$set('filtro', 'receber')" class="px-3 py-1 rounded {{ $filtro === 'receber' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">A Receber</button>
        </div>

        <!-- Listagem -->
        <table class="w-full border-collapse border border-gray-300 text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-3 py-1">Descrição</th>
                    <th class="border border-gray-300 px-3 py-1">Tipo</th>
                    <th class="border border-gray-300 px-3 py-1">Valor</th>
                    <th class="border border-gray-300 px-3 py-1">Vencimento</th>
                    <th class="border border-gray-300 px-3 py-1">Categoria</th>
                    <th class="border border-gray-300 px-3 py-1">Status</th>
                    <th class="border border-gray-300 px-3 py-1">Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $t)
                    <tr class="{{ $t->pago ? 'bg-green-50' : '' }}">
                        <td class="border border-gray-300 px-3 py-1">{{ $t->descricao }}</td>
                        <td class="border border-gray-300 px-3 py-1 capitalize">{{ $t->tipo == 'pagar' ? 'Pagar' : 'Receber' }}</td>
                        <td class="border border-gray-300 px-3 py-1">R$ {{ number_format($t->valor, 2, ',', '.') }}</td>
                        <td class="border border-gray-300 px-3 py-1">{{ \Carbon\Carbon::parse($t->data_vencimento)->format('d/m/Y') }}</td>
                        <td class="border border-gray-300 px-3 py-1">{{ $t->categoria ?? '-' }}</td>
                        <td class="border border-gray-300 px-3 py-1">
                            @if ($t->pago)
                                <span class="text-green-700 font-semibold">Pago em {{ \Carbon\Carbon::parse($t->data_pagamento)->format('d/m/Y') }}</span>
                            @else
                                <span class="text-red-600 font-semibold">Pendente</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 px-3 py-1">
                            @if (!$t->pago)
                                <button wire:click="marcarComoPago({{ $t->id }})" class="text-white bg-green-600 px-2 py-1 rounded hover:bg-green-700">Marcar como Pago</button>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Nenhum lançamento encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
            </div> <!-- fecha max-w-4xl -->
</div> <!-- fecha flex justify-center -->
    </div>
</div>
