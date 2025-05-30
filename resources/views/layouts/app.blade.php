<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- CSS Compilado -->
    {{-- Se você estiver usando Vite (Laravel 10+), use: --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Se estiver usando Laravel Mix, mantenha sua linha atual: --}}
    <!--{{-- <link rel="stylesheet" href="{{ asset('build/assets/app-DshOE-Xa.css') }}"> --}}-->
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            @yield('content')
            
        </main>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
    <div 
    x-data="{ message: '', show: false }"
    x-on:notify.window="
        message = $event.detail.message;
        show = true;
        setTimeout(() => show = false, 4000);
    "
    x-show="show"
    x-transition
    class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50"
    style="display: none;"
>
    <span x-text="message"></span>
</div>
    <!-- JS Compilado -->
    {{-- Se estiver usando Vite, já está incluso no @vite --}}
    {{-- Se estiver usando Laravel Mix: --}}
    <!--{{-- <script src="{{ asset('build/assets/app-Bf4POITK.js') }}" defer></script> --}} -->

</body>
</html>
