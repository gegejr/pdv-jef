<div class="ml-64 pt-[72px] p-6">
    <div class="flex">
        <!-- Menu lateral -->
        <x-sidebar />

        <!-- Conteúdo principal -->
        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <!-- Topbar -->
            <x-topbar />

            <!-- Mensagens de feedback -->
            @if (session()->has('message'))
                <div 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-init="setTimeout(() => show = false, 3000)" 
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4"
                >
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-init="setTimeout(() => show = false, 3000)" 
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4"
                >
                    {{ session('error') }}
                </div>
            @endif
            @if (!$caixaAberto)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md text-center">
                        <h2 class="text-xl font-bold text-red-600 mb-4">Caixa fechado</h2>
                        <p class="mb-4 text-gray-700">Você precisa abrir o caixa para iniciar uma nova venda.</p>

                        <div class="mb-3 text-left">
                            <label class="block text-sm font-semibold">Nome do Caixa</label>
                            <input type="text" wire:model="nome" class="w-full border rounded p-2" placeholder="Ex: Caixa Principal" />
                        </div>
                        <div class="mb-4 text-left">
                            <label class="block text-sm font-semibold">Valor Inicial</label>
                            <input type="number" wire:model="valor_inicial" class="w-full border rounded p-2" />
                        </div>

                        <button wire:click="abrirCaixa" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full">
                            Abrir Caixa
                        </button>
                    </div>
                </div>
            @endif

            <h2 class="text-xl font-bold mb-4">Carrinho de Compras</h2>
            @if ($cliente_nome)
                <div class="mb-4 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded">
                    Cliente Selecionado: <strong>{{ $cliente_nome }}</strong>
                </div>
            @else
                <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded">
                    Nenhum cliente selecionado
                </div>
            @endif
            <div class="mb-4">
                <button wire:click="toggleCampoBusca" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Abrir Venda
                </button>
                    @if ($campo_visivel)
                        <!-- Campo de busca de cliente -->
                        <div class="mt-4">
                            <input 
                                type="text"
                                wire:model.debounce.10ms="busca_cliente"
                                placeholder="Buscar cliente por nome ou telefone"
                                class="border p-2 rounded w-full"
                            />
                            @if (!empty($sugestoes_clientes))
                                <ul class="border rounded mt-2 bg-white max-h-40 overflow-y-auto">
                                    @foreach ($sugestoes_clientes as $cliente)
                                        <li 
                                            class="p-2 hover:bg-gray-200 cursor-pointer"
                                            wire:click="selecionarCliente({{ $cliente['id'] }})"
                                        >
                                            {{ $cliente['nome'] }} - {{ $cliente['telefone'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

            @if ($campo_visivel)
                <div class="relative mt-2"
                    x-data="{
                        aberto: true,
                        selecionado: 0,
                        total: {{ count($sugestoes) }},
                        selecionarComEnter(e) {
                            if (e.key === 'ArrowDown') {
                                this.selecionado = (this.selecionado + 1) % this.total;
                                e.preventDefault();
                            } else if (e.key === 'ArrowUp') {
                                this.selecionado = (this.selecionado - 1 + this.total) % this.total;
                                e.preventDefault();
                            } else if (e.key === 'Enter') {
                                e.preventDefault();
                                const input = e.target.value;
                                if (this.total > 0) {
                                    // Se sugestões visíveis, usa a seleção
                                    $wire.selecionarProduto({{ json_encode($sugestoes) }}[this.selecionado].id);
                                } else {
                                    // Se nenhuma sugestão, tenta buscar pelo texto digitado
                                    $wire.selecionarProduto(input);
                                }
                                this.aberto = false;
                            }
                        }
                    }"
                >
                    <input 
                        type="text"
                        wire:model.debounce.10ms="busca_produto"
                        placeholder="Digite código ou nome"
                        @focus="aberto = true"
                        @click.away="aberto = false"
                        @keydown="selecionarComEnter"
                        class="border p-2 rounded w-full" 
                    />

                    @if (!empty($sugestoes) && $busca_produto)
                        <ul 
                            class="absolute bg-white border rounded shadow mt-1 w-full z-10 max-h-60 overflow-y-auto"
                            x-show="aberto"
                        >
                            @foreach ($sugestoes as $index => $produto)
                                <li 
                                    wire:click="selecionarProduto({{ $produto['id'] }})"
                                    :class="{ 'bg-gray-200': selecionado === {{ $index }} }"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                >
                                    {{ $produto['nome'] }} - R$ {{ number_format($produto['valor'], 2, ',', '.') }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            </div>


            @if (count($carrinho) > 0)
                <table class="w-full table-auto border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Produto</th>
                            <th class="p-2 border">Preço</th>
                            <th class="p-2 border">Quantidade</th>
                            <th class="p-2 border">Total</th>
                            <th class="p-2 border">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carrinho as $item)
                            <tr>
                                <td class="p-2 border">{{ $item['produto']->nome }}</td>
                                <td class="p-2 border">R$ {{ number_format($item['produto']->valor, 2, ',', '.') }}</td>
                                <td class="p-2 border">
                                    <input 
                                        type="number" min="1" class="border rounded px-2 py-1 w-20"
                                        wire:change="atualizarQuantidade({{ $item['produto']->id }}, $event.target.value)"
                                        value="{{ $item['quantidade'] }}">
                                </td>
                                <td class="p-2 border">R$ {{ number_format($item['valor_total'], 2, ',', '.') }}</td>
                                <td class="p-2 border text-center">
                                    <button wire:click="removerItem({{ $item['produto']->id }})"
                                            class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                        Remover
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 text-right">
                    <strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong>
                </div>

                <!-- Seleção de pagamento -->
<!-- Pagamentos múltiplos -->
<div class="mt-4">
    <h3 class="text-lg font-semibold mb-2">Métodos de Pagamento</h3>
    
    @foreach ($pagamentos as $index => $pagamento)
        <div class="flex items-center gap-2 mb-2">
            <select wire:model="pagamentos.{{ $index }}.tipo" class="border p-2 rounded w-1/2">
                <option value="">Selecione</option>
                <option value="dinheiro">Dinheiro</option>
                <option value="debito">Débito</option>
                <option value="credito">Crédito</option>
                <option value="pix">Pix</option>
                <option value="conta">Conta</option>
            </select>

            <input 
                type="number" 
                wire:model="pagamentos.{{ $index }}.valor" 
                class="border p-2 rounded w-1/2"
                min="0" step="0.01"
                placeholder="Valor"
            >

            <button 
                wire:click="removerPagamento({{ $index }})" 
                class="text-red-500 hover:text-red-700 font-bold"
                title="Remover"
            >
                &times;
            </button>
        </div>
    @endforeach

    <button wire:click="adicionarPagamento" class="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300">
        + Adicionar Pagamento
    </button>

    <div class="mt-2 text-sm text-gray-600">
        Total informado: R$ {{ number_format(array_sum(array_column($pagamentos, 'valor')), 2, ',', '.') }}
    </div>
</div>

                <!-- Desconto Total -->
                <div class="mt-4">
                    <h3 class="text-lg font-semibold mb-2">Desconto Total</h3>
                    <input 
                        type="number" wire:model="desconto_total" 
                        class="border p-2 rounded w-full" 
                        min="0" step="0.01" 
                    />
                </div>

                <!-- Total a Pagar -->
                <div class="mt-4">
                    <h3 class="text-lg font-semibold mb-2">
                        Total a Pagar: R$ {{ number_format($total - $desconto_total, 2, ',', '.') }}
                    </h3>
                </div>

                <!-- Botão para Finalizar a Venda -->
                <div class="mt-4 text-right">
                    <button wire:click="fecharVenda" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Finalizar Venda
                    </button>
                </div>
                @else
                    <div class="text-center p-4">Carrinho vazio</div>
            @endif
        </div> <!-- Fim do conteúdo principal -->
    </div> <!-- Fim do flex -->
</div> <!-- Fim da ml-64 pt-[72px] p-6 -->