<div class="flex">
    <!-- Menu lateral -->
    <x-sidebar />
    

    <!-- Main Content Area -->
    <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
    <x-topbar />
        <h2 class="text-xl font-bold mb-4">Lista de Produtos</h2>

        @if (session()->has('sucesso'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                {{ session('sucesso') }}
            </div>
        @endif

        <input type="text" wire:model.debounce.500ms="pesquisa" placeholder="Buscar por nome..."
            class="border p-2 rounded w-full mb-4" />

        <table class="w-full table-auto border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Imagem</th>
                    <th class="p-2 border">Nome</th>
                    <th class="p-2 border">Preço</th>
                    <th class="p-2 border">Estoque</th>
                    <th class="p-2 border">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produtos as $produto)
                    <tr class="border-t">
                        <td class="p-2 border">{{ $produto->id }}</td>
                        <td class="p-2 border">
                            @if ($produto->imagem)
                                <img src="{{ asset('storage/' . $produto->imagem) }}" class="w-12 h-12 object-cover rounded">
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-2 border">{{ $produto->nome }}</td>
                        <td class="p-2 border">R$ {{ number_format($produto->valor, 2, ',', '.') }}</td>
                        <td class="p-2 border">{{ $produto->estoque }}</td>
                        <td class="p-2 border space-x-2">
                            <a href="#" class="text-blue-600 hover:underline" wire:click="$emit('editarProduto', {{ $produto->id }})">Editar</a>
                            <button wire:click="excluir({{ $produto->id }})" class="text-red-600 hover:underline">Excluir</button>
                            <button wire:click="adicionarCarrinho({{ $produto->id }})" class="text-green-600 hover:underline">Adicionar ao Carrinho</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-4">Nenhum produto encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $produtos->links() }}
        </div>
    </div>
</div>
