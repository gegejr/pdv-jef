@if($produtoId)
    <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 px-4 overflow-y-auto">
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Editar Produto</h2>
                </div>
                <button wire:click="fecharModal" class="text-gray-500 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="salvar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome</label>
                        <input type="text" wire:model.defer="nome" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm" />
                        @error('nome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Código de Barras</label>
                        <input type="text" wire:model.defer="codigo_barras" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm" />
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                        <textarea wire:model.defer="descricao" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor</label>
                        <input type="number" step="0.01" wire:model.defer="valor" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estoque</label>
                        <input type="number" wire:model.defer="estoque" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Desconto Padrão</label>
                        <input type="number" step="0.01" wire:model.defer="desconto_padrao" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Imagem</label>
                        <input type="file" wire:model="imagem" class="w-full text-gray-700 dark:text-white" />
                        @error('imagem') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @if ($imagem_existente && !$imagem)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $imagem_existente) }}" class="w-24 h-24 object-cover rounded shadow">
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="my-6 border-gray-300 dark:border-gray-700">

                <div x-data="{ mostrarPerda: @entangle('registrar_perda') }">
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" x-model="mostrarPerda" class="form-checkbox text-blue-600 rounded">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Registrar Perda</span>
                    </label>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                        Marque para registrar perda de estoque. Valor atual:
                        <span x-text="mostrarPerda ? 'SIM' : 'NÃO'"></span>
                    </p>

                    <div x-show="mostrarPerda" class="grid grid-cols-1 gap-4 mt-4">
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-300">Quantidade Perdida</label>
                            <input type="number" wire:model="quantidade_perda" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm" />
                        </div>
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-300">Motivo da Perda</label>
                            <select wire:model="motivo_perda" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm">
                                <option value="">Selecione</option>
                                <option value="quebra">Quebra</option>
                                <option value="descarte">Descarte</option>
                                <option value="perda">Perda</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <button type="submit" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M17 16v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1h14zM6 2a2 2 0 00-2 2v10h12V4a2 2 0 00-2-2H6zm2 2h4v4H8V4z" />
                        </svg>
                        Salvar
                    </button>
                    <button type="button" wire:click="fecharModal" class="flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif