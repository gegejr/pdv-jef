<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('build/assets/jef.ico') }}" type="image/x-icon" />
</head>
<body class="bg-gray-100 font-sans text-gray-800">

<div class="min-h-screen md:pl-64 transition-all duration-300">
    <!-- Sidebar -->
    <x-sidebar />

    <!-- Conteúdo -->
    <div class="pt-[72px] p-6">
        <!-- Topbar -->
        <x-topbar />

        <!-- Cabeçalho -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                
                
            </h1>
            <button onclick="toggleSidebar()" class="md:hidden bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Menu
            </button>
        </div>

       <!-- Filtro por Data -->
<form id="filtroForm" method="GET" action="{{ route('painel') }}" class="bg-white p-6 rounded-xl shadow mb-8 flex flex-col sm:flex-row items-end gap-4 relative">

    <div id="date-range-picker" date-rangepicker class="flex items-center gap-2">
        <!-- Início -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                </svg>
            </div>
            <input id="datepicker-range-start" name="inicio" type="date" value="{{ $inicio ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5">
        </div>

        <span class="text-gray-500">até</span>

        <!-- Fim -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                </svg>
            </div>
            <input id="datepicker-range-end" name="fim" type="date" value="{{ $fim ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5">
        </div>
    </div>

    <!-- Botão Filtrar -->
    <button type="submit" class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

    <!-- Indicador de Carregamento -->
    <div id="loadingIndicator" class="hidden flex items-center justify-center gap-2 text-gray-600 font-semibold py-4">
        <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        Carregando...
    </div>

    <!-- Caixa atual -->
    @if ($caixa)
        <div class="sm:ml-auto flex items-center gap-3 bg-blue-50 text-blue-800 px-6 py-4 rounded-xl shadow-inner text-sm font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c1.657 0 3 1.343 3 3m3-3a6 6 0 11-6-6 6 6 0 016 6zm-9 7H6a2 2 0 00-2 2v1h16v-1a2 2 0 00-2-2h-3"/>
            </svg>
            <div>
                <span class="text-blue-700">Caixa Atual:</span> {{ $caixa->nome }}
                @if($caixa->aberto_em)
                    <span class="text-gray-600">| Aberto em:</span>
                    <span>{{ \Carbon\Carbon::parse($caixa->aberto_em)->format('d/m/Y H:i') }}</span>
                @endif
            </div>
        </div>
    @else
        <div class="sm:ml-auto bg-red-100 text-red-600 px-6 py-4 rounded-xl shadow-inner text-sm font-semibold">
            Nenhum caixa aberto no momento.
        </div>
    @endif
</form>



    
      <!-- Cards Resumo -->
<!-- Cards Resumo -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    @if(auth()->user()->role === 'admin')
        @livewire('vendas-resumo', ['inicio' => request('inicio'), 'fim' => request('fim')])

        <!-- Total de Despesas -->
        <div class="bg-red-100 p-4 rounded-xl shadow hover:shadow-xl transition transform hover:scale-105 duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-700 font-semibold">Total de Despesas</p>
                    <p class="text-3xl font-bold text-red-800 mt-1">R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-red-200/50 rounded-full group relative" title="Despesas totais do período">
                    <!-- Ícone seta para baixo com animação -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-800 transition-transform duration-300 group-hover:scale-110 group-hover:translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Fluxo de Caixa -->
        <div class="bg-green-100 p-4 rounded-xl shadow hover:shadow-xl transition transform hover:scale-105 duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-700 font-semibold">Fluxo de Caixa</p>
                    <p class="text-3xl font-bold text-green-800 mt-1">R$ {{ number_format($fluxoCaixa, 2, ',', '.') }}</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-green-200/50 rounded-full group relative" title="Saldo final entre entradas e saídas">
                    <!-- Ícone seta para cima com animação -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-800 transition-transform duration-300 group-hover:scale-110 group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </div>
            </div>
        </div>
    @endif

    <!-- Vendas do Dia -->
    <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-5 rounded-xl shadow hover:shadow-xl transition transform hover:scale-105 duration-300">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-md font-semibold text-purple-900">Vendas do Dia</h3>
                <p class="text-sm text-purple-600">{{ now()->format('d/m/Y') }}</p>
                <p class="text-3xl font-bold text-purple-700 mt-1">R$ {{ number_format($vendasHoje, 2, ',', '.') }}</p>
            </div>
            <div class="flex items-center justify-center w-12 h-12 bg-purple-200/50 rounded-full group relative" title="Total vendido hoje">
                <!-- Ícone calendário com animação -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-800 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/>
                </svg>
            </div>
        </div>
    </div>
</div>

        </div>

        <!-- Últimas Vendas -->
        <h2 class="text-3xl font-bold text-gray-800 tracking-wide border-l-4 border-blue-500 pl-3 mb-4">Últimas Vendas</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-xl shadow border">
                <thead class="bg-gray-100 text-xs text-gray-600 uppercase">
                    <tr>
                        <th class="px-6 py-3 text-center">ID</th>
                        <th class="px-6 py-3 text-center">Usuário</th>
                        <th class="px-6 py-3 text-center">Cliente</th>
                        <th class="px-6 py-3 text-center">Desconto</th>
                        <th class="px-6 py-3 text-center">Total</th>
                        <th class="px-6 py-3 text-center">Data</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($ultimasVendas as $venda)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-center">{{ $venda->id }}</td>
                            <td class="px-6 py-4 text-center">{{ $venda->user->name }}</td>
                            <td class="px-6 py-4 text-center">{{ $venda->cliente?->nome ?? 'Não informado' }}</td>
                            <td class="px-6 py-4 text-center text-gray-500">{{ number_format($venda->desconto_total, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center text-gray-500">R$ {{ number_format($venda->total - $venda->desconto_total, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Nenhuma venda realizada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@if(auth()->user()->role === 'admin')
        <!-- Últimos Caixas -->
        <h2 class="text-2xl font-semibold mt-10 mb-4">Últimos Caixas Fechados</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-xl shadow border">
                <thead class="bg-gray-100 text-xs text-gray-600 uppercase">
                    <tr>
                        <th class="px-6 py-3 text-center">ID</th>
                        <th class="px-6 py-3 text-left">Usuário</th>
                        <th class="px-6 py-3 text-center">Inicial</th>
                        <th class="px-6 py-3 text-center">Final</th>
                        <th class="px-6 py-3 text-center">Fechado em</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($historicoCaixas as $caixa)
                        <tr class="hover:bg-gray-50">
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
@endif
    </div>
</div>

@livewireScripts
<script>
  const form = document.getElementById('filtroForm');
  const loading = document.getElementById('loadingIndicator');

  form.addEventListener('submit', function() {
    loading.classList.remove('hidden');
  });
</script>

</script>
</body>
</html>
