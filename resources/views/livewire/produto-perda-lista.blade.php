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
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border text-center">Imagem</th>
                        <th class="p-2 border text-center">Nome</th>
                        <th class="p-2 border text-center">Quantidade</th>
                        <th class="p-2 border text-center">Motivo</th>
                        <th class="p-2 border text-center">Valor</th>
                        <th class="p-2 border text-center">Data</th>
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
                            <td class="p-2 border text-center">{{$perda->produto->nome}}</td>
                            <td class="p-2 border text-center">{{ $perda->quantidade }}</td>
                            <td class="p-2 border text-center">{{ ucfirst($perda->motivo) }}</td>
                            <td class="p-2 border text-center">R$ {{ number_format($perda->valor, 2, ',', '.') }}</td>
                            <td class="p-2 border text-center">{{ $perda->created_at->format('d/m/Y H:i') }}</td>
                            
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
