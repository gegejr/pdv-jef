<div class="ml-64 pt-[72px] p-6">
    <div>
        <!-- Tabela de produtos (já existente) -->

        <!-- Modal de Edição -->
        @if($produtoId)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-6 rounded shadow-xl w-96">
                    <h2 class="text-xl font-bold mb-4">Editar Produto</h2>
                    <form wire:submit.prevent="salvar">
                        <!-- Formulário de edição com os dados preenchidos -->
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
                        </div>

                        <div class="mb-4">
                            <label>Estoque</label>
                            <input type="number" wire:model="estoque" class="w-full border p-2 rounded" />
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

                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Salvar</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
