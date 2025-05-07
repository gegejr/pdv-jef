<div class="w-full bg-white shadow-md px-6 py-4 flex justify-between items-center mb-6">
    <h1 class="text-xl font-semibold">Painel Administrativo</h1>
    <div class="flex items-center gap-4">
        <span class="text-gray-700 font-medium">Olá, {{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                Sair
            </button>
        </form>
    </div>
</div>
