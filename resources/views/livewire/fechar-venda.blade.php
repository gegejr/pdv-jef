<div class="flex">
    <!-- Menu lateral -->
    <x-sidebar />

    <!-- Conteúdo principal -->
    <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
        <!-- Topbar -->
        <x-topbar />

        <h2 class="text-xl font-bold mb-4">Fechar Venda</h2>

        @if (session()->has('sucesso'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                {{ session('sucesso') }}
            </div>
        @endif

        <div>
            <h3 class="text-lg font-semibold mb-2">Método de Pagamento</h3>
            <select wire:model="metodo_pagamento" class="border p-2 rounded w-full">
                <option value="dinheiro">Dinheiro</option>
                <option value="debito">Débito</option>
                <option value="credito">Crédito</option>
                <option value="pix">Pix</option>
            </select>
        </div>

        <div class="mt-4">
            <h3 class="text-lg font-semibold mb-2">Desconto Total</h3>
            <input type="number" wire:model="desconto_total" class="border p-2 rounded w-full" min="0" step="0.01" />
        </div>

        <div class="mt-4">
            <h3 class="text-lg font-semibold mb-2">Total: R$ {{ number_format($total - $desconto_total, 2, ',', '.') }}</h3>
        </div>

        <div class="mt-4">
            <button wire:click="fecharVenda" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Finalizar Venda
            </button>
        </div>
    </div>
</div>
