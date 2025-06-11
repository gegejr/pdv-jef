<div class="ml-64 pt-[72px] p-6 bg-gray-100 min-h-screen">
    <div class="flex">
        <x-sidebar />

        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <x-topbar />
            <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5.121 17.804A13.937 13.937 0 0112 15c2.69 0 5.18.793 7.121 2.146M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Relacionamento com Clientes
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border rounded-xl overflow-hidden shadow bg-white text-gray-700">
                    <thead class="bg-indigo-100 text-indigo-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.121 17.804A13.937 13.937 0 0112 15c2.69 0 5.18.793 7.121 2.146M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Nome
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase">Telefone</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase">Aniversário</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase">WhatsApp</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse ($clientesRelacionamento as $rel)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">{{ $rel->cliente->nome }}</td>
                                <td class="px-6 py-3">{{ $rel->cliente->telefone }}</td>
                                <td class="px-6 py-3">
                                    {{ \Carbon\Carbon::parse($rel->cliente->data_nascimento)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-3">
                                    @if ($rel->tem_whatsapp)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-green-700 bg-green-100">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M..."/> <!-- ícone WhatsApp -->
                                            </svg>
                                            Sim
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-red-700 bg-red-100">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Não
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if ($rel->tem_whatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $rel->cliente->telefone) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1 text-sm font-medium text-white bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded shadow">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M..."/> <!-- ícone WhatsApp -->
                                            </svg>
                                            Conversar
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhum cliente encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
