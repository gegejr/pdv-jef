<div class="flex">
    <!-- Menu lateral -->
    <x-sidebar />

    <!-- Conteúdo principal -->
    <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
        <!-- Topbar -->
        <x-topbar />

        <h2 class="text-xl font-bold mb-4">Caixa e Sangria</h2>

        <!-- Caixa -->
        @if ($caixa)
            <div class="bg-white p-4 border rounded shadow-sm">
                <h3 class="text-lg font-semibold">Caixa Aberto</h3>
                <p><strong>Valor Inicial:</strong> R$ {{ number_format($caixa->valor_inicial, 2, ',', '.') }}</p>
                <p><strong>Valor Final:</strong> R$ {{ number_format($caixa->valor_final ?? 0, 2, ',', '.') }}</p>
                <p><strong>Aberto em:</strong> {{ \Carbon\Carbon::parse($caixa->aberto_em)->format('d/m/Y H:i') }}</p>

                @if ($caixa->fechado_em)
                    <p><strong>Fechado em:</strong> {{ \Carbon\Carbon::parse($caixa->fechado_em)->format('d/m/Y H:i') }}</p>
                @endif

                <div class="mt-4">
                    <button wire:click="fecharCaixa" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Fechar Caixa
                    </button>
                </div>
            </div>
        @else
            <div class="bg-white p-4 border rounded shadow-sm">
                <h3 class="text-lg font-semibold">Abrir Caixa</h3>
                <div class="mb-4">
                    <label for="valor_inicial" class="block text-sm font-semibold">Valor Inicial</label>
                    <input type="number" id="valor_inicial" wire:model="valor_inicial" class="border p-2 rounded w-full" required>
                </div>
                <button wire:click="abrirCaixa" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Abrir Caixa
                </button>
            </div>
        @endif

        <!-- Registrar Sangria -->
        @if ($caixa)
            <div class="mt-6 bg-white p-4 border rounded shadow-sm">
                <h3 class="text-lg font-semibold mb-4">Registrar Sangria</h3>
                <form wire:submit.prevent="registrarSangria" class="space-y-4">
                    <div>
                        <label for="valor_sangria" class="block text-sm font-semibold">Valor</label>
                        <input type="number" id="valor_sangria" wire:model="valor_sangria" class="border p-2 rounded w-full" min="0" step="0.01" required>
                        @error('valor_sangria') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="observacao_sangria" class="block text-sm font-semibold">Observação</label>
                        <input type="text" id="observacao_sangria" wire:model="observacao_sangria" class="border p-2 rounded w-full" required>
                        @error('observacao_sangria') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Registrar Sangria
                    </button>
                </form>
            </div>
        @endif

        <!-- Lista de Sangrias -->
        <!-- Lista de Sangrias -->
        @if ($caixa && $caixa->sangrias && $caixa->sangrias->count() > 0)
            <div class="mt-6">
                <h3 class="text-lg font-semibold">Sangrias Registradas</h3>
                <table class="w-full table-auto border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Data</th>
                            <th class="p-2 border">Valor</th>
                            <th class="p-2 border">Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($caixa->sangrias as $sangria)
                            <tr>
                                <td class="p-2 border">{{ $sangria->created_at->format('d/m/Y H:i') }}</td>
                                <td class="p-2 border">R$ {{ number_format($sangria->valor, 2, ',', '.') }}</td>
                                <td class="p-2 border">{{ $sangria->observacao }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center p-4">Nenhuma sangria registrada.</div>
        @endif
    </div>
</div>
