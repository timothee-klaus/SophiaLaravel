<x-layouts.app>
    <div class="px-4 py-8 mx-auto xl:max-w-7xl">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold leading-tight text-slate-800 flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Inscriptions
            </h2>
            <p class="mt-1 text-sm text-gray-500">Gérez les inscriptions et ajoutez de nouveaux élèves.</p>
        </div>

        <div class="mb-10">
            @livewire('financial-reports')
        </div>

        <div>
            @livewire('enrollment-list')
        </div>
    </div>
</x-layouts.app>
