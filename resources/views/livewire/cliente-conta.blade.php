@section('title', 'Conta Clientes')
<div class="ml-64 pt-[72px] p-6 bg-gray-100 min-h-screen">
    <x-sidebar />

    <div class="flex-1 ml-64 md:ml-0 transition-all duration-300">
        <x-topbar />
        <div class="p-6 space-y-6 bg-white shadow rounded-xl">
        <h2 class="text-2xl font-bold text-gray-800">Clientes com Vendas em Conta</h2>

        {{-- Contas Pendentes --}}
        <h3 class="text-lg font-semibold text-gray-800">Contas Pendentes</h3>
        <div wire:loading.class="opacity-50 pointer-events-none relative">
            <table class="w-full text-sm text-center border rounded shadow overflow-hidden">
            <thead class="bg-gray-200 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="p-2 border">Data</th>
                    <th class="p-2 border">Data</th>
                    <th class="p-2 border">Cliente</th>
                    <th class="p-2 border">Telefone</th>
                    <th class="p-2 border">Valor</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contasPendentes as $venda)
                    @php
                        $cliente = $venda->cliente;
                        $pagamentoConta = $venda->pagamentos->firstWhere('tipo', 'conta');
                    @endphp
                    <tr class="border-t">
                        <td class="p-2 border">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-2 border">{{ $cliente->nome }}</td>
                        <td class="p-2 border">{{ $cliente->telefone }}</td>
                        <td class="p-2 border">R$ {{ number_format($pagamentoConta->valor, 2, ',', '.') }}</td>
                        <td class="p-2 border">Pendente</td>
                        <td class="p-2 border">
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
        {{-- Links de paginação fora da tabela --}}
        <div class="mt-4">
            {{ $contasPendentes->links() }}
        </div>
        
        {{-- Contas Pagas --}}
        <h3 class="text-lg font-semibold text-gray-800">Contas Pagas</h3>
        <table class="w-full text-sm text-center border rounded shadow overflow-hidden">
            <thead class="bg-gray-200 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="p-2 border">Data</th>
                    <th class="p-2 border">Cliente</th>
                    <th class="p-2 border">Telefone</th>
                    <th class="p-2 border">Valor</th>
                    <th class="p-2 border">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contasPagas as $venda)
                    @php
                        $cliente = $venda->cliente;
                        $pagamentoConta = $venda->pagamentos->firstWhere('tipo', 'conta');
                    @endphp
                    <tr class="border-t">
                        <td class="p-2 border">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-2 border">{{ $cliente->nome }}</td>
                        <td class="p-2 border">{{ $cliente->telefone }}</td>
                        <td class="p-2 border">R$ {{ number_format($pagamentoConta->valor, 2, ',', '.') }}</td>
                        <td class="p-2 border">Pago</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">Nenhuma conta paga encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $contasPagas->links() }}
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
        <!-- Modal -->
        @if($modalOpen)
        <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-md w-96">
                <h3 class="text-lg font-semibold mb-4">Confirmar Pagamento</h3>

                <p><strong>Cliente:</strong> {{ $clienteNome }}</p>
                <p><strong>Valor:</strong> R$ {{ number_format($valor, 2, ',', '.') }}</p>

                <div class="mt-4">
                    <h3 class="text-lg font-semibold mb-2">Método de Pagamento</h3>
                    @if (session()->has('error'))
                        <div class="bg-red-100 text-red-700 text-sm p-2 rounded mb-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session()->has('message'))
                        <div class="bg-green-100 text-green-700 text-sm p-2 rounded mb-3">
                            {{ session('message') }}
                        </div>
                    @endif
                    <select wire:model="metodo_pagamento" class="border p-2 rounded w-full mb-2">
                        <option value="" selected>Selecione...</option>  {{-- value vazio, não “#” --}}
                        <option value="dinheiro">Dinheiro</option>
                        <option value="debito">Débito</option>
                        <option value="credito">Crédito</option>
                        <option value="pix">Pix</option>
                    </select>

                    <input  type="number"
                            step="0.01"
                            wire:model="valor_pagamento"
                            placeholder="Valor"
                            class="border p-2 rounded w-full mb-2">
                    <button wire:click="adicionarPagamento"
                            class="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300 w-full">
                        + Adicionar Pagamento
                    </button>
                </div>

                <!-- Lista de pagamentos adicionados -->
                @if(count($pagamentos_adicionados) > 0)
                    <div class="mt-4">
                        <h4 class="font-semibold">Pagamentos Adicionados:</h4>
                        <ul class="list-disc list-inside text-sm text-gray-700">
                            @foreach($pagamentos_adicionados as $p)
                                <li>{{ ucfirst($p['tipo']) }} - R$ {{ number_format($p['valor'], 2, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    </div>
@endif
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
</div>
