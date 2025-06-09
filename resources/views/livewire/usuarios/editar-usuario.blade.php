<div>
    @if ($modalAberto)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:key="{{ now() }}">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-6 space-y-6">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                    <h2 class="text-xl font-semibold mb-4">Editar Usuário</h2>

                    <form wire:submit.prevent="atualizar" class="space-y-4">
                        <div>
                            <label>Nome</label>
                            <input type="text" wire:model.defer="name" class="w-full border p-2 rounded">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label>Email</label>
                            <input type="email" wire:model.defer="email" class="w-full border p-2 rounded">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label>Função</label>
                            <select wire:model.defer="role" class="w-full border p-2 rounded">
                                <option value="user">Usuário</option>
                                <option value="admin">Administrador</option>
                            </select>
                            @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" wire:click="$set('modalAberto', false)" class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
<div>
