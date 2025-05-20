<div class="ml-64 pt-[72px] p-6 bg-gray-100 min-h-screen">
    <!-- Topbar -->
    <x-topbar class="w-full" />

    <div class="flex">
        <!-- Sidebar -->
        <x-sidebar class="w-64" />

        <!-- Conteúdo principal -->
        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <div class="bg-white shadow-lg rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14m-14.14 0L19.07 4.93" />
                        </svg>
                        Histórico de Perdas
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full table-auto text-sm text-gray-700">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 text-center">Imagem</th>
                                <th class="px-4 py-3 text-center">Nome</th>
                                <th class="px-4 py-3 text-center">Qtd</th>
                                <th class="px-4 py-3 text-center">Motivo</th>
                                <th class="px-4 py-3 text-center">Valor</th>
                                <th class="px-4 py-3 text-center">Data</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($perdas as $perda)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-2 text-center">
                                        @if ($perda->produto && $perda->produto->imagem)
                                            <img src="{{ asset('storage/' . $perda->produto->imagem) }}" class="w-12 h-12 object-cover rounded-full mx-auto shadow-sm">
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">{{ $perda->produto->nome }}</td>
                                    <td class="px-4 py-3 text-center">{{ $perda->quantidade }}</td>
                                    <td class="px-4 py-3 text-center capitalize">{{ $perda->motivo }}</td>
                                    <td class="px-4 py-3 text-center font-medium text-red-600">R$ {{ number_format($perda->valor, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center">{{ $perda->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $perdas->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
