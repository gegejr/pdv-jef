
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 font-sans leading-relaxed text-gray-800">

<div class="ml-64 pt-[72px] p-6 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Conteúdo principal -->
        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <!-- Topbar -->
            <x-topbar />

            <!-- Cabeçalho e botão -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold">Dashboard</h1>
                <button onclick="toggleSidebar()" class="md:hidden bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Menu
                </button>
            </div>

            <!-- Filtro por Data -->
            <form method="GET" action="{{ route('painel') }}" class="bg-white p-4 rounded-lg shadow-md mb-6 flex flex-col sm:flex-row items-end gap-4">
                <div>
                    <label for="inicio" class="text-sm font-medium">Data Início</label>
                    <input type="date" name="inicio" id="inicio" value="{{ $inicio ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="fim" class="text-sm font-medium">Data Fim</label>
                    <input type="date" name="fim" id="fim" value="{{ $fim ?? '' }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Filtrar
                </button>
            </form>

            <!-- Cards de Resumo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold text-gray-700">Total de Vendas</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">R$ {{ number_format($totalVendas, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold text-gray-700">Número de Vendas</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $numeroVendas }}</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition flex flex-col justify-between">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Relatório</h3>
                    <a href="{{ route('relatorio-vendas') }}" class="text-yellow-600 font-bold hover:underline">Ver Relatório &rarr;</a>
                </div>
            </div>

            <!-- Últimas Vendas -->
            <h2 class="text-2xl font-semibold mb-4">Últimas Vendas</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg shadow border">
                    <thead class="bg-gray-100 text-sm text-gray-600 uppercase">
                        <tr>
                            <th class="px-6 py-3 text-center">ID</th>
                            <th class="px-6 py-3 text-left">Usuário</th>
                            <th class="px-6 py-3 text-left">Cliente</th>
                            <th class="px-6 py-3 text-center">Total</th>
                            <th class="px-6 py-3 text-center">Data</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($ultimasVendas as $venda)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-4 text-center">{{ $venda->id }}</td>
                                <td class="px-6 py-4">{{ $venda->user->name }}</td>
                                <td class="px-6 py-4">{{ $venda->cliente?->nome ?? 'Não informado' }}</td>
                                <td class="px-6 py-4 text-center">R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
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
