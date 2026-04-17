<header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200 shadow-sm z-10 w-full transition-colors duration-300">
    <!-- Recherche Globale via Livewire -->
    <div class="flex-1 max-w-lg">
        @livewire('global-search')
    </div>

    <!-- Actions Rapides & Profil (Composant Livewire) -->
    @livewire('header-actions')
</header>
