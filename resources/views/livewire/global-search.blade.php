<div class="relative w-full max-w-lg z-50">
    <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input wire:model.live.debounce.300ms="query" type="search"
            class="block w-full py-2 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 transition-colors bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-800 focus:border-blue-800 focus:outline-none focus:bg-white"
            placeholder="Rechercher un élève (nom ou matricule)..." autocomplete="off">
    </div>
    @if(strlen($query) > 1)
        <div class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg top-full">
            @if(count($results) > 0)
                <ul class="py-1 overflow-auto text-sm text-gray-700 max-h-60">
                    @foreach($results as $student)
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">
                                <div class="font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</div>
                                <div class="flex items-center mt-1 text-xs text-gray-500">
                                    <span class="px-2 py-0.5 bg-gray-100 rounded text-gray-600 border border-gray-200 mr-2">{{ $student->matricule }}</span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-4 py-3 text-sm text-gray-500">
                    Aucun élève trouvé pour "{{ $query }}"
                </div>
            @endif
        </div>
    @endif
</div>
