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

            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-2">Nome</th>
                        <th class="px-4 py-2">CPF/CNPJ</th>
                        <th class="px-4 py-2">Telefone</th>
                        <th class="px-4 py-2">Endereço</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $cliente->nome }}</td>
                            <td class="px-4 py-2">{{ $cliente->cpf_cnpj }}</td>
                            <td class="px-4 py-2">{{ $cliente->telefone }}</td>
                            <td class="px-4 py-2">{{ $cliente->endereco }}</td>
                            <td class="px-4 py-2 space-x-2">
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
                    <div class="bg-white p-6 rounded shadow-md w-full max-w-xl">
                        <h3 class="text-lg font-bold mb-4">
                            {{ $clienteSelecionadoId ? 'Editar Cliente' : 'Novo Cliente' }}
                        </h3>

                        <form wire:submit.prevent="{{ $clienteSelecionadoId ? 'atualizar' : 'salvar' }}">
                            <div class="mb-3">
                                <label class="block mb-1">Nome</label>
                                <input type="text" wire:model.defer="nome" class="w-full border rounded px-3 py-2"
                                    @if($modoVisualizacao || $modoEdicao) disabled @endif>
                            </div>

                            <div class="mb-3">
                                <label class="block mb-1">CPF/CNPJ</label>
                                <input type="text" wire:model.defer="cpf_cnpj" class="w-full border rounded px-3 py-2"
                                    @if($modoVisualizacao || $modoEdicao) disabled @endif>
                            </div>

                            <div class="mb-3">
                                <label class="block mb-1">Data de Nascimento</label>
                                <input type="date" wire:model.defer="data_nascimento" class="w-full border rounded px-3 py-2"
                                    @if($modoVisualizacao) readonly @endif>
                            </div>

                            <div class="mb-3">
                                <label class="block mb-1">Endereço</label>
                                <input type="text" wire:model.defer="endereco" class="w-full border rounded px-3 py-2"
                                    @if($modoVisualizacao) readonly @endif>
                            </div>

                            <div class="mb-3">
                                <label class="block mb-1">Telefone</label>
                                <input type="text" wire:model.defer="telefone" class="w-full border rounded px-3 py-2"
                                    @if($modoVisualizacao) readonly @endif>
                            </div>


                            <div class="flex justify-end gap-2 mt-4">
                                <button type="button" wire:click="resetCampos" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                                    Cancelar
                                </button>

                                @if(!$modoVisualizacao)
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
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