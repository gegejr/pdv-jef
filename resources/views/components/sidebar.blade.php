<div id="sidebar" class="bg-white w-64 h-screen shadow-md fixed top-0 left-0 z-40 transition-all duration-300">
    <div class="p-4 border-b font-bold text-xl text-center">
        JEF-Sys
    </div>
    <nav class="p-4 space-y-2">

        <!-- Link para o painel -->
        <a href="{{ route('painel') }}" class="flex items-center text-gray-700 hover:bg-gray-200 p-2 rounded">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 2v20m-10-10h20" />
            </svg>
            Painel
        </a>

        <!-- Link para o carrinho -->
        <a href="{{ route('carrinho') }}" class="flex items-center text-gray-700 hover:bg-gray-200 p-2 rounded">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 3h18v2H3zm0 4h18v2H3zm0 4h18v2H3zm0 4h18v2H3zm0 4h18v2H3z" />
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

        <!-- Link para listar produtos -->
        <a href="{{ route('produtos.lista') }}" class="flex items-center text-gray-700 hover:bg-gray-200 p-2 rounded">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 3h18v2H3zm0 4h18v2H3zm0 4h18v2H3zm0 4h18v2H3zm0 4h18v2H3z" />
            </svg>
            Listar Produtos
        </a>

        <!-- Link para adicionar produtos -->
        <a href="{{ route('adicionar-produto') }}" class="flex items-center text-gray-700 hover:bg-gray-200 p-2 rounded">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 2v20m-10-10h20" />
            </svg>
            Adicionar Produto
        </a>

        <!-- Link para Caixa/Sangria -->
        <a href="{{ route('caixa-sangria') }}" class="flex items-center text-gray-700 hover:bg-gray-200 p-2 rounded">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 2v20m-10-10h20" />
            </svg>
            Caixa/Sangria
        </a>
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded hover:bg-gray-100 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 16v-2m8-6h-2M6 12H4m15.364-4.636l-1.414 1.414M6.05 17.95l-1.414-1.414M17.95 17.95l-1.414-1.414M6.05 6.05L4.636 7.464" />
                </svg>
                <span class="text-gray-700">Configurações</span>
            </button>

            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                <a href="{{ route('usuarios.criar') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Criar Usuário
                </a>
                <!-- Você pode adicionar mais opções aqui futuramente -->
            </div>
        </div>
    </nav>
    
</div>
