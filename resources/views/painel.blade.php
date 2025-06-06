
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 font-sans leading-relaxed text-gray-800">

<div class="pt-[72px] p-6 min-h-screen md:ml-64 transition-all duration-300">
    <div class="flex">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Conteúdo principal -->
        <div class="flex-1 p-6 transition-all duration-300">
            <!-- Topbar -->
            <x-topbar />

            <!-- Cabeçalho e botão -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-column-icon lucide-chart-column">
                        <path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/>
                    </svg>
                    Dashboard
                </h1>
                <button onclick="toggleSidebar()" class="md:hidden bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Menu
                </button>
            </div>

        <!-- Filtro por Data -->
        <form method="GET" action="{{ route('painel') }}" class="bg-white p-6 rounded-lg shadow flex flex-col sm:flex-row items-end gap-4 mb-8">
            <div>
                <label for="inicio" class="text-sm font-semibold">Data Início</label>
                <input type="date" name="inicio" id="inicio" value="{{ $inicio ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="fim" class="text-sm font-semibold">Data Fim</label>
                <input type="date" name="fim" id="fim" value="{{ $fim ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
            </div>
                <button type="submit" class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sliders-horizontal-icon lucide-sliders-horizontal">
                        <line x1="21" x2="14" y1="4" y2="4"/>
                        <line x1="10" x2="3" y1="4" y2="4"/>
                        <line x1="21" x2="12" y1="12" y2="12"/>
                        <line x1="8" x2="3" y1="12" y2="12"/>
                        <line x1="21" x2="16" y1="20" y2="20"/>
                        <line x1="12" x2="3" y1="20" y2="20"/>
                        <line x1="14" x2="14" y1="2" y2="6"/>
                        <line x1="8" x2="8" y1="10" y2="14"/>
                        <line x1="16" x2="16" y1="18" y2="22"/>
                    </svg>
                    Filtrar
                </button>
            @if ($caixa)
                <div class="sm:ml-auto flex items-center gap-3 text-sm font-semibold text-gray-800 bg-gray-100 dark:bg-gray-800 px-6 py-4 rounded-xl shadow-inner">
                    <!-- Ícone Heroicons -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c1.657 0 3 1.343 3 3m3-3a6 6 0 11-6-6 6 6 0 016 6zm-9 7H6a2 2 0 00-2 2v1h16v-1a2 2 0 00-2-2h-3" />
                    </svg>

                    <!-- Texto do Caixa -->
                    <div>
                        <span class="text-blue-600">Caixa Atual:</span> {{ $caixa->nome }}
                        @if($caixa->aberto_em)
                            <span class="text-gray-500 dark:text-gray-300">| Aberto em:</span>
                            <span class="text-gray-600 dark:text-gray-200">{{ \Carbon\Carbon::parse($caixa->aberto_em)->format('d/m/Y H:i') }}</span>
                        @endif
                    </div>
                </div>
            @else
                <div class="sm:ml-auto text-sm font-semibold text-red-600 bg-red-100 px-6 py-4 rounded shadow-inner">
                    Nenhum caixa aberto no momento.
                </div>
            @endif
        </form>


            <!-- Cards de Resumo -->
                
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <!-- Card 1 -->
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-5 rounded-xl shadow hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-md font-semibold text-blue-900 dark:text-blue-100">Total de Vendas</h3>
                <p class="text-3xl font-bold text-blue-700 dark:text-blue-200 mt-2">R$ {{ number_format($totalVendas, 2, ',', '.') }}</p>
            </div>
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-200/50 dark:bg-blue-700/30">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-no-axes-combined-icon lucide-chart-no-axes-combined"><path d="M12 16v5"/><path d="M16 14v7"/><path d="M20 10v11"/><path d="m22 3-8.646 8.646a.5.5 0 0 1-.708 0L9.354 8.354a.5.5 0 0 0-.707 0L2 15"/><path d="M4 18v3"/><path d="M8 14v7"/></svg>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 p-5 rounded-xl shadow hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-md font-semibold text-green-900 dark:text-green-100">Número de Vendas</h3>
                <p class="text-3xl font-bold text-green-700 dark:text-green-200 mt-2">{{ $numeroVendas }}</p>
            </div>
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-200/50 dark:bg-green-700/30">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up10-icon lucide-arrow-up-1-0"><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/><path d="M17 10V4h-2"/><path d="M15 10h4"/><rect x="15" y="14" width="4" height="6" ry="2"/></svg>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 p-5 rounded-xl shadow hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-md font-semibold text-yellow-900 dark:text-yellow-100 mb-4">Relatório</h3>
                <a href="{{ route('relatorio-vendas') }}" class="text-yellow-600 dark:text-yellow-300 font-bold hover:underline">Ver Relatório →</a>
            </div>
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-200/50 dark:bg-yellow-700/30">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chart-gantt-icon lucide-square-chart-gantt"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 8h7"/><path d="M8 12h6"/><path d="M11 16h5"/></svg>
            </div>
        </div>
    </div>
</div>


            <!-- Últimas Vendas -->
            <h2 class="text-2xl font-semibold mb-4">Últimas Vendas</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg shadow border">
                        <thead class="bg-gray-100 text-sm text-gray-600 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-center">ID</th>
                                <th class="px-6 py-3 text-center">Usuário</th>
                                <th class="px-6 py-3 text-center">Cliente</th>
                                <th class="px-6 py-3 text-center">Desconto</th>
                                <th class="px-6 py-3 text-center">Total</th>
                                <th class="px-6 py-3 text-center">Data</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($ultimasVendas as $venda)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-6 py-4 text-center">{{ $venda->id }}</td>
                                    <td class="px-6 py-4 text-center">{{ $venda->user->name }}</td>
                                    <td class="px-6 py-4 text-center">{{ $venda->cliente?->nome ?? 'Não informado' }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ number_format($venda->desconto_total, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        R$ {{ number_format($venda->total - $venda->desconto_total, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                
                            @endforeach

                            
                        </tbody>
                    </table>
                </div>


            <!-- Últimos Caixas -->
            <h2 class="text-2xl font-semibold mt-10 mb-4">Últimos Caixas Fechados</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg shadow border">
                    <thead class="bg-gray-100 text-sm text-gray-600 uppercase">
                        <tr>
                            <th class="px-6 py-3 text-center">ID</th>
                            <th class="px-6 py-3 text-left">Usuário</th>
                            <th class="px-6 py-3 text-center">Inicial</th>
                            <th class="px-6 py-3 text-center">Final</th>
                            <th class="px-6 py-3 text-center">Fechado em</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($historicoCaixas as $caixa)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-4 text-center">{{ $caixa->id }}</td>
                                <td class="px-6 py-4">{{ $caixa->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center">R$ {{ number_format($caixa->valor_inicial, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">R$ {{ number_format($caixa->valor_final, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">{{ \Carbon\Carbon::parse($caixa->fechado_em)->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhum caixa fechado encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@livewireScripts
</body>
</html>
