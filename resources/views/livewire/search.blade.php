<div>
    <div id="filter" class="flex gap-4">
        <label class="input input-bordered flex items-center gap-2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 16 16"
x              fill="currentColor"
              class="h-4 w-4 opacity-70">
              <path
                fill-rule="evenodd"
                d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                clip-rule="evenodd" />
            </svg>
            <input wire:model.live="searchTerm" type="text" class="grow" placeholder="Search" />
        </label>
    </div>
    <hr class="my-5">
    <div id="content" class="grid grid-cols-4 gap-4">
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
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $produto->nome }}</td>
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
    </div>
</div>