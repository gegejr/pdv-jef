<div class="ml-64 pt-[72px] p-6 bg-gray-50 min-h-screen">
    <div class="flex">
        <x-sidebar />

        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <x-topbar />
            <h2 class="text-xl font-bold mb-4">Relacionamento com Clientes</h2>

            <table class="w-full border rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Nome</th>
                        <th class="p-2 text-left">Telefone</th>
                        <th class="p-2 text-left">WhatsApp</th>
                        <th class="p-2 text-left">Última Interação</th>
                        <th class="p-2 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientesRelacionamento as $rel)
                        <tr class="border-t">
                            <td class="p-2">{{ $rel->cliente->nome }}</td>
                            <td class="p-2">{{ $rel->cliente->telefone }}</td>
                            <td class="p-2">
                                @if ($rel->tem_whatsapp)
                                    ✅ Sim
                                @else
                                    ❌ Não
                                @endif
                            </td>
                            <td class="p-2">
                                {{ $rel->ultima_interacao ? \Carbon\Carbon::parse($rel->ultima_interacao)->diffForHumans() : 'Nunca' }}
                            </td>
                            <td class="p-2">
                                @if ($rel->tem_whatsapp)
                                    <button wire:click="enviarMensagem({{ $rel->id }})"
                                        class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                        Enviar Mensagem
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">Nenhum cliente encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>