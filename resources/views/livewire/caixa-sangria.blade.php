@section('title', 'Caixa | Sangria')
<div class="ml-64 pt-[72px] p-6">
    <div class="flex">
        <!-- Menu lateral -->
        <x-sidebar />

        <!-- Conteúdo principal -->
        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <!-- Topbar -->
            <x-topbar />

            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Caixa e Sangria
            </h2>

            <!-- Caixa -->
            @if ($caixa)
                <div class="bg-white p-6 border rounded-xl shadow">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#42cf61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-open-icon lucide-package-open">
                            <path d="M12 22v-9"/><path d="M15.17 2.21a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.655 1.655 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z"/>
                            <path d="M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13"/>
                            <path d="M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.636 1.636 0 0 0 1.63 0z"/>
                        </svg>
                        Caixa Aberto
                    </h3>
                    <p><strong>Nome:</strong> {{ $caixa->nome }}</p>
                    <p><strong>Valor Inicial:</strong> R$ {{ number_format($caixa->valor_inicial, 2, ',', '.') }}</p>
                    <p><strong>Valor Final:</strong> R$ {{ number_format($caixa->valor_final ?? 0, 2, ',', '.') }}</p>
                    <p><strong>Aberto em:</strong> {{ \Carbon\Carbon::parse($caixa->aberto_em)->format('d/m/Y H:i') }}</p>

                    @if ($caixa->fechado_em)
                        <p><strong>Fechado em:</strong> {{ \Carbon\Carbon::parse($caixa->fechado_em)->format('d/m/Y H:i') }}</p>
                    @endif

                    <div class="mt-4">
                        <button wire:click="fecharCaixa"
                            class="flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Fechar Caixa
                        </button>
                    </div>
                </div>
            @else
                <div class="bg-white p-6 border rounded-xl shadow">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                        Abrir Caixa
                    </h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="nome" class="block text-sm font-semibold">Nome do Caixa</label>
                            <input type="text" id="nome" wire:model="nome" class="border p-2 rounded w-full" required>
                        </div>
                        <div>
                            <label for="valor_inicial" class="block text-sm font-semibold">Valor Inicial</label>
                            <input type="number" id="valor_inicial" wire:model="valor_inicial" class="border p-2 rounded w-full" required>
                        </div>
                    </div>
                    <button wire:click="abrirCaixa"
                        class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                        Abrir Caixa
                    </button>
                </div>
            @endif

            <!-- Registrar Sangria -->
            @if ($caixa)
                <div class="mt-6 bg-white p-6 border rounded-xl shadow">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-red-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8c-1.657 0-3 1.343-3 3v1h6v-1c0-1.657-1.343-3-3-3z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 13h14v8H5z"/>
                        </svg>
                        Registrar Sangria
                    </h3>
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
                        <button type="submit"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 4v16m8-8H4"/>
                            </svg>
                            Registrar Sangria
                        </button>
                    </form>
                </div>
            @endif

            <!-- Lista de Sangrias -->
            @if ($caixa && $caixa->sangrias && $caixa->sangrias->count() > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-2">Sangrias Registradas</h3>
                    <div class="overflow-x-auto rounded border">
                        <table class="min-w-full text-sm text-left">
                            <thead class="bg-gray-100 font-semibold text-gray-700">
                                <tr>
                                    <th class="p-3 border">Data</th>
                                    <th class="p-3 border">Valor</th>
                                    <th class="p-3 border">Observação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($caixa->sangrias as $sangria)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-3 border">{{ $sangria->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="p-3 border">R$ {{ number_format($sangria->valor, 2, ',', '.') }}</td>
                                        <td class="p-3 border">{{ $sangria->observacao }}</td>
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
</div>
