@section('title', 'Lista | Serviços')
<div class="ml-64 pt-[72px] p-6 bg-gray-50 min-h-screen">
    <div class="flex">
        <x-sidebar />

        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <x-topbar />
            <div class="p-6 bg-white rounded-xl shadow space-y-6">
                <!-- Título e botão -->
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <x-heroicon-o-wrench class="w-6 h-6 text-blue-600" />
                        Serviços Cadastrados
                    </h2>
                    <a href="{{ route('adicionar-servico') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 transition">
                        <x-heroicon-o-plus class="w-5 h-5" />
                        Novo Serviço
                    </a>
                </div>

                <!-- Campo de busca -->
                <div class="relative">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                    <input
                        type="text"
                        wire:model.debounce.300ms="busca"
                        placeholder="Buscar serviço por nome..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    />
                </div>

                <!-- Tabela -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg shadow">
                        <thead class="bg-gray-100 text-gray-700 text-center uppercase text-xs">
                            <tr>
                                <th class="p-3">Nome</th>
                                <th class="p-3">Preço</th>
                                <th class="p-3">Duração</th>
                                <th class="p-3 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servicos as $servico)
                                <tr class="hover:bg-gray-50 border-t border-gray-200">
                                    <td class="p-3">{{ $servico->nome }}</td>
                                    <td class="p-3">R$ {{ number_format($servico->valor, 2, ',', '.') }}</td>
                                    <td class="p-3">{{ $servico->duracao }} min</td>
                                    <td class="p-3 text-center">
                                        <a href="{{ route('adicionar-servico', ['id' => $servico->id]) }}"
                                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 hover:underline">
                                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-gray-500">
                                        <x-heroicon-o-exclamation-circle class="w-6 h-6 inline mb-1 text-gray-400" />
                                        Nenhum serviço encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação opcional -->
                <div>
                    {{ $servicos->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
