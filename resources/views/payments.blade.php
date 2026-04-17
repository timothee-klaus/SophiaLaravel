<x-layouts.app>
    <div class="px-4 py-8 mx-auto xl:max-w-7xl">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold leading-tight text-slate-800 flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Paiements & Caisse
            </h2>
            <p class="mt-1 text-sm text-gray-500">Consultez l'historique de la caisse et enregistrez de nouveaux paiements.</p>
        </div>

        <div>
            @livewire('payment-list')
        </div>
    </div>
</x-layouts.app>
