<!-- resources/views/livewire/servico-form.blade.php -->
<div class="ml-64 pt-[72px] p-6 bg-gray-50 min-h-screen">
    <div class="flex">
        <x-sidebar />

        <div class="flex-1 p-6 ml-64 md:ml-0 transition-all duration-300">
            <x-topbar />
            <div class="bg-white shadow rounded-xl p-6 space-y-6 max-w-2xl mx-auto">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <x-heroicon-o-wrench class="h-6 w-6 text-blue-600" />
                    Cadastro de Serviço
                </h2>

                <!-- Nome do Serviço -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Serviço</label>
                    <div class="relative">
                        <x-heroicon-o-pencil class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                        <input type="text" wire:model.defer="nome" placeholder="Ex: Alinhamento"
                            class="pl-10 pr-4 py-2 w-full border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-500" />
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <textarea wire:model.defer="descricao" placeholder="Descrição do serviço"
                        class="w-full p-3 border rounded-md shadow-sm resize-none focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <!-- Valor e Duração -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valor (R$)</label>
                        <div class="relative">
                            <x-heroicon-o-currency-dollar class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                            <input type="number" step="0.01" wire:model.defer="valor" placeholder="0,00"
                                class="pl-10 pr-4 py-2 w-full border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duração (minutos)</label>
                        <div class="relative">
                            <x-heroicon-o-clock class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                            <input type="number" wire:model.defer="duracao" placeholder="Ex: 30"
                                class="pl-10 pr-4 py-2 w-full border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none focus:border-blue-500" />
                        </div>
                    </div>
                </div>

                <!-- Ativo -->
                <div class="flex items-center space-x-2">
                    <input type="checkbox" wire:model.defer="ativo"
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label class="text-sm text-gray-700">Serviço Ativo</label>
                </div>

                <!-- Botão -->
                <div class="pt-4">
                    <button wire:click="salvar"
                        class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300 shadow">
                        <x-heroicon-o-check class="inline h-5 w-5 mr-2 -mt-1" />
                        Salvar Serviço
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>