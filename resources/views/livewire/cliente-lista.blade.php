<div class="ml-64 pt-[72px] p-6">
        <x-sidebar />

        <div class="p-6">
                    <x-topbar />
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($mensagemErro)
                        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                            {{ $mensagemErro }}
                        </div>
                    @endif
            @push('scripts')
            <script>
                window.addEventListener('cliente-nao-excluido', event => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Não é possível excluir',
                        text: `O cliente "${event.detail.nome}" possui vendas registradas.`,
                    });
                });

                window.addEventListener('cliente-excluido', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cliente excluído',
                        showConfirmButton: false,
                        timer: 1500
                    });
                });
            </script>
            @endpush
            <h2 class="text-xl font-bold mb-4">Clientes</h2>

            <button wire:click="$set('modalAberto', true)" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 hover:bg-blue-700">
                Novo Cliente
            </button>

            <table class="min-w-full divide-y divide-gray-200 border rounded-lg overflow-hidden shadow-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Nome</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">CPF/CNPJ</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Telefone</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Endereço</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($clientes as $cliente)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-2 text-center text-sm font-semibold text-gray-900">{{ $cliente->nome }}</td>
                            <td class="px-4 py-2 text-center text-sm font-semibold text-gray-900">{{ $cliente->cpf_cnpj }}</td>
                            <td class="px-4 py-2 text-center text-sm font-semibold text-gray-900">{{ $cliente->telefone }}</td>
                            <td class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">{{ $cliente->endereco }}</td>
                            <td class="px-4 py-2 text-center space-x-1 whitespace-nowrap">
                                <button wire:click="ver({{ $cliente->id }})" class="text-blue-600 hover:underline">Ver</button>
                                <button wire:click="editar({{ $cliente->id }})" class="text-yellow-600 hover:underline">Editar</button>
                                <button wire:click="excluir({{ $cliente->id }})" class="text-red-600 hover:underline">Excluir</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-2 text-center">Nenhum cliente encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Modal --}}
           @if ($modalAberto)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6 space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                                @if ($clienteSelecionadoId)
                                    <x-heroicon-o-pencil class="w-5 h-5 text-blue-600" />
                                    Editar Cliente
                                @else
                                    <x-heroicon-o-user-plus class="w-5 h-5 text-green-600" />
                                    Novo Cliente
                                @endif
                            </h3>
                            <button wire:click="resetCampos" class="text-gray-400 hover:text-gray-600">
                                <x-heroicon-o-x-mark class="w-6 h-6" />
                            </button>
                        </div>

                        <form wire:submit.prevent="{{ $clienteSelecionadoId ? 'atualizar' : 'salvar' }}" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome</label>
                                <div class="relative">
                                    <x-heroicon-o-user class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" />
                                    <input type="text" wire:model.defer="nome"
                                        class="w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        @if($modoVisualizacao || $modoEdicao) disabled @endif>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">CPF/CNPJ</label>
                                <div class="relative">
                                    <x-heroicon-o-identification class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" />
                                    <input type="text" wire:model.defer="cpf_cnpj"
                                        class="w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        @if($modoVisualizacao || $modoEdicao) disabled @endif>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                                <input type="date" wire:model.defer="data_nascimento"
                                    class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    @if($modoVisualizacao) readonly @endif>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Endereço</label>
                                <input type="text" wire:model.defer="endereco"
                                    class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    @if($modoVisualizacao) readonly @endif>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telefone</label>
                                <input type="text" wire:model.defer="telefone"
                                    class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    @if($modoVisualizacao) readonly @endif>
                            </div>

                            <div class="flex justify-end gap-3 pt-4">
                                <button type="button" wire:click="resetCampos"
                                    class="inline-flex items-center px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                                    <x-heroicon-o-x-circle class="w-5 h-5 mr-2" />
                                    Cancelar
                                </button>

                                @if (!$modoVisualizacao)
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                        <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
                                        Salvar
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>
</div>