<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-2xl space-y-8">
    <form wire:submit.prevent="salvar" class="space-y-6">
        @csrf

        <h2 class="text-3xl font-semibold text-gray-800 flex items-center gap-2">
            <x-heroicon-o-cube class="w-6 h-6 text-blue-600" />
            {{ $produto_id ? 'Editar Produto' : 'Cadastrar Produto' }}
        </h2>

        
        @if (session()->has('sucesso'))
            <div 
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 3000)" 
                x-show="show"
                x-transition
                class="fixed top-4 right-4 p-4 bg-green-100 text-green-800 rounded shadow-lg z-50"
            >
                {{ session('sucesso') }}
            </div>
        @endif

        {{-- Informações principais --}}
        <div>
            <h3 class="text-lg font-medium text-gray-700 flex items-center gap-1">
                <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500" />
                Informações principais
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form.input label="Nome *" model="nome" icon="tag" />
                <x-form.input label="Código de Barras" model="codigo_barras" icon="qr-code" />
                <x-form.input label="SKU (opcional)" model="sku" icon="finger-print" />
                <x-form.input label="NCM" model="ncm" icon="finger-print" />
                <x-form.select label="Categoria *" model="categoria_id" :options="$categorias" value="id" text="nome" />
                <x-form.select label="Unidade de Medida" model="unidade_medida" :options="[['value' => 'un', 'text' => 'Unidade'], ['value' => 'kg', 'text' => 'Kg'], ['value' => 'l', 'text' => 'Litro'], ['value' => 'cx', 'text' => 'Caixa']]" />
                <x-form.select label="Status" model="status" :options="[['value' => 'ativo', 'text' => 'Ativo'], ['value' => 'inativo', 'text' => 'Inativo']]" />
                

                @if ($this->tipo_produto === 'roupa')
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-gray-700">Variações de Roupas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form.select label="Tamanho" model="tamanho" :options="[
                                ['value' => 'P', 'text' => 'P'],
                                ['value' => 'M', 'text' => 'M'],
                                ['value' => 'G', 'text' => 'G'],
                                ['value' => 'GG', 'text' => 'GG'],
                            ]" />
                            <x-form.input label="Cor" model="cor" />
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Estoque e Preço --}}
        <div>
            <h3 class="text-lg font-medium text-gray-700 flex items-center gap-1">
                <x-heroicon-o-currency-dollar class="w-5 h-5 text-green-500" />
                Estoque e preço
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-form.input type="number" step="0.01" label="Preço de Custo *" model="preco_custo" icon="banknotes" />
                <x-form.input type="number" step="0.01" label="Preço de Venda *" model="valor" icon="banknotes" />
                <x-form.input type="number" step="0.01" label="Desconto Padrão (%)" model="desconto_padrao" icon="tag" />
                <x-form.input type="number" label="Estoque *" model="estoque" icon="archive-box" />
            </div>
        </div>
        @if ($segmento === 'loja')
            {{-- Características de Moda --}}
            <div>
                <h3 class="text-lg font-medium text-gray-700 flex items-center gap-1">
                    <x-heroicon-o-adjustments-horizontal class="w-5 h-5 text-indigo-500" />
                    Características de Moda
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input label="Tamanho" model="tamanho" icon="arrows-pointing-out" />
                    <x-form.input label="Cor" model="cor" icon="swatch" />
                    <x-form.select label="Gênero" model="genero" :options="[
                        ['value' => 'unissex', 'text' => 'Unissex'],
                        ['value' => 'masculino', 'text' => 'Masculino'],
                        ['value' => 'feminino', 'text' => 'Feminino'],
                    ]" />
                    <x-form.input label="Marca" model="marca" icon="building-storefront" />
                    <x-form.input label="Material" model="material" icon="cube-transparent" />
                    <x-form.input label="Modelo (referência)" model="modelo" icon="identification" />
                    <x-form.input label="Coleção" model="colecao" icon="calendar" />
                </div>
            </div>
        @endif

        {{-- Informações Fiscais --}}
        <div>
            <h3 class="text-lg font-medium text-gray-700 flex items-center gap-1">
                <x-heroicon-o-document-text class="w-5 h-5 text-yellow-500" />
                Informações fiscais
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form.input label="CST ICMS" model="cst_icms" />
                <x-form.input type="number" step="0.01" label="ICMS (%)" model="icms_rate" />
                <x-form.input label="CST IPI" model="cst_ipi" />
                <x-form.input type="number" step="0.01" label="IPI (%)" model="ipi_rate" />
                <x-form.input label="CST PIS" model="cst_pis" />
                <x-form.input type="number" step="0.01" label="PIS (%)" model="pis_rate" />
                <x-form.input label="CST COFINS" model="cst_cofins" />
                <x-form.input type="number" step="0.01" label="COFINS (%)" model="cofins_rate" />
            </div>
        </div>

        {{-- Descrição e Imagem --}}
        <div>
            <h3 class="text-lg font-medium text-gray-700 flex items-center gap-1">
                <x-heroicon-o-photo class="w-5 h-5 text-purple-500" />
                Descrição e imagem
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Descrição</label>
                    <textarea wire:model="descricao" rows="4"
                        class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Imagem</label>
                    <input type="file" wire:model="imagem"
                        class="w-full border rounded p-1 bg-white shadow-sm" />
                    @error('imagem') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                    @if ($imagem_existente && !$imagem)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-1">Imagem atual:</p>
                            <img src="{{ asset('storage/' . $imagem_existente) }}"
                                class="w-28 h-28 object-cover rounded shadow" />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Botão --}}
        <div class="pt-4 flex justify-end">
            <button type="submit"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl shadow transition disabled:opacity-50"
                wire:loading.attr="disabled">
                <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
                {{ $produto_id ? 'Atualizar Produto' : 'Cadastrar Produto' }}
            </button>
            <a href="{{ route('produtos.lista') }}"
            class="flex items-center gap-2 text-gray-600 hover:text-gray-800 text-sm underline">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Voltar para listagem
            </a>
        </div>
    </form>
</div>