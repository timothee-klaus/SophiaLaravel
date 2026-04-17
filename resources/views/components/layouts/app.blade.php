<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Institut Sophia') }} - Secrétariat</title>

    <!-- Scripts via Vite pour Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <x-layouts.sidebar />

    <!-- Conteneur Principal -->
    <div class="flex flex-col flex-1 overflow-hidden">

        <!-- Header / Navbar -->
        <x-layouts.header />

        <!-- Zone de Contenu Principal -->
        <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>

    </div>

    @livewireScripts
</body>
</html>

