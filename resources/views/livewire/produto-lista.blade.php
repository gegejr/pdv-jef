
<div class="ml-64 pt-[72px] p-6 bg-gray-50 min-h-screen">
    <div class="flex">
        <x-sidebar />

        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <x-topbar />

            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <x-heroicon-o-cube class="w-6 h-6 text-indigo-500" />
                Lista de Produtos
            </h2>
            <!-- Ações principais -->
<!-- Ações principais -->
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <!-- Grupo de ações (esquerda) -->
    <div class="flex flex-wrap gap-3">
        <button wire:click="abrirMaisVendidos"
            class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow transition">
            <x-heroicon-o-fire class="w-5 h-5" />
            Mais Vendidos
        </button>

        <button wire:click="consultarFalta"
            class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow transition">
            <x-heroicon-o-exclamation-circle class="w-5 h-5" />
            Produtos em Falta
        </button>
    </div>

    <!-- Botão de exportar (direita) -->
    <a href="{{ route('produtos.exportar') }}" target="_blank"
        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow transition">
        <x-heroicon-o-document-arrow-down class="w-5 h-5" />
        Exportar Inventário
    </a>
</div>



            @if (session()->has('sucesso'))
                <div class="bg-green-100 text-green-800 p-3 rounded shadow mb-4">
                    {{ session('sucesso') }}
                </div>
            @endif
            
            <input type="text" wire:model.live="searchTerm"  placeholder="🔍 Buscar por nome..."
            class="w-full px-4 py-2 mb-6 rounded-lg border border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 transition" />

            <div class="mt-2 text-sm text-gray-600">
                Termo digitado: "{{ $searchTerm }}"
            </div>
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3 text-center">ID</th>
                            <th class="px-4 py-3 text-center">Imagem</th>
                            <th class="px-4 py-3 text-left">Nome</th>
                            <th class="px-4 py-3 text-left">Categoria</th>
                            <th class="px-4 py-3 text-left">Código de Barras</th>
                            <th class="px-4 py-3 text-left">Descrição</th>
                            <th class="px-4 py-3 text-right">Preço</th>
                            <th class="px-4 py-3 text-right">Desconto</th>
                            <th class="px-4 py-3 text-right">Estoque</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse($produtos as $produto)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-center text-gray-700">{{ $produto->id }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if ($produto->imagem)
                                        <img src="{{ asset('storage/' . $produto->imagem) }}" class="w-10 h-10 rounded object-cover mx-auto" />
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-900">
                                    {{ $produto->nome }}<p class="text-sm text-gray-600">Vendido {{ $produto->vendas_no_mes }} vezes este mês</p>
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ optional($produto->categoria)->nome ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $produto->codigo_barras }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ \Illuminate\Support\Str::limit($produto->descricao, 50) }}</td>
                                <td class="px-4 py-3 text-right text-green-600 font-semibold">R$ {{ number_format($produto->valor, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-blue-500">{{ number_format($produto->desconto_padrao, 2, ',', '.') }}%</td>
                                <td class="px-4 py-3 text-right font-semibold {{ $produto->estoque < 5 ? 'text-red-600' : 'text-gray-700' }}">
                                    {{ $produto->estoque }}
                                    @if ($produto->estoque < 5)
                                        <span class="block text-xs text-red-400 italic">Estoque baixo!</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center space-x-2">
                                    <button wire:click="editarProduto({{ $produto->id }})" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs shadow">
                                        <x-heroicon-o-pencil class="w-4 h-4" /> Editar
                                    </button>
                                    <button wire:click="excluir({{ $produto->id }})" class="inline-flex items-center gap-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs shadow">
                                        <x-heroicon-o-trash class="w-4 h-4" /> Excluir
                                    </button>
                                    <button wire:click="adicionarCarrinho({{ $produto->id }})" class="inline-flex items-center gap-1 px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs shadow">
                                        <x-heroicon-o-plus class="w-4 h-4" /> Adicionar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-6 text-center text-gray-500">Nenhum produto encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $produtos->links() }}
            </div>  
            <!-- Componentes adicionais (modais etc.) -->
        @includeWhen($produtoId, 'components.modal-editar-produto')
        @includeWhen($showMaisVendidos, 'components.modal-mais-vendidos')
        @includeWhen($showFalta, 'components.modal-produtos-falta')
        </div>
</div>