<div class="ml-64 pt-[72px] p-6">
    <div class="flex">
    <!-- Menu lateral -->
    <x-sidebar />

    <!-- Conteúdo principal -->
    <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
        <!-- Topbar -->
        <x-topbar />

        <div class="max-w-xl mx-auto p-4 bg-white shadow rounded">
            <form wire:submit.prevent="salvar">
                @csrf

                <h2 class="text-xl font-bold mb-4">
                    @if($produto_id)
                        Editar Produto
                    @else
                        Cadastrar Produto
                    @endif
                </h2>

                @if (session()->has('sucesso'))
                    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                        {{ session('sucesso') }}
                    </div>
                @endif

                <div class="mb-4">
                    <label>Nome</label>
                    <input type="text" wire:model="nome" class="w-full border p-2 rounded" />
                    @error('nome') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label>Código de Barras</label>
                    <input type="text" wire:model="codigo_barras" class="w-full border p-2 rounded" />
                </div>

                <div class="mb-4">
                    <label>Descrição</label>
                    <textarea wire:model="descricao" class="w-full border p-2 rounded"></textarea>
                </div>

                <div class="mb-4">
                    <label>Valor</label>
                    <input type="number" step="0.01" wire:model="valor" class="w-full border p-2 rounded" />
                    @error('valor') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label>Estoque</label>
                    <input type="number" wire:model="estoque" class="w-full border p-2 rounded" />
                    @error('estoque') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label>Desconto Padrão</label>
                    <input type="number" step="0.01" wire:model="desconto_padrao" class="w-full border p-2 rounded" />
                </div>

                <div class="mb-4">
                    <label>Imagem</label>
                    <input type="file" wire:model="imagem" class="w-full" />
                    @error('imagem') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    @if ($imagem_existente && !$imagem)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $imagem_existente) }}" class="w-24 h-24 object-cover rounded">
                        </div>
                    @endif
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    @if($produto_id)
                        Atualizar Produto
                    @else
                        Cadastrar Produto
                    @endif
                </button>
            </form>
        </div>
    </div>
    </div>
</div>