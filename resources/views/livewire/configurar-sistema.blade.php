<div>
    @if($mostrarConfiguracoes)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded shadow-md w-96 relative">
                <button wire:click="$set('mostrarConfiguracoes', false)" class="absolute top-2 right-2 text-gray-600">
                    ✕
                </button>
                <h2 class="text-xl font-semibold mb-4">Configurações</h2>
                
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('usuarios.criar') }}" class="text-blue-600 hover:underline">➕ Criar Usuário</a>
                    </li>
                    <!-- Adicione mais opções aqui -->
                </ul>
            </div>
        </div>
    @endif
</div>
