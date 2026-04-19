<x-layouts.app>
    <div class="px-4 py-8 mx-auto xl:max-w-7xl">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold leading-tight text-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#0f172a] flex items-center justify-center text-white shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                Mon Profil & Sécurité
            </h2>
            <p class="mt-1 text-sm text-slate-500 font-medium">Gérez vos informations personnelles et la sécurité de votre compte.</p>
        </div>

        <div class="space-y-10">
            @livewire('user-profile-manager')
            @livewire('user-password-manager')
            @livewire('system-settings-manager')
        </div>
    </div>
</x-layouts.app>
