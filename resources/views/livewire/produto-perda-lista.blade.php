@section('title', 'Consultar Perdas')
<div class="ml-64 pt-[72px] p-6 bg-gray-100 min-h-screen">
    <div class="flex">
        <x-sidebar />

    <div class="flex-1 ml-64 md:ml-0 transition-all duration-300">
            <x-topbar />

        <!-- Conteúdo principal -->
        <div class="p-6 space-y-6 bg-white shadow rounded-xl">
            
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14m-14.14 0L19.07 4.93" />
                        </svg>
                        Histórico de Perdas
                    </h3>
                </div>

                <div wire:loading.class="opacity-50 pointer-events-none relative">
                    <table class="w-full text-sm text-left border rounded shadow overflow-hidden">
                        <thead class="bg-gray-200 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="p-2 border">Imagem</th>
                                <th class="p-2 border">Nome</th>
                                <th class="p-2 border">Qtd</th>
                                <th class="p-2 border">Motivo</th>
                                <th class="p-2 border">Valor</th>
                                <th class="p-2 border">Data</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($perdas as $perda)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-2 border">
                                        @if ($perda->produto && $perda->produto->imagem)
                                            <img src="{{ asset('storage/' . $perda->produto->imagem) }}" class="w-12 h-12 object-cover rounded-full mx-auto shadow-sm">
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="p-2 border">{{ $perda->produto->nome }}</td>
                                    <td class="p-2 border">{{ $perda->quantidade }}</td>
                                    <td class="p-2 border ">{{ $perda->motivo }}</td>
                                    <td class="p-2 border ">R$ {{ number_format($perda->valor, 2, ',', '.') }}</td>
                                    <td class="p-2 border">{{ $perda->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $perdas->links('pagination::tailwind') }}
                </div>
                <!-- Loader -->
                    <div wire:loading
                        class="absolute inset-0 bg-white bg-opacity-70 flex items-center justify-center">
                        <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>
            
            </div>
        </div>
    </div>
    </div>
</div>
