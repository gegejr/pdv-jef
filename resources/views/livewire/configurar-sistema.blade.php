@if ($mostrarConfiguracoes)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/2">
        <h2 class="text-xl font-bold mb-4">Configurações do Sistema</h2>

        @foreach ($configuracoes as $categoria => $configs)
            <div class="mb-4">
                <h3 class="font-semibold text-lg mb-2">{{ ucfirst($categoria) }}</h3>
                @foreach ($configs as $config)
                    <div class="flex justify-between items-center mb-2">
                        <span>{{ ucwords(str_replace('_', ' ', $config['chave'])) }}</span>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600"
                                wire:change="atualizarConfiguracao({{ $config['id'] }}, $event.target.checked)"
                                @checked($config['ativo'])>
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach

        <button wire:click="$set('mostrarConfiguracoes', false)"
            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 mt-4">Fechar</button>
    </div>
</div>
@endif
