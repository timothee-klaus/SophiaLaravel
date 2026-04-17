<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Inscription' }} | Sophia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @livewireStyles
</head>
<body class="font-['Inter'] antialiased bg-white min-h-screen flex text-slate-800">
    
    <!-- Left Side - Image & Branding (Same as Login) -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900 border-r border-slate-200">
        <img src="{{ asset('images/campus-bg.png') }}" class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-overlay" alt="Campus">
        <div class="absolute inset-0 bg-gradient-to-t from-[#1e3a8a]/90 via-[#1e3a8a]/60 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#1e3a8a]/80 to-transparent"></div>

        <div class="relative z-10 flex flex-col justify-between p-16 w-full h-full text-white">
            <div>
                <div class="w-14 h-14 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center mb-6 shadow-xl relative overflow-hidden">
                    <span class="text-white text-2xl font-black italic relative z-10">S</span>
                </div>
                
                <h1 class="text-3xl font-black tracking-widest uppercase mb-1">Institut Scolaire Sophia</h1>
                <p class="text-sm font-semibold text-blue-200 uppercase tracking-[0.2em] italic mb-6">« Le Don De Dieu »</p>
                
                <div class="w-16 h-1 bg-white/30 rounded-full mb-8"></div>
                
                <p class="text-lg font-medium text-slate-100 max-w-md leading-relaxed text-balance">
                    Système de Gestion Administrative Intégré.<br>
                    Demande d'accès pour le personnel administratif.
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-300 font-medium tracking-wide">© {{ date('Y') }} Sophia. Excellence & Intégrité.</p>
            </div>
        </div>
    </div>

    <!-- Right Side - Dynamic Content -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-slate-50 relative">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="w-full max-w-md px-8 py-10">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>
