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
        <!-- Background Image -->
        <img src="{{ asset('images/campus-bg.png') }}" class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-overlay" alt="Campus">
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#1e3a8a]/90 via-[#1e3a8a]/60 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#1e3a8a]/80 to-transparent"></div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-between p-16 w-full h-full text-white">
            <div>
                <!-- Custom "S" Logo -->
                <div class="w-14 h-14 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center mb-6 shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-400/20 to-purple-400/20 translate-y-full hover:translate-y-0 transition-transform duration-500"></div>
                    <span class="text-white text-2xl font-black italic relative z-10">S</span>
                </div>
                
                <h1 class="text-3xl font-black tracking-widest uppercase mb-1">Institut Scolaire Sophia</h1>
                <p class="text-sm font-semibold text-blue-200 uppercase tracking-[0.2em] italic mb-6">« Le Don De Dieu »</p>
                
                <div class="w-16 h-1 bg-white/30 rounded-full mb-8"></div>
                
                <p class="text-lg font-medium text-slate-100 max-w-md leading-relaxed text-balance">
                    L'excellence académique à portée de main.<br>
                    Bienvenue sur votre portail d'administration sécurisé.
                </p>

                <!-- Benefits Section -->
                <div class="mt-16 relative">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-400 mb-10 flex items-center gap-4">
                        <span class="w-12 h-[1px] bg-blue-500/40"></span>
                        Pourquoi choisir cette plateforme ?
                    </h3>
                    
                    <div class="space-y-10 ml-4 border-l border-white/10 pl-8 relative">
                        <!-- Strategic -->
                        <div class="relative group">
                            <div class="absolute -left-[37px] top-1.5 w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.6)] group-hover:scale-125 transition-transform duration-300"></div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1.5 tracking-wide">Vision Stratégique</h4>
                                <p class="text-[11px] text-slate-300 font-medium leading-relaxed opacity-70">
                                    Centralisation des flux financiers et suivi analytique en temps réel pour une gouvernance éclairée.
                                </p>
                            </div>
                        </div>

                        <!-- Operational -->
                        <div class="relative group">
                            <div class="absolute -left-[37px] top-1.5 w-2 h-2 rounded-full bg-white/20 group-hover:bg-blue-400/50 transition-colors duration-300"></div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1.5 tracking-wide">Performance Opérationnelle</h4>
                                <p class="text-[11px] text-slate-300 font-medium leading-relaxed opacity-70">
                                    Saisie ultra-rapide des inscriptions, automatisation des reçus et recherche instantanée des dossiers.
                                </p>
                            </div>
                        </div>

                        <!-- Security -->
                        <div class="relative group">
                            <div class="absolute -left-[37px] top-1.5 w-2 h-2 rounded-full bg-white/20 group-hover:bg-blue-400/50 transition-colors duration-300"></div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1.5 tracking-wide">Sécurité & Intégrité</h4>
                                <p class="text-[11px] text-slate-300 font-medium leading-relaxed opacity-70">
                                    Protection rigoureuse des données institutionnelles et traçabilité complète de chaque transaction.
                                </p>
                            </div>
                        </div>

                        <!-- Archiving -->
                        <div class="relative group">
                            <div class="absolute -left-[37px] top-1.5 w-2 h-2 rounded-full bg-white/20 group-hover:bg-blue-400/50 transition-colors duration-300"></div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1.5 tracking-wide">Archivage Digital Pérenne</h4>
                                <p class="text-[11px] text-slate-300 font-medium leading-relaxed opacity-70">
                                    Historisation complète sans papier, facilitant l'audit et la pérennité de la mémoire scolaire.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer left -->
            <div>
                <p class="text-sm text-slate-300 font-medium tracking-wide">© {{ date('Y') }} Sophia. Tous droits réservés.</p>
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
