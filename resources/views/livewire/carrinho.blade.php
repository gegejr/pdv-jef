
<div class="flex">
    <!-- Menu lateral -->
    <x-sidebar />

    <!-- Conteúdo principal -->
    <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
        <!-- Topbar -->
        <x-topbar />

        <h2 class="text-xl font-bold mb-4">Carrinho de Compras</h2>

        @if (count($carrinho) > 0)
    <table class="w-full table-auto border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Produto</th>
                <th class="p-2 border">Preço</th>
                <th class="p-2 border">Quantidade</th>
                <th class="p-2 border">Total</th>
                <th class="p-2 border">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($carrinho as $item)
                <tr>
                    <td class="p-2 border">{{ $item['produto']->nome }}</td>
                    <td class="p-2 border">R$ {{ number_format($item['produto']->valor, 2, ',', '.') }}</td>
                    <td class="p-2 border">
                        <input type="number" min="1" class="border rounded px-2 py-1 w-20"
                               wire:change="atualizarQuantidade({{ $item['produto']->id }}, $event.target.value)"
                               value="{{ $item['quantidade'] }}">
                    </td>
                    <td class="p-2 border">R$ {{ number_format($item['valor_total'], 2, ',', '.') }}</td>
                    <td class="p-2 border text-center">
                        <button wire:click="removerItem({{ $item['produto']->id }})"
                                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                            Remover
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 text-right">
        <strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong>
    </div>

    <!-- Seleção de pagamento -->
    <div class="mt-4">
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
        <h3 class="text-lg font-semibold mb-2">Total a Pagar: R$ {{ number_format($total - $desconto_total, 2, ',', '.') }}</h3>
    </div>

    <div class="mt-4 text-right">
        <button wire:click="fecharVenda" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Finalizar Venda
        </button>
    </div>
@else
    <div class="text-center p-4">Carrinho vazio</div>
@endif
    </div>
</div>
