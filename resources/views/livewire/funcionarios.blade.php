@section('title', 'Funcionários')
<div class="ml-64 pt-[72px] p-6 bg-gray-100 min-h-screen">
    <div class="flex">
        <x-sidebar />

        <div class="flex-1 ml-64 md:ml-0 transition-all duration-300">
            <x-topbar />

            <div class="p-6 space-y-6 bg-white shadow rounded-xl">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <x-heroicon-o-user-group class="w-6 h-6 text-indigo-600" />
                        Funcionários
                    </h2>
                </div>

                <table class="min-w-full bg-white rounded shadow text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-left uppercase text-xs text-gray-600">
                            <th class="px-4 py-2">Nome</th>
                            <th class="px-4 py-2">E-mail</th>
                            <th class="px-4 py-2">Função</th>
                            <th class="px-4 py-2">Comissão</th>
                            <th class="px-4 py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $usuario->name }}</td>
                                <td class="px-4 py-2">{{ $usuario->email }}</td>
                                <td class="px-4 py-2">{{ $usuario->role }}</td>
                                <td class="px-4 py-2">
                                    @if($usuario->comissionado)
                                        Venda: {{ $usuario->comissao_venda }}% <br>
                                        Serviço: {{ $usuario->comissao_servico }}%
                                    @else
                                        <span class="text-gray-500">Não comissionado</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 space-x-2 text-sm">
                                    <button wire:click="abrirModalEdicao({{ $usuario->id }})" class="text-indigo-600 hover:underline">Editar</button>
                                    <button wire:click="verComissao({{ $usuario->id }})" class="text-green-600 hover:underline">Ver comissão</button>
                                    <button wire:click="verHistorico({{ $usuario->id }})" class="text-gray-700 hover:underline">Histórico</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white w-full max-w-lg rounded-lg p-6 shadow-lg space-y-4">
                <h2 class="text-xl font-semibold flex items-center gap-2 text-gray-800">
                    <x-heroicon-o-pencil-square class="w-5 h-5 text-blue-600" /> Editar Funcionário
                </h2>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Nome
                        <input type="text" wire:model.defer="name" class="mt-1 w-full border rounded px-3 py-2 shadow-sm">
                    </label>
                    <label class="block text-sm font-medium">Email
                        <input type="email" wire:model.defer="email" class="mt-1 w-full border rounded px-3 py-2 shadow-sm">
                    </label>
                    <label class="block text-sm font-medium">Função
                        <select wire:model.defer="role" class="mt-1 w-full border rounded px-3 py-2 shadow-sm">
                            <option value="user">Usuário</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="comissionado">
                        Funcionário comissionado
                    </label>

                    @if($comissionado)
                        <label class="block text-sm font-medium">Comissão por Venda (%)
                            <input type="number" step="0.01" wire:model.defer="comissao_venda" class="mt-1 w-full border rounded px-3 py-2 shadow-sm">
                        </label>
                        <label class="block text-sm font-medium">Comissão por Serviço (%)
                            <input type="number" step="0.01" wire:model.defer="comissao_servico" class="mt-1 w-full border rounded px-3 py-2 shadow-sm">
                        </label>
                    @endif
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancelar</button>
                    <button wire:click="salvarUsuario" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
                </div>
            </div>
        </div>
    @endif

    @if($showComissaoModal)

        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white w-full max-w-xl rounded-lg p-6 shadow-lg space-y-4">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <x-heroicon-o-currency-dollar class="w-5 h-5 text-green-600" /> Comissão de {{ $usuarioComissao->name }}
                </h2>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium">Data início
                            <input type="date" wire:model="comissaoInicio" class="mt-1 w-full border rounded px-3 py-2 shadow-sm">
                        </label>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium">Data fim
                            <input type="date" wire:model="comissaoFim" class="mt-1 w-full border rounded px-3 py-2 shadow-sm">
                        </label>
                    </div>
                </div>

                <div class="mt-4 space-y-2 text-sm text-gray-700">
                    <div><strong>Total comissão sobre vendas:</strong> R$ {{ number_format($comissaoVendas, 2, ',', '.') }}</div>
                    <div><strong>Total comissão sobre serviços:</strong> R$ {{ number_format($comissaoServicos, 2, ',', '.') }}</div>
                    <div class="text-lg font-semibold mt-2 text-indigo-700"><strong>Total geral:</strong> R$ {{ number_format($totalComissao, 2, ',', '.') }}</div>
                </div>

                <div class="flex justify-end mt-4">
                    <button wire:click="$set('showComissaoModal', false)" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Fechar</button>
                </div>
            </div>
        </div>
        
    @endif

    @if($showHistoricoModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white w-full max-w-5xl rounded-lg p-6 shadow-lg space-y-4 overflow-y-auto max-h-screen">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-gray-700" /> Histórico de {{ $usuarioHistorico->name }}
                </h2>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium">Data início
                            <input type="date" wire:model="historicoInicio" class="mt-1 w-full border rounded px-3 py-2 shadow-sm">
                        </label>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium">Data fim
                            <input type="date" wire:model="historicoFim" class="mt-1 w-full border rounded px-3 py-2 shadow-sm">
                        </label>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-bold text-gray-800">Vendas Realizadas</h3>
                    @if(count($vendasRealizadas) > 0)
                        <table class="w-full mt-2 border text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2 text-left">ID</th>
                                    <th class="px-3 py-2 text-left">Cliente</th>
                                    <th class="px-3 py-2 text-left">Total</th>
                                    <th class="px-3 py-2 text-left">Desconto</th>
                                    <th class="px-3 py-2 text-left">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendasRealizadas as $venda)
                                    <tr class="border-t">
                                        <td class="px-3 py-1">#{{ $venda['id'] }}</td>
                                        <td class="px-3 py-1">{{ $venda['cliente_id'] ?? '-' }}</td>
                                        <td class="px-3 py-1">R$ {{ number_format($venda['total'], 2, ',', '.') }}</td>
                                        <td class="px-3 py-1">R$ {{ number_format($venda['desconto_total'], 2, ',', '.') }}</td>
                                        <td class="px-3 py-1">{{ \Carbon\Carbon::parse($venda['created_at'])->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-gray-600 mt-2">Nenhuma venda encontrada.</p>
                    @endif
                </div>

                <div class="flex justify-end mt-6">
                    <button wire:click="$set('showHistoricoModal', false)" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Fechar</button>
                </div>
            </div>
        </div>
    @endif
</div>