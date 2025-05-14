<div class="ml-64 pt-[72px] p-6">
    <x-sidebar />

    <div class="p-6">
        <x-topbar />

        <h2 class="text-xl font-bold mb-4">Clientes com Vendas em Conta</h2>

        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-left">Cliente</th>
                    <th class="px-4 py-2">Telefone</th>
                    <th class="px-4 py-2">Valor</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vendas as $venda)
                    @php
                        $cliente = $venda->cliente;
                        $pagamentoConta = $venda->pagamentos->firstWhere('tipo', 'conta');
                        $contaPaga = $pagamentoConta && $pagamentoConta->pago;
                    @endphp
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $cliente->nome }}</td>
                        <td class="px-4 py-2">{{ $cliente->telefone }}</td>
                        <td class="px-4 py-2">R$ {{ number_format($pagamentoConta->valor, 2, ',', '.') }}</td>
                        <td class="px-4 py-2 font-bold {{ $contaPaga ? 'text-green-600' : 'text-red-600' }}">
                            {{ $contaPaga ? 'Pago' : 'Pendente' }}
                        </td>
                        <td class="px-4 py-2">
                            @if (!$contaPaga)
                                <button wire:click="openModal({{ $venda->id }})"
                                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                    Marcar como Pago
                                </button>
                            @else
                                <span class="text-gray-500">✓</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">Nenhuma venda em conta encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Modal -->
        @if($modalOpen)
        <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-md w-96">
                <h3 class="text-lg font-semibold mb-4">Confirmar Pagamento</h3>

                <p><strong>Cliente:</strong> {{ $clienteNome }}</p>
                <p><strong>Valor:</strong> R$ {{ number_format($valor, 2, ',', '.') }}</p>

                <div class="mt-4">
                    <label for="metodo_pagamento" class="block text-sm font-medium text-gray-700">Método de Pagamento</label>
                    <select wire:model="metodo_pagamento" id="metodo_pagamento" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Selecione...</option>
                        <option value="dinheiro">Dinheiro</option>
                        <option value="cartao">Cartão</option>
                        <option value="transferencia">Transferência</option>
                    </select>

                    @error('metodo_pagamento') 
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <button wire:click="confirmarPagamento"
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Confirmar Pagamento
                    </button>
                    <button wire:click="fecharModal"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 ml-4">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
