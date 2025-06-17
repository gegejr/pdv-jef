@section('title', 'Usuários')

<div class="ml-64 pt-[72px] p-6">
        <x-sidebar />

        <div class="p-6">
                    <x-topbar />        
            <div class="max-w-5xl mx-auto mt-24 p-6 bg-white shadow-md rounded-2xl">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <x-heroicon-o-user-plus class="w-6 h-6 text-blue-600" />
                    {{ $editarId ? 'Editar Usuário' : 'Criar Novo Usuário' }}
                </h2>

                @if (session('sucesso'))
                    <div class="mb-4 text-green-600 bg-green-100 p-4 rounded-md shadow">
                        {{ session('sucesso') }}
                    </div>
                @endif

                <form wire:submit.prevent="salvar" class="space-y-4" wire:key="{{ $editarId ?? 'novo' }}">
                    <div>
                        <label>Nome</label>
                        <input type="text" wire:model="name" class="w-full border p-2 rounded">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label>Email</label>
                        <input type="email" wire:model="email" class="w-full border p-2 rounded">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label>Senha</label>
                        <input type="password" wire:model="password" class="w-full border p-2 rounded">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label>Confirmar Senha</label>
                        <input type="password" wire:model="password_confirmation" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>Função</label>
                        <select wire:model="role" class="w-full border p-2 rounded">
                            <option value="user">Usuário</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

                    <div class="flex justify-between">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                            {{ $editarId ? 'Atualizar' : 'Criar' }}
                        </button>

                        @if($editarId)
                            <button type="button" wire:click="resetarFormulario" class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                        @endif
                    </div>
                </form>

                @if($usuarios->count())
                    <div class="mt-12">
                        <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                            <x-heroicon-o-users class="w-6 h-6 text-gray-700" />
                            Usuários Cadastrados
                        </h3>

                        <table class="min-w-full text-left border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border">Nome</th>
                                    <th class="px-4 py-2 border">Email</th>
                                    <th class="px-4 py-2 border">Função</th>
                                    <th class="px-4 py-2 border">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $usuario)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 border">{{ $usuario->name }}</td>
                                        <td class="px-4 py-2 border">{{ $usuario->email }}</td>
                                        <td class="px-4 py-2 border">{{ $usuario->role }}</td>
                                        <td class="px-4 py-2 border space-x-2">
                                            <button wire:click="editar({{ $usuario->id }})" class="text-yellow-600 hover:underline">Editar</button>
                                            <button wire:click="deletar({{ $usuario->id }})" class="text-red-600 hover:underline">Excluir</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
    </div>
    
</div>