<div class="mt-4">
    <h3 class="text-lg font-semibold mb-2">Métodos de Pagamento</h3>
    
    @foreach ($pagamentos as $index => $pagamento)
        <div class="flex items-center gap-2 mb-2">
            <select wire:model="pagamentos.{{ $index }}.tipo" class="border p-2 rounded w-1/2">
                <option value="">Selecione</option>
                <option value="dinheiro">Dinheiro</option>
                <option value="debito">Débito</option>
                <option value="credito">Crédito</option>
                <option value="pix">Pix</option>
                <option value="conta">Conta</option>
            </select>

            <input 
                type="number" 
                wire:model="pagamentos.{{ $index }}.valor" 
                class="border p-2 rounded w-1/2"
                min="0" step="0.01"
                placeholder="Valor"
            >

            <button 
                wire:click="removerPagamento({{ $index }})" 
                class="text-red-500 hover:text-red-700 font-bold"
                title="Remover"
            >
                &times;
            </button>
        </div>
    @endforeach

    <button wire:click="adicionarPagamento" class="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300">
        + Adicionar Pagamento
    </button>

    <div class="mt-2 text-sm text-gray-600">
        Total informado: R$ <span wire:poll.200ms>{{ number_format($this->totalInformado, 2, ',', '.') }}</span>
    </div>
</div>