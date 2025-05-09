<div class="w-full bg-white shadow-md px-6 py-4 flex justify-between items-center mb-6 fixed top-0 left-0 right-0 z-50">
    <div class="flex items-center gap-2">
    <img src="{{ asset('build/assets/jef.ico') }}" alt="Ícone" class="w-10 h-10">
    <h1 class="text-xl font-semibold">JEF - System</h1>    
</div>
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