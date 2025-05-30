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

            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <circle cx="7" cy="21" r="1" />
                    <circle cx="17" cy="21" r="1" />
                </svg>
                Carrinho de Compras
            </h2>

            @if ($cliente_nome)
                <div class="mb-4 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded">
                    Cliente Selecionado: <strong>{{ $cliente_nome }}</strong>
                </div>
            @else
                <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded">
                    Nenhum cliente selecionado
                </div>
            @endif
            <!--
            <div class="mb-4">
                <button wire:click="toggleCampoBusca" class="inline-flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                    Abrir Venda
                </button>
            </div>
            -->
            @if($campo_visivel)
                <!-- Campo busca cliente + botão -->
                <div class="mt-4" wire:key="campo-busca-cliente">
                    <div class="flex space-x-2">
                        <input
                            type="text"
                            wire:model.live="busca_cliente"
                            wire:keydown.enter="Buscar cliente por nome ou telefone"
                            class="form-input w-full"
                            placeholder="Digite o nome do cliente"
                        >
                        <button
                            wire:click="abrirModalClientes"
                            class="bg-blue-600 text-white px-4 rounded hover:bg-blue-700"
                        >
                            Listar Clientes
                        </button>
                    </div>

                    @if(count($sugestoes_clientes))
                        <ul class="border rounded mt-2 bg-white max-h-40 overflow-y-auto">
                            @foreach($sugestoes_clientes as $cliente)
                                <li wire:key="cli-{{ $cliente['id'] }}"
                                    class="p-2 hover:bg-gray-200 cursor-pointer"
                                    wire:click="selecionarCliente({{ $cliente['id'] }})">
                                    {{ $cliente['nome'] }} – {{ $cliente['telefone'] }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Campo busca produto + botão -->
                <div class="relative mt-6" wire:key="campo-busca-produto">
                    <div class="flex space-x-2">
                        <input
                            type="text"
                            wire:model.live="searchTerm"
                            wire:keydown.enter.prevent="selecionarProduto"
                            class="form-input w-full"
                            placeholder="Digite o nome ou código de barras do produto..."
                        >
                        <button
                            wire:click="abrirModalProdutos"
                            class="bg-green-600 text-white px-4 rounded hover:bg-green-700"
                        >
                            Listar Produtos
                        </button>
                    </div>

                    @if(count($sugestoes))
                        <ul class="border rounded mt-2 bg-white max-h-40 overflow-y-auto absolute w-full z-10">
                            @foreach($sugestoes as $produto)
                                <li wire:key="prod-{{ $produto['id'] }}"
                                    class="p-2 hover:bg-gray-200 cursor-pointer"
                                    wire:click="selecionarProduto({{ $produto['id'] }})">
                                    {{ $produto['nome'] }} – R$ {{ number_format($produto['valor'], 2, ',', '.') }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <!-- Modal Clientes -->
            @if($modalClientesAberto)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                    <div class="bg-white rounded-lg shadow-lg w-72 max-h-60 overflow-y-auto p-4 relative">
                        <h3 class="text-base font-semibold mb-2">Selecione um Cliente</h3>
                        <button
                            wire:click="fecharModalClientes"
                            class="absolute top-1 right-2 text-gray-600 hover:text-gray-900 font-bold"
                            title="Fechar"
                        >&times;</button>

                        <!-- Campo de busca dentro do modal -->
                        <input
                            type="text"
                            wire:model.debounce.300ms="busca_modal_cliente"
                            class="form-input w-full mb-2 text-sm"
                            placeholder="Buscar cliente..."
                        >

                        <ul>
                            @foreach($todos_clientes as $cliente)
                                @if(str_contains(strtolower($cliente['nome']), strtolower($busca_modal_cliente)))
                                    <li class="p-2 hover:bg-gray-200 cursor-pointer text-sm"
                                        wire:click="selecionarCliente({{ $cliente['id'] }})">
                                        {{ $cliente['nome'] }} – {{ $cliente['telefone'] }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Modal Produtos -->
            @if($modalProdutosAberto)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                    <div class="bg-white rounded-lg shadow-lg w-[22rem] max-h-[80vh] overflow-y-auto p-4 relative">
                        <h3 class="text-lg font-semibold mb-2">Selecione um Produto</h3>
                        <button
                            wire:click="fecharModalProdutos"
                            class="absolute top-1 right-2 text-gray-600 hover:text-gray-900 font-bold"
                            title="Fechar"
                        >&times;</button>

                        <!-- Campo de busca -->
                        <input
                            type="text"
                            wire:model.live="searchTerm"
                            class="form-input w-full mb-3 text-sm"
                            placeholder="Buscar produto..."
                        >

                        <ul class="divide-y">
                            @foreach($todos_produtos as $produto)
                                @if(
                                        str_contains(strtolower($produto['nome']), strtolower($searchTerm)) ||
                                        str_contains((string)$produto['codigo_barras'], $searchTerm)
                                    )
                                        <li
                                            wire:click="selecionarProduto({{ $produto['id'] }})"
                                            class="p-3 hover:bg-gray-100 cursor-pointer text-sm"
                                        >
                                            <div class="font-semibold text-gray-800">{{ $produto['nome'] }}</div>
                                            <div class="text-gray-600 text-xs">
                                                Código: <span class="font-mono">{{ $produto['codigo_barras'] }}</span> |
                                                Estoque: <strong>{{ $produto['estoque'] }}</strong>
                                            </div>
                                            <div class="text-green-600 font-semibold text-sm mt-1">
                                                R$ {{ number_format($produto['valor'], 2, ',', '.') }}
                                            </div>
                                        </li>
                                    @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif



            @if (count($carrinho) > 0)
                <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2">Código de Barras</th>
                            <th class="px-4 py-2">Produto</th>
                            <th class="px-4 py-2">Preço</th>
                            <th class="px-4 py-2">Qtd</th>
                            <th class="px-4 py-2">Total</th>
                            <th class="px-4 py-2 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($carrinho as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $item['produto']->codigo_barras }}</td>
                                <td class="px-4 py-2">{{ $item['produto']->nome }}</td>
                                <td class="px-4 py-2">R$ {{ number_format($item['produto']->valor, 2, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    <input 
                                        type="number" min="1"
                                        wire:change="atualizarQuantidade({{ $item['produto']->id }}, $event.target.value)"
                                        value="{{ $item['quantidade'] }}"
                                        class="w-20 border-gray-300 rounded p-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                </td>
                                <td class="px-4 py-2">R$ {{ number_format($item['valor_total'], 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-center">
                                    <button wire:click="removerItem({{ $item['produto']->id }})"
                                        class="inline-flex items-center text-red-600 hover:text-red-800 font-medium text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
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
                    <button wire:click="fecharVenda" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 shadow-md transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h13m-4 4l4-4m0 0l-4-4"/>
                        </svg>
                        Finalizar Venda
                    
                </div>
                @else
                    <div class="text-center p-4">Carrinho vazio</div>
            @endif
        </div> <!-- Fim do conteúdo principal -->
</div> <!-- Fim do flex -->
    <script>
    function produtoAutocomplete() {
        return {
            aberto: false,
            selecionado: 0,
            sugestoes: @json($sugestoes),
            init() {
                this.aberto = this.sugestoes.length > 0;

                window.addEventListener('sugestoes-atualizadas', event => {
                    this.sugestoes = event.detail.sugestoes;
                    this.selecionado = 0;
                    this.aberto = this.sugestoes.length > 0;
                });
            },
            selecionarComEnter(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    if (this.sugestoes.length > 0) {
                        this.selecionarProduto(this.sugestoes[this.selecionado].id);
                    } else {
                        const input = event.target.value;
                        @this.selecionarProduto(input);
                    }
                    this.aberto = false;
                }
            },
            selecionarProduto(id) {
                @this.selecionarProduto(id);
                this.aberto = false;
            }
        }
    }
</script>
</div> <!-- Fim da ml-64 pt-[72px] p-6 -->