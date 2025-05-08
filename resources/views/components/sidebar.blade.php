<div id="sidebar" class="bg-white w-64 min-h-screen shadow-md transition-all duration-300 fixed inset-0 md:relative md:inset-auto">
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

    </nav>
</div>
