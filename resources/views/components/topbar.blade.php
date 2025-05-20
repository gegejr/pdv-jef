<div class="w-full bg-white shadow-lg px-8 py-4 flex justify-between items-center fixed top-0 left-0 right-0 z-50 border-b border-gray-200">
    <!-- Logo + Título -->
    <div class="flex items-center space-x-3">
        <img src="{{ asset('build/assets/jef.ico') }}" alt="Ícone" class="w-10 h-10 rounded-md shadow-sm">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">JEF - System</h1>    
    </div>

    <!-- Saudação + Logout -->
    <div class="flex items-center space-x-6">
        <span class="text-gray-600 text-base">Olá, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span></span>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-all duration-200 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
                </svg>
                Sair
            </button>
        </form>
    </div>
</div>
