<div class="ml-64 pt-[72px] p-6">
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
                        <th class="p-2 border text-center">ID</th>
                        <th class="p-2 border text-center">Imagem</th>
                        <th class="p-2 border text-center">Nome</th>
                        <th class="p-2 border text-center">Codigo de barras</th>
                        <th class="p-2 border text-center">Preço</th>
                        <th class="p-2 border text-center">Estoque</th>
                        <th class="p-2 border text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produtos as $produto)
                        <tr class="border-t">
                            <td class="p-2 border text-center">{{ $produto->id }}</td>
                            <td class="p-2 border text-center">
                                @if ($produto->imagem)
                                    <img src="{{ asset('storage/' . $produto->imagem) }}" class="w-12 h-12 object-cover rounded mx-auto">
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-2 border text-center">{{ $produto->nome }}</td>
                            <td class="p-2 border text-center">{{ $produto->codigo_barras }}</td>
                            <td class="p-2 border text-center">R$ {{ number_format($produto->valor, 2, ',', '.') }}</td>
                            <td class="p-2 border text-center">
                                @if ($produto->estoque < 5)
                                    <span class="text-red-600 font-semibold">
                                        {{ $produto->estoque }} <br>
                                        <small>Estoque baixo! <br> Faça pedido</small>
                                    </span>
                                @else
                                    {{ $produto->estoque }}
                                @endif
                            </td>
                            <td class="p-2 border text-center">
                                <div class="flex justify-center items-center space-x-2">
                                    <button wire:click="editarProduto({{ $produto->id }})" class="flex items-center p-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 15H7v-2l8-8z" />
                                        </svg>
                                        Editar
                                    </button>

                                    <button wire:click="excluir({{ $produto->id }})" class="flex items-center p-1 text-sm bg-red-500 text-white rounded hover:bg-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7L5 7M6 7V19a2 2 0 002 2h8a2 2 0 002-2V7m-5 4v6m-4-6v6m1-10V4a1 1 0 011-1h2a1 1 0 011 1v2" />
                                        </svg>
                                        Excluir
                                    </button>

                                    <button wire:click="adicionarCarrinho({{ $produto->id }})" class="flex items-center p-1 text-sm bg-green-500 text-white rounded hover:bg-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.293 2.707a1 1 0 00.707 1.707h11a1 1 0 00.707-1.707L17 13M7 13V6h.01M17 13V6h.01" />
                                        </svg>
                                        Adicionar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-4">Nenhum produto encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-4">
                {{ $produtos->links() }}
            </div>
        </div>

        @if($produtoId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded shadow-xl w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Editar Produto</h2>
                <form wire:submit.prevent="salvar">
                    <div class="mb-4">
                        <label>Nome</label>
                        <input type="text" wire:model.defer="nome" class="w-full border p-2 rounded" />
                        @error('nome') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label>Código de Barras</label>
                        <input type="text" wire:model.defer="codigo_barras" class="w-full border p-2 rounded" />
                    </div>

                    <div class="mb-4">
                        <label>Descrição</label>
                        <textarea wire:model.defer="descricao" class="w-full border p-2 rounded"></textarea>
                    </div>

                    <div class="mb-4">
                        <label>Valor</label>
                        <input type="number" step="0.01" wire:model.defer="valor" class="w-full border p-2 rounded" />
                    </div>

                    <div class="mb-4">
                        <label>Estoque</label>
                        <input type="number" wire:model.defer="estoque" class="w-full border p-2 rounded" />
                    </div>

                    <div class="mb-4">
                        <label>Desconto Padrão</label>
                        <input type="number" step="0.01" wire:model.defer="desconto_padrao" class="w-full border p-2 rounded" />
                    </div>

                    <div class="mb-4">
                        <label>Imagem</label>
                        <input type="file" wire:model="imagem" class="w-full" />
                        @error('imagem') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        @if ($imagem_existente && !$imagem)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $imagem_existente) }}" class="w-24 h-24 object-cover rounded">
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Salvar</button>
                        <button type="button" wire:click="fecharModal" class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>