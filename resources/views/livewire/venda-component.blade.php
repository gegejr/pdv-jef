<div>
    <label for="cliente">Cliente:</label>
    <select wire:model="clienteSelecionado" id="cliente">
        <option value="">Selecione um cliente</option>
        @foreach($clientes as $cliente)
            <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
        @endforeach
    </select>

    @if($clienteSelecionado)
        <h3>Produtos disponíveis:</h3>
        <ul>
            @foreach($produtosDisponiveis as $produto)
                <li>{{ $produto->nome }}</li>
            @endforeach
        </ul>
    @endif
</div>