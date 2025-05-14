<div id="sidebar" class="bg-white w-64 h-screen shadow-md fixed top-0 left-0 z-40 transition-all duration-300">
    <div class="p-4 border-b font-bold text-xl text-center">
        JEF-Sys
    </div>
    <nav class="p-4 space-y-2">

        <!-- Link para o painel -->
        <a href="{{ route('painel') }}" class="flex items-center text-gray-700 hover:bg-gray-200 p-2 rounded">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 3h18v18H3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                <path d="M3 9h18M3 15h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
            Painel
        </a>
                <!-- Link para Caixa/Sangria -->
        <a href="{{ route('caixa-sangria') }}" class="flex items-center text-gray-700 hover:bg-gray-200 p-2 rounded">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4 4h16v16H4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                <path d="M6 4v16M18 4v16M6 8h12M6 12h12M6 16h12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
            Caixa/Sangria
        </a>
        <!-- Link para o carrinho -->
        <a href="{{ route('carrinho') }}" class="flex items-center text-gray-700 hover:bg-gray-200 p-2 rounded">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M6 6h15l1 12H7L6 6z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                <circle cx="9" cy="19" r="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                <circle cx="18" cy="19" r="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
            Carrinho
        </a>
        <!-- Link para o Relatório de Vendas -->
        <a href="{{ route('relatorio-vendas') }}" class="flex items-center text-gray-700 hover:bg-gray-200 p-2 rounded">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M19 7l-7 7-7-7m7 4V3" />
            </svg>
            Relatório de Vendas
        </a>



        <!-- Link para adicionar produtos -->

        <div x-data="{ open: false }">
            <!-- Botão principal -->
        <button @click="open = !open" class="w-full flex items-center justify-between text-gray-700 hover:bg-gray-200 p-2 rounded">
            <span class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <path d="M3 9h18M3 15h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
                Produtos
            </span>
            <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Dropdown -->
            <div x-show="open" class="pl-6 mt-2 space-y-1" x-cloak>
                    <a href="{{ route('produtos.lista') }}" class="block text-gray-700 hover:bg-gray-200 p-2 rounded">Listar Produtos</a>
                    <a href="{{ route('adicionar-produto') }}" class="block text-gray-700 hover:bg-gray-200 p-2 rounded">Adicionar Produto</a>
                    <a href="{{ route('produtos-perda-lista') }}" class="block text-gray-700 hover:bg-gray-200 p-2 rounded">Ver Perdas</a>
            </div>
        </div> 
                <a href="{{ route('clientes') }}" class="flex items-center text-gray-700 hover:bg-gray-200 p-2 rounded">
            <svg class="w-6 h-6 mr-2 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25v-1.5a4.5 4.5 0 014.5-4.5h6a4.5 4.5 0 014.5 4.5v1.5" />
            </svg>
            Clientes
        </a>



        <div x-data="{ open: false }">
            <!-- Botão principal -->
            <button @click="open = !open" class="w-full flex items-center justify-between text-gray-700 hover:bg-gray-200 p-2 rounded">
                <span class="flex items-center">
                    <!-- Ícone de ferramenta -->
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.983 13.883a2 2 0 100-3.766 2 2 0 000 3.766z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.735 10.533a1 1 0 00-.75-1.293l-1.74-.435a7.017 7.017 0 00-.54-1.307l.93-1.547a1 1 0 00-.27-1.376l-1.414-1.414a1 1 0 00-1.376-.27l-1.548.93a7.01 7.01 0 00-1.307-.54l-.435-1.74a1 1 0 00-1.293-.75l-2 .5a1 1 0 00-.75 1.293l.435 1.74a7.017 7.017 0 00-1.307.54l-1.548-.93a1 1 0 00-1.376.27L3.635 5.67a1 1 0 00-.27 1.376l.93 1.547a7.01 7.01 0 00-.54 1.307l-1.74.435a1 1 0 00-.75 1.293l.5 2a1 1 0 001.293.75l1.74-.435c.147.46.327.9.54 1.307l-.93 1.547a1 1 0 00.27 1.376l1.414 1.414a1 1 0 001.376.27l1.547-.93c.407.213.847.393 1.307.54l-.435 1.74a1 1 0 00.75 1.293l2 .5a1 1 0 001.293-.75l.435-1.74c.46-.147.9-.327 1.307-.54l1.547.93a1 1 0 001.376-.27l1.414-1.414a1 1 0 00.27-1.376l-.93-1.547c.213-.407.393-.847.54-1.307l1.74.435a1 1 0 001.293-.75l.5-2z" />
                    </svg>
                    Configurações
                </span>
                <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div x-show="open" class="pl-6 mt-2 space-y-1" x-cloak>
                <a href="{{ route('usuarios.criar') }}" class="block text-gray-700 hover:bg-gray-200 p-2 rounded">Criar Usuário</a>
            </div>
        </div>

    </nav>
    
</div>
