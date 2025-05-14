<div x-show="clienteModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-3xl">
        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-semibold">Selecionar Cliente</h2>
            <button @click="clienteModal = false" class="text-gray-500 hover:text-black text-2xl">&times;</button>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Nome</th>
                    <th class="px-4 py-2 text-left">CPF/CNPJ</th>
                    <th class="px-4 py-2 text-center">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $cliente->nome }}</td>
                        <td class="px-4 py-2">{{ $cliente->cpf_cnpj }}</td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="$emit('clienteSelecionado', {{ $cliente->id }})" @click="clienteModal = false"
                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                Selecionar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
