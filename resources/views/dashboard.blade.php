<x-layouts.app>
    <div class="px-4 py-8 mx-auto xl:max-w-7xl">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold leading-tight text-slate-800 flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Tableau de Bord
            </h2>
            <p class="mt-1 text-sm text-slate-500">Aperçu général des chiffres et flux financiers de l'Institut.</p>
        </div>

        <div class="mt-6">
            @livewire('secretary-dashboard')
        </div>
    </div>
</x-layouts.app>


