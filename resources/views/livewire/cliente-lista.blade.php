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
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Cidade</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($clientes as $cliente)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-2 text-center text-sm font-semibold text-gray-900">{{ $cliente->nome }}</td>
                            <td class="px-4 py-2 text-center text-sm font-semibold text-gray-900">{{ $cliente->cpf_cnpj }}</td>
                            <td class="px-4 py-2 text-center text-sm font-semibold text-gray-900">{{ $cliente->telefone }}</td>
                            <td class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">{{ $cliente->cidade }}, {{ $cliente->uf }}</td>
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
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:key="{{ now() }}">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-6 space-y-6">
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

                            <form 
                                    @if($clienteSelecionadoId)
                                        wire:submit.prevent="atualizar"
                                    @else
                                        wire:submit.prevent="salvar"
                                    @endif
                                >
                                {{-- Tipo Pessoa --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipo de Pessoa</label>
                                    <select wire:model.live="tipo_pessoa"
                                        class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        @if($modoVisualizacao || $modoEdicao) disabled @endif>
                                        <option value="fisica" @if($modoVisualizacao || $modoEdicao) disabled @endif>Física</option>
                                        <option value="juridica" class="" @if($modoVisualizacao || $modoEdicao) disabled @endif>Jurídica</option>
                                    </select>
                                </div>

                                {{-- Dados Básicos --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                                        <input type="text" wire:model.defer="nome"
                                            class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao || $modoEdicao) disabled @endif>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">CPF/CNPJ</label>
                                        <input type="text" wire:model.defer="cpf_cnpj"
                                            class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao || $modoEdicao) disabled @endif>
                                    </div>

                                    @if ($tipo_pessoa === 'juridica')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Razão Social</label>
                                            <input type="text" wire:model.defer="razao_social"
                                                class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                @if($modoVisualizacao) readonly @endif>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nome Fantasia</label>
                                            <input type="text" wire:model.defer="nome_fantasia"
                                                class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                @if($modoVisualizacao) readonly @endif>
                                        </div>
                                        <div class="mb-4">
                                            <label for="cnae_id" class="block text-sm font-medium text-gray-700">
                                                Atividade Econômica Principal (CNAE)
                                            </label>
                                            <select name="cnae_id" id="cnae_id"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                            wire:model="cnae_id"
                                            @if($modoVisualizacao) disabled @endif>
                                                <option value="">Selecione...</option>
                                                @foreach($cnaes as $cnae)
                                                    <option value="{{ $cnae->id }}"
                                                        @selected(old('cnae_id', $cliente->cnae_id ?? '') == $cnae->id)>
                                                        {{ $cnae->codigo }} - {{ $cnae->descricao }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Dados de Contato e Nascimento --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Telefone</label>
                                        <input type="text" wire:model.defer="telefone"
                                            class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao) readonly @endif>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">E-mail</label>
                                        <input type="email" wire:model.defer="email"
                                            class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao) readonly @endif>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                                        <input type="date" wire:model.defer="data_nascimento"
                                            class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao) readonly @endif>
                                    </div>
                                </div>

                                {{-- Endereço --}}
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4" :key="$cep">
                                    <div class="flex items-end gap-2">
                                        <div class="w-full">
                                            <label class="block text-sm font-medium text-gray-700">CEP</label>
                                            <input type="text" wire:model.defer="cep"
                                                class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                placeholder="Digite o CEP"
                                                @if($modoVisualizacao) readonly @endif>
                                        </div>

                                        <button type="button"
                                            wire:click="buscarEnderecoPorCep"
                                            class="mb-1 px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            <!-- Ícone de lupa -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                                            </svg>
                                        </button>
                                    </div>


                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Endereço</label>
                                        <input type="text" wire:model.defer="endereco"
                                            class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao) readonly @endif>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Número</label>
                                        <input type="text" wire:model.defer="numero"
                                            class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao) readonly @endif>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Complemento</label>
                                        <input type="text" wire:model.defer="complemento"
                                            class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao) readonly @endif>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Bairro</label>
                                        <input type="text" wire:model.defer="bairro"
                                            class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao) readonly @endif>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Cidade</label>
                                        <input type="text" wire:model.defer="cidade"
                                            class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao) readonly @endif>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">UF</label>
                                        <input type="text" maxlength="2" wire:model.defer="uf"
                                            class="w-full border px-3 py-2 rounded-lg uppercase focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao) readonly @endif>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Código IBGE</label>
                                        <input type="text" maxlength="7" wire:model.defer="codigo_ibge"
                                            class="w-full border px-3 py-2 rounded-lg uppercase focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            @if($modoVisualizacao) readonly @endif>
                                    </div>
                                </div>

                                {{-- Campos fiscais extras para PJ --}}
                                @if($tipo_pessoa === 'juridica')
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Inscrição Estadual (IE)</label>
                                            <input type="text" wire:model.defer="ie"
                                                class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                @if($modoVisualizacao) readonly @endif>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Inscrição Municipal (IM)</label>
                                            <input type="text" wire:model.defer="im"
                                                class="w-full border px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                @if($modoVisualizacao) readonly @endif>
                                        </div>
                                    </div>
                                @endif

                                {{-- Botões --}}
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