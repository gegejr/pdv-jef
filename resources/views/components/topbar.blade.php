<div class="w-full bg-white shadow-lg px-8 py-4 flex justify-between items-center fixed top-0 left-0 right-0 z-50 border-b border-gray-200">
    <!-- Logo + Título -->
    <div class="flex items-center space-x-3">
        <img src="{{ asset('build/assets/jef.ico') }}" alt="Ícone" class="w-10 h-10 rounded-md shadow">
        <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">JEF - System</h1>    
    </div>

    <!-- Saudação + Logout -->
    <div class="flex items-center space-x-6">
        <span class="text-sm font-medium text-gray-700">Olá, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span></span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-200 shadow group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M12 2v10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <path d="M18.4 6.6a9 9 0 1 1-12.77.04" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
                <span class="text-sm font-medium">Sair</span>
            </button>
        </form>
    </div>
</div>
