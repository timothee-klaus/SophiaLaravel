<aside class="flex flex-col w-72 h-full bg-[#0f172a] text-blue-50 shadow-2xl z-20 border-r border-white/5 overflow-hidden">
    <!-- En-tête / Logo -->
    <div class="relative flex flex-col items-center justify-center min-h-[5.5rem] border-b border-white/5 text-center px-4 bg-black/20"
        @php
            $setting = \App\Models\SchoolSetting::first() ?? (object)[
                'name' => 'Institut Scolaire Sophia',
                'logo_path' => null,
                'slogan' => '«Le Don De Dieu»'
            ];
        @endphp

        <!-- Subtly glowing background effect -->
        <div class="absolute inset-0 bg-blue-400/5 pointer-events-none"></div>

        <h1 class="relative text-[10px] font-black tracking-[0.1em] text-white uppercase leading-tight" title="{{ $setting->name }}">
            Institut Scolaire Sophia
        </h1>
        <p class="relative text-[10px] font-extrabold text-blue-200 uppercase mt-1.5 tracking-widest italic">
            &laquo; Le Don De Dieu &raquo;
        </p>
    </div>

    <!-- Menu de Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" wire:navigate class="group flex items-center gap-3 px-3.5 py-3 text-sm font-medium transition-all duration-300 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-white text-[#1e3a8a] shadow-lg shadow-blue-950/40' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-[#1e3a8a]' : 'text-blue-100/50 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>

        <!-- Inscriptions -->
        <a href="{{ route('enrollments.new') }}" wire:navigate class="group flex items-center gap-3 px-3.5 py-3 text-sm font-medium transition-all duration-300 rounded-xl {{ request()->routeIs('enrollments.new') ? 'bg-white text-[#1e3a8a] shadow-lg shadow-blue-950/40' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('enrollments.new') ? 'text-[#1e3a8a]' : 'text-blue-100/50 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Inscriptions
        </a>

        <!-- Années Académiques -->
        <a href="{{ route('academic-years') }}" wire:navigate class="group flex items-center gap-3 px-3.5 py-3 text-sm font-medium transition-all duration-300 rounded-xl {{ request()->routeIs('academic-years') ? 'bg-white text-[#1e3a8a] shadow-lg shadow-blue-950/40' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('academic-years') ? 'text-[#1e3a8a]' : 'text-blue-100/50 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Années Académiques
        </a>

        <!-- Élèves -->
        <a href="{{ route('students.index') }}" wire:navigate class="group flex items-center gap-3 px-3.5 py-3 text-sm font-medium transition-all duration-300 rounded-xl {{ request()->routeIs('students.*') ? 'bg-white text-[#1e3a8a] shadow-lg shadow-blue-950/40' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('students.*') ? 'text-[#1e3a8a]' : 'text-blue-100/50 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Élèves
        </a>

        <!-- Suivi des Retards -->
        <a href="{{ route('exam-eligibility') }}" wire:navigate class="group flex items-center gap-3 px-3.5 py-3 text-sm font-medium transition-all duration-300 rounded-xl {{ request()->routeIs('exam-eligibility') ? 'bg-white text-[#1e3a8a] shadow-lg shadow-blue-950/40' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('exam-eligibility') ? 'text-[#1e3a8a]' : 'text-blue-100/50 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Suivi des Retards
        </a>

        <!-- Paiements -->
        <a href="{{ route('payments') }}" wire:navigate class="group flex items-center gap-3 px-3.5 py-3 text-sm font-medium transition-all duration-300 rounded-xl {{ request()->routeIs('payments') ? 'bg-white text-[#1e3a8a] shadow-lg shadow-blue-950/40' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('payments') ? 'text-[#1e3a8a]' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Paiements
        </a>

        <!-- Paramètres -->
        <a href="{{ route('settings') }}" wire:navigate class="group flex items-center gap-3 px-3.5 py-3 text-sm font-medium transition-all duration-300 rounded-xl {{ request()->routeIs('settings') ? 'bg-white text-[#1e3a8a] shadow-lg shadow-blue-950/40' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('settings') ? 'text-[#1e3a8a]' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Paramètres
        </a>
    </nav>

    <!-- Informations Utilisateur -->
    <div class="px-4 py-4 border-t border-white/5 bg-black/20">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 font-bold text-[#1e3a8a] bg-white rounded-xl shadow-lg ring-2 ring-blue-700">
                S<!-- Remplacer par $user->initials -->
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-semibold text-white">Secrétariat</span>
                <span class="text-xs text-blue-300">admin@sophia.edu</span>
            </div>
        </div>
        
        <!-- Déconnexion bouton rapide via Logout -->
        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 text-sm font-medium text-blue-100 bg-white/10 border border-white/10 rounded-lg hover:text-white hover:bg-white/20 transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Déconnexion
            </button>
        </form>
    </div>
</aside>
