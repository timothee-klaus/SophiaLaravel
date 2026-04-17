<x-layouts.app>
    <div class="px-4 py-8 mx-auto xl:max-w-7xl">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold leading-tight text-slate-800 flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Architecture Académique
            </h2>
            <p class="mt-1 text-sm text-gray-500">Gérez les années académiques et les réinscriptions.</p>
        </div>

        <div class="mb-10">
            @livewire('academic-year-manager')
        </div>

        <div class="mt-6">
            @livewire('reenrollment-manager')
        </div>

        <div class="mt-6">
            @livewire('level-manager')
        </div>
    </div>
</x-layouts.app>
