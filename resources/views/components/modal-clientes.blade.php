 @if($modalClientesAberto)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-72 max-h-60 overflow-y-auto p-4 relative">
            <h3 class="text-base font-semibold mb-2">Selecione um Cliente</h3>
            <button
                wire:click="fecharModalClientes"
                class="absolute top-1 right-2 text-gray-600 hover:text-gray-900 font-bold"
                title="Fechar"
            >&times;</button>

            <!-- Campo de busca dentro do modal -->
            <input
                type="text"
                wire:model.debounce.300ms="busca_modal_cliente"
                class="form-input w-full mb-2 text-sm"
                placeholder="Buscar cliente..."
            >

            <ul>
                @foreach($todos_clientes as $cliente)
                    @if(str_contains(strtolower($cliente['nome']), strtolower($busca_modal_cliente)))
                        <li class="p-2 hover:bg-gray-200 cursor-pointer text-sm"
                            wire:click="selecionarCliente({{ $cliente['id'] }})">
                            {{ $cliente['nome'] }} – {{ $cliente['telefone'] }}
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
@endif