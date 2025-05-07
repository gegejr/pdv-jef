<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Conteúdo principal -->
        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <button onclick="toggleSidebar()" class="mb-4 bg-blue-500 text-white px-3 py-1 rounded focus:outline-none md:hidden">
                Menu
            </button>

            <!-- Topbar com nome e logout -->
            <x-topbar />

            <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

            <!-- Filtro por Data -->
            <form method="GET" action="{{ route('painel') }}" class="mb-6 flex flex-col sm:flex-row items-start sm:items-end gap-4">
                <div>
                    <label for="inicio" class="block text-sm font-medium text-gray-700">Data Início</label>
                    <input type="date" name="inicio" id="inicio" value="{{ $inicio ?? '' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="fim" class="block text-sm font-medium text-gray-700">Data Fim</label>
                    <input type="date" name="fim" id="fim" value="{{ $fim ?? '' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-6 sm:mt-0 hover:bg-blue-700 transition">Filtrar</button>
                </div>
            </form>

            <!-- Cards de Resumo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                    <h3 class="text-lg font-semibold text-gray-700">Total de Vendas (R$)</h3>
                    <p class="text-3xl font-bold text-blue-600">
                        R$ {{ number_format($totalVendas, 2, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                    <h3 class="text-lg font-semibold text-gray-700">Número de Vendas</h3>
                    <p class="text-3xl font-bold text-green-600">
                        {{ $numeroVendas }}
                    </p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                    <h3 class="text-lg font-semibold text-gray-700">Relatório de Vendas</h3>
                    <p class="text-3xl font-bold text-yellow-600">Ver Relatório</p>
                </div>
            </div>

            <!-- Últimas Vendas -->
            <h2 class="text-2xl font-semibold mt-8 mb-4">Últimas Vendas</h2>

            <table class="min-w-full table-auto bg-white border border-gray-200 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">ID Venda</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Usuário</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Total</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimasVendas as $venda)
                        <tr class="border-b">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $venda->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $venda->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
        </table>
    </div>
</div>

@livewireScripts
