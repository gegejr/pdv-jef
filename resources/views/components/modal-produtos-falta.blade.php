@if($showFalta)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <!-- Fundo escuro clicável para fechar -->
        <div class="absolute inset-0 bg-black/50" wire:click="fecharFalta"></div>

        <!-- Caixa branca do modal -->
        <div class="relative bg-white p-6 rounded shadow-lg max-w-md w-full z-10">
            <h3 class="text-lg font-bold mb-4">Produtos em falta (estoque &lt; 10)</h3>
            <ul class="space-y-2">
                @forelse($produtosEmFalta as $produto)
                    <li class="text-sm text-red-600">
                        {{ $produto->nome }} — {{ $produto->estoque }} unidades restantes
                    </li>
                @empty
                    <li class="text-sm text-gray-500">Nenhum produto em falta.</li>
                @endforelse
            </ul>

            <!-- Botão fechar opcional -->
            <button wire:click="fecharFalta"
                    class="mt-4 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded">
                Fechar
            </button>
        </div>
    </div>
@endif