<div class="ml-64 pt-[72px] p-6 bg-gray-50 min-h-screen">
    <div class="flex">
        <x-sidebar />

        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <x-topbar />

            <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-xl space-y-6">
                <form wire:submit.prevent="salvar" class="space-y-6">
                    @csrf

                    <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">
                        {{ $produto_id ? 'Editar Produto' : 'Cadastrar Produto' }}
                    </h2>

                    @if (session()->has('sucesso'))
                        <div class="bg-green-100 text-green-800 p-3 rounded">
                            {{ session('sucesso') }}
                        </div>
                    @endif

                    {{-- Seção 1: Informações principais --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Informações principais</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome *</label>
                                <input type="text" wire:model="nome" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" />
                                @error('nome') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Código de Barras</label>
                                <input type="text" wire:model="codigo_barras" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">SKU (opcional)</label>
                                <input type="text" wire:model="sku" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoria *</label>
                                <select wire:model="categoria_id" class="w-full border p-2 rounded">
                                    <option value="">Selecione</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria['id'] }}">{{ $categoria['nome'] }}</option>
                                    @endforeach
                                </select>
                                @error('categoria_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Unidade de Medida</label>
                                <select wire:model="unidade_medida" class="w-full border p-2 rounded">
                                    <option value="un">Unidade</option>
                                    <option value="kg">Kg</option>
                                    <option value="l">Litro</option>
                                    <option value="cx">Caixa</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select wire:model="status" class="w-full border p-2 rounded">
                                    <option value="ativo">Ativo</option>
                                    <option value="inativo">Inativo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Seção 2: Estoque e preço --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Estoque e preço</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Valor *</label>
                                <input type="number" step="0.01" wire:model="valor" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" />
                                @error('valor') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Desconto Padrão (%)</label>
                                <input type="number" step="0.01" wire:model="desconto_padrao" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estoque *</label>
                                <input type="number" wire:model="estoque" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" />
                                @error('estoque') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Seção 3: Descrição e imagem --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Descrição e imagem</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Descrição</label>
                                <textarea wire:model="descricao" rows="4" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Imagem</label>
                                <input type="file" wire:model="imagem" class="w-full border rounded p-1" />
                                @error('imagem') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                                @if ($imagem_existente && !$imagem)
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-500 mb-1">Imagem atual:</p>
                                        <img src="{{ asset('storage/' . $imagem_existente) }}" class="w-28 h-28 object-cover rounded shadow" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Botão de envio --}}
                    <div class="pt-4">
                        <button type="submit"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow transition-all disabled:opacity-50"
                            wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $produto_id ? 'M5 13l4 4L19 7' : 'M12 4v16m8-8H4' }}" />
                            </svg>
                            {{ $produto_id ? 'Atualizar Produto' : 'Cadastrar Produto' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
