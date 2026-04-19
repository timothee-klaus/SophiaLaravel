<div class="p-6 bg-white rounded-lg shadow-sm">
    <h3 class="mb-4 text-xl font-bold">Paramètres de l'Établissement</h3>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom de l'établissement</label>
                <input type="text" wire:model="name" class="w-full px-4 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Slogan</label>
                <input type="text" wire:model="slogan" class="w-full px-4 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                @error('slogan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Adresse</label>
                <input type="text" wire:model="address" class="w-full px-4 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                @error('address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphones</label>
                <input type="text" wire:model="phones" class="w-full px-4 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                @error('phones') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="text" wire:model="email" class="w-full px-4 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Logo</label>
                @if ($currentLogoPath)
                    <div class="mb-2">
                        <img src="{{ Storage::url($currentLogoPath) }}" alt="Logo actuel" class="h-16">
                    </div>
                @endif
                <input type="file" wire:model="logo" class="w-full px-4 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <div wire:loading wire:target="logo" class="text-sm text-[#0f172a]">Téléchargement en cours...</div>
                @error('logo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="px-6 py-2 text-white bg-[#0f172a] rounded hover:bg-black transition-colors">Enregistrer les paramètres</button>
        </div>
    </form>
</div>

