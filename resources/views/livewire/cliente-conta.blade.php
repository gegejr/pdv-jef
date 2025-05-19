<div class="ml-64 pt-[72px] p-6">
    <x-sidebar />

    <div class="p-6">
        <x-topbar />

        <h2 class="text-xl font-bold mb-4">Clientes com Vendas em Conta</h2>

        {{-- Contas Pendentes --}}
        <h3 class="text-lg font-semibold mb-2">Contas Pendentes</h3>
        <table class="w-full table-auto border mb-8">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-center text-sm text-gray-700">Data</th>
                    <th class="px-4 py-2 text-center text-sm text-gray-700">Cliente</th>
                    <th class="px-4 py-2 text-center text-sm text-gray-700">Telefone</th>
                    <th class="px-4 py-2 text-center text-sm text-gray-700">Valor</th>
                    <th class="px-4 py-2 text-center text-sm text-gray-700">Status</th>
                    <th class="px-4 py-2 text-center text-sm text-gray-700">Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contasPendentes as $venda)
                    @php
                        $cliente = $venda->cliente;
                        $pagamentoConta = $venda->pagamentos->firstWhere('tipo', 'conta');
                    @endphp
                    <tr class="border-t">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $cliente->nome }}</td>
                        <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $cliente->telefone }}</td>
                        <td class="px-4 py-2 text-center text-sm text-gray-700">R$ {{ number_format($pagamentoConta->valor, 2, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center text-sm font-bold text-red-600">Pendente</td>
                        <td class="px-4 py-2">
                            <button wire:click="openModal({{ $venda->id }})"
                                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                Marcar como Pago
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">Nenhuma conta pendente encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- Contas Pagas --}}
        <h3 class="text-lg font-semibold mb-2">Contas Pagas</h3>
        <table class="w-full table-auto border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-center text-sm text-gray-700">Data</th>
                    <th class="px-4 py-2 text-center text-sm text-gray-700">Cliente</th>
                    <th class="px-4 py-2 text-center text-sm text-gray-700">Telefone</th>
                    <th class="px-4 py-2 text-center text-sm text-gray-700">Valor</th>
                    <th class="px-4 py-2 text-center text-sm text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contasPagas as $venda)
                    @php
                        $cliente = $venda->cliente;
                        $pagamentoConta = $venda->pagamentos->firstWhere('tipo', 'conta');
                    @endphp
                    <tr class="border-t">
                        <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $cliente->nome }}</td>
                        <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $cliente->telefone }}</td>
                        <td class="px-4 py-2 text-center text-sm text-gray-700">R$ {{ number_format($pagamentoConta->valor, 2, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center text-sm font-bold text-green-600">Pago</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">Nenhuma conta paga encontrada.</td></tr>
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
                    <h3 class="text-lg font-semibold mb-2">Método de Pagamento</h3>
                    <select wire:model="metodo_pagamento" class="border p-2 rounded w-full">
                        <option value="#" selected>Selecione um método de Pagamento</option>
                        <option value="dinheiro">Dinheiro</option>
                        <option value="debito">Débito</option>
                        <option value="credito">Crédito</option>
                        <option value="pix">Pix</option>
                    </select>
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
