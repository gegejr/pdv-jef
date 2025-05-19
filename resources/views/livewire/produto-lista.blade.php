
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

            <table class="min-w-full divide-y divide-gray-200 border rounded-lg overflow-hidden shadow-sm">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">ID</th>
            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Imagem</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Categoria</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Código de Barras</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Preço</th>
            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Desconto %</th>
            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Estoque</th>
            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Ações</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($produtos as $produto)
            <tr class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $produto->id }}</td>
                <td class="px-4 py-2 text-center">
                    @if ($produto->imagem)
                        <img src="{{ asset('storage/' . $produto->imagem) }}" alt="Imagem do produto" class="w-12 h-12 object-cover rounded mx-auto" />
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-2 text-left text-sm font-semibold text-gray-900">{{ $produto->nome }}</td>
                <td class="px-4 py-2 text-left text-sm font-semibold text-gray-900">
                    {{ optional($produto->categoria)->nome ?? '—' }}
                </td>
                <td class="px-4 py-2 text-left text-sm text-gray-700">{{ $produto->codigo_barras }}</td>
                <td class="px-4 py-2 text-left text-sm text-gray-600">
                    {{ \Illuminate\Support\Str::limit($produto->descricao, 50) }}
                </td>
                <td class="px-4 py-2 text-right text-sm text-green-600 font-semibold">
                    R$ {{ number_format($produto->valor, 2, ',', '.') }}
                </td>
                <td class="px-4 py-2 text-right text-sm text-blue-600 font-semibold">
                    {{ number_format($produto->desconto_padrao, 2, ',', '.') }}%
                </td>
                <td class="px-4 py-2 text-right text-sm font-semibold
                    {{ $produto->estoque < 5 ? 'text-red-600' : 'text-gray-700' }}">
                    {{ $produto->estoque }}
                    @if ($produto->estoque < 5)
                        <br><small class="text-xs text-red-500 italic">Estoque baixo!</small>
                    @endif
                </td>
                <td class="px-4 py-2 text-center space-x-1 whitespace-nowrap">
                    <button wire:click="editarProduto({{ $produto->id }})" 
                        class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-500 hover:bg-blue-600 text-white rounded">
                        Editar
                    </button>
                    <button wire:click="excluir({{ $produto->id }})" 
                        class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-500 hover:bg-red-600 text-white rounded">
                        Excluir
                    </button>
                    <button wire:click="adicionarCarrinho({{ $produto->id }})" 
                        class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-500 hover:bg-green-600 text-white rounded">
                        Adicionar
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="px-4 py-6 text-center text-gray-500">Nenhum produto encontrado.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $produtos->links() }}
</div>

@if($produtoId)
<div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 overflow-y-auto">
    <div class="bg-white p-6 rounded shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-bold mb-6 text-center">Editar Produto</h2>
        <form wire:submit.prevent="salvar">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-1">Nome</label>
                    <input type="text" wire:model.defer="nome" class="w-full border p-2 rounded" />
                    @error('nome') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-1">Código de Barras</label>
                    <input type="text" wire:model.defer="codigo_barras" class="w-full border p-2 rounded" />
                </div>

                <div class="col-span-2">
                    <label class="block mb-1">Descrição</label>
                    <textarea wire:model.defer="descricao" class="w-full border p-2 rounded"></textarea>
                </div>

                <div>
                    <label class="block mb-1">Valor</label>
                    <input type="number" step="0.01" wire:model.defer="valor" class="w-full border p-2 rounded" />
                </div>

                <div>
                    <label class="block mb-1">Estoque</label>
                    <input type="number" wire:model.defer="estoque" class="w-full border p-2 rounded" />
                </div>

                <div>
                    <label class="block mb-1">Desconto Padrão</label>
                    <input type="number" step="0.01" wire:model.defer="desconto_padrao" class="w-full border p-2 rounded" />
                </div>

                <div>
                    <label class="block mb-1">Imagem</label>
                    <input type="file" wire:model="imagem" class="w-full" />
                    @error('imagem') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    @if ($imagem_existente && !$imagem)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $imagem_existente) }}" class="w-24 h-24 object-cover rounded">
                        </div>
                    @endif
                </div>
            </div>

            <hr class="my-6 border-gray-300">

            <div x-data="{ mostrarPerda: @entangle('registrar_perda') }">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" x-model="mostrarPerda" class="form-checkbox">
                    <span class="font-medium">Registrar Perda</span>
                </label>
                <p class="text-gray-500 text-sm mt-1">
                    Marque para registrar perda de estoque. Valor atual: 
                    <span x-text="mostrarPerda ? 'SIM' : 'NÃO'"></span>
                </p>

                <div x-show="mostrarPerda" class="grid grid-cols-1 gap-4 mt-4">
                    <div class="mb-4">
                        <label>Quantidade Perdida</label>
                        <input type="number" wire:model="quantidade_perda" class="w-full border p-2 rounded" />
                    </div>
                    <div class="mb-4">
                        <label>Motivo da Perda</label>
                        <select wire:model="motivo_perda" class="w-full border p-2 rounded">
                            <option value="">Selecione</option>
                            <option value="quebra">Quebra</option>
                            <option value="descarte">Descarte</option>
                            <option value="perda">Perda</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="flex justify-end space-x-4 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                    💾 Salvar
                </button>
                <button type="button" wire:click="fecharModal" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded shadow">
                    ❌ Cancelar
                </button>
            </div>
        </form>
    </div>
</div>
@endif
    </div>
</div>