<div class="ml-64 pt-[72px] p-6">
    <!-- Topbar -->
    <x-topbar class="w-full" />
    
    <div class="flex flex-1">
        <!-- Sidebar -->
        <x-sidebar class="w-64" />
        
        <!-- Conteúdo principal -->
        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <h3 class="font-bold mb-2">Histórico de Perdas</h3>
            <table class="w-full table-auto border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-center text-sm text-gray-700">Imagem</th>
                        <th class="px-4 py-2 text-center text-sm text-gray-700">Nome</th>
                        <th class="px-4 py-2 text-center text-sm text-gray-700">Quantidade</th>
                        <th class="px-4 py-2 text-center text-sm text-gray-700">Motivo</th>
                        <th class="px-4 py-2 text-center text-sm text-gray-700">Valor</th>
                        <th class="px-4 py-2 text-center text-sm text-gray-700">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perdas as $perda)
                        <tr class="border-t">
                        <td class="p-2 border text-center">
                            @if ($perda->produto && $perda->produto->imagem)
                                <img src="{{ asset('storage/' . $perda->produto->imagem) }}" class="w-12 h-12 object-cover rounded mx-auto">
                            @else
                                -
                            @endif
                        </td>
                            <td class="px-4 py-2 text-center text-sm text-gray-700">{{$perda->produto->nome}}</td>
                            <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $perda->quantidade }}</td>
                            <td class="px-4 py-2 text-center text-sm text-gray-700">{{ ucfirst($perda->motivo) }}</td>
                            <td class="px-4 py-2 text-center text-sm text-gray-700">R$ {{ number_format($perda->valor, 2, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $perda->created_at->format('d/m/Y H:i') }}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4 ">
        {{ $perdas->links('pagination::tailwind') }}
    </div>
</div>
