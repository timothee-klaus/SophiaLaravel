<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Sophia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-['Inter'] antialiased bg-white min-h-screen flex text-slate-800">
    
    <!-- Left Side - Image & Branding -->
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

                <!-- Benefits Section Redesign -->
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

    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-slate-50 relative">
        <!-- Soft decorative glow -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="w-full max-w-md px-8 py-10">
            <!-- Mobile Header (Hidden on Desktop) -->
            <div class="lg:hidden flex flex-col items-center mb-10">
                <div class="w-14 h-14 bg-[#1e3a8a] rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <span class="text-white text-2xl font-black italic">S</span>
                </div>
                <h1 class="text-xl font-black tracking-[0.1em] text-slate-800 uppercase text-center">Institut Scolaire Sophia</h1>
                <p class="text-[10px] font-bold text-blue-600 uppercase mt-1 tracking-widest italic">« Le Don De Dieu »</p>
            </div>

            <h2 class="text-3xl font-bold text-slate-900 mb-2 mt-4 lg:mt-0 tracking-tight">Connexion</h2>
            <p class="text-sm text-slate-500 mb-8 font-medium">Veuillez entrer vos identifiants pour accéder à Sophia.</p>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 border border-red-100 px-4 py-3 rounded-xl text-sm mb-4 flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium">{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="space-y-1">
                    <label for="email" class="block text-sm font-semibold text-slate-700">Adresse E-mail</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="block w-full pl-11 pr-4 py-3.5 border-slate-200 rounded-xl leading-5 bg-white shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] sm:text-sm font-medium transition-all" 
                            placeholder="exemple@email.com">
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Mot de passe</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="block w-full pl-11 pr-4 py-3.5 border-slate-200 rounded-xl leading-5 bg-white shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] sm:text-sm font-medium transition-all" 
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" 
                            class="h-4 w-4 text-[#1e3a8a] focus:ring-[#1e3a8a] border-slate-300 rounded cursor-pointer transition-colors">
                        <label for="remember-me" class="ml-2.5 block text-sm font-medium text-slate-600 cursor-pointer">
                            Rester connecté
                        </label>
                    </div>
                    <a href="#" class="text-sm font-bold text-[#1e3a8a] hover:text-blue-800 transition-colors">Mot de passe oublié?</a>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-[#1e3a8a]/20 text-sm font-bold text-white bg-[#1e3a8a] hover:bg-[#152c6e] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a] hover:-translate-y-0.5 transition-all duration-300">
                        Connexion à l'Espace
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
            
            <div class="mt-12 pt-8 border-t border-slate-200">
                <p class="text-center text-xs font-medium text-slate-500 mb-4">
                    Pas encore de compte ? <a href="{{ route('register-secretary') }}" class="text-[#1e3a8a] font-bold hover:underline">Demande d'accès secrétaire</a>
                </p>
                <p class="text-center text-xs font-medium text-slate-500">
                    Problème de connexion ? <a href="#" class="text-[#1e3a8a] font-bold hover:underline">Contactez le support IT</a>
                </p>
            </div>

        </div>
    </div>

</body>
</html>
