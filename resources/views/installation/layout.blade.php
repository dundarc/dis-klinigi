<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Kurulum</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
            <div class="mb-4 text-sm text-gray-600 text-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto h-20 mb-4">
                <div class="text-2xl font-bold mb-2">Diş Kliniği Yönetim Sistemi</div>
                <div class="text-gray-500">Kurulum Sihirbazı</div>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex justify-between mb-1">
                    @foreach(['Hoşgeldiniz', 'Gereksinimler', 'Veritabanı', 'Klinik', 'Yönetici', 'Tamamlandı'] as $step)
                        <div class="text-xs {{ Request::is('install/' . strtolower($step)) ? 'text-blue-600' : 'text-gray-500' }}">
                            {{ $step }}
                        </div>
                    @endforeach
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    @php
                        $steps = [
                            'install' => 0,
                            'install/requirements' => 20,
                            'install/database' => 40,
                            'install/clinic' => 60,
                            'install/admin' => 80,
                            'install/complete' => 100,
                        ];
                        $currentProgress = $steps[Request::path()] ?? 0;
                    @endphp
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $currentProgress }}%"></div>
                </div>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>