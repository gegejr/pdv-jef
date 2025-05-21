<div id="sidebar" class="bg-white w-64 h-screen shadow-lg fixed top-0 left-0 z-40 transition-all duration-300 border-r">
    <div class="p-4 border-b text-2xl font-extrabold text-gray-800 text-center tracking-tight">
        JEF-Sys
    </div>
    <nav class="px-4 py-6 space-y-2 text-sm font-medium text-gray-700">
        
        <!-- Item padrão -->
        <a href="{{ route('painel') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition group">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 3h18v18H3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                <path d="M3 9h18M3 15h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
            <span>Painel</span>
        </a>

        <a href="{{ route('caixa-sangria') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition group">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4 4h16v16H4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                <path d="M6 4v16M18 4v16M6 8h12M6 12h12M6 16h12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
            <span>Caixa/Sangria</span>
        </a>

        <a href="{{ route('carrinho') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition group">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9d9e9b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-dollar-sign-icon lucide-badge-dollar-sign">
                <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/>
                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/>
            </svg>
            <span>
                Iniciar Venda
            </span>
        </a>

        <a href="{{ route('relatorio-vendas') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition group">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M19 7l-7 7-7-7m7 4V3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
            <span>Relatório de Vendas</span>
        </a>

        <!-- Produtos Dropdown -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-100 transition group">
                <span class="flex items-center gap-3 text-gray-700">
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        <path d="M3 9h18M3 15h18" />
                    </svg>
                    Produtos
                </span>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-cloak class="pl-8 space-y-1 text-sm">
                <a href="{{ route('produtos.lista') }}" class="block px-2 py-1 rounded hover:bg-gray-100">Listar Produtos</a>
                <a href="{{ route('adicionar-produto') }}" class="block px-2 py-1 rounded hover:bg-gray-100">Adicionar Produto</a>
                <a href="{{ route('produtos-perda-lista') }}" class="block px-2 py-1 rounded hover:bg-gray-100">Ver Perdas</a>
            </div>
        </div>

        <!-- Clientes Dropdown -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-100 transition group">
                <span class="flex items-center gap-3 text-gray-700">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        <path d="M4.5 20.25v-1.5a4.5 4.5 0 014.5-4.5h6a4.5 4.5 0 014.5 4.5v1.5" />
                    </svg>
                    Clientes
                </span>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-cloak class="pl-8 space-y-1 text-sm">
                <a href="{{ route('clientes') }}" class="block px-2 py-1 rounded hover:bg-gray-100">Adicionar Cliente</a>
                <a href="{{ route('cliente.conta') }}" class="block px-2 py-1 rounded hover:bg-gray-100">Conta Cliente</a>
            </div>
        </div>

        <!-- Mesas -->
        <a href="{{ route('mesas') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition group">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 10h18v2H3v-2zm2 2v6h2v-4h10v4h2v-6H5z" />
            </svg>
            <span>Mesas</span>
        </a>

        <!-- Configurações -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-100 transition group">
                <span class="flex items-center gap-3 text-gray-700">
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M11.983 13.883a2 2 0 100-3.766 2 2 0 000 3.766z" />
                        <path d="M20.735 10.533a1 1 0 00-.75-1.293l-1.74-.435a7.017 7.017 0 00-.54-1.307l.93-1.547a1 1 0 00-.27-1.376l-1.414-1.414a1 1 0 00-1.376-.27l-1.548.93a7.01 7.01 0 00-1.307-.54l-.435-1.74a1 1 0 00-1.293-.75l-2 .5a1 1 0 00-.75 1.293l.435 1.74a7.017 7.017 0 00-1.307.54l-1.548-.93a1 1 0 00-1.376.27L3.635 5.67a1 1 0 00-.27 1.376l.93 1.547a7.01 7.01 0 00-.54 1.307l-1.74.435a1 1 0 00-.75 1.293l.5 2a1 1 0 001.293.75l1.74-.435c.147.46.327.9.54 1.307l-.93 1.547a1 1 0 00.27 1.376l1.414 1.414a1 1 0 001.376.27l1.547-.93c.407.213.847.393 1.307.54l-.435 1.74a1 1 0 00.75 1.293l2 .5a1 1 0 001.293-.75l.435-1.74c.46-.147.9-.327 1.307-.54l1.547.93a1 1 0 001.376-.27l1.414-1.414a1 1 0 00.27-1.376l-.93-1.547c.213-.407.393-.847.54-1.307l1.74.435a1 1 0 001.293-.75l.5-2z" />
                    </svg>
                    Configurações
                </span>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-cloak class="pl-8 space-y-1 text-sm">
                <a href="{{ route('usuarios.criar') }}" class="block px-2 py-1 rounded hover:bg-gray-100">Criar Usuário</a>
            </div>
        </div>
    </nav>
</div>
