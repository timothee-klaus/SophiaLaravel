<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Explorateur de Base de Données</h1>
                <p class="text-slate-500 font-medium">Diagnostic direct des tables de l'application Sophia</p>
            </div>
            <div class="bg-blue-100 text-[#1e3a8a] px-4 py-2 rounded-lg font-bold text-sm">
                {{ count($tables) }} Tables détectées
            </div>
        </div>

        <div class="grid grid-cols-12 gap-8">
            <!-- Sidebar: Table List -->
            <div class="col-span-4 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="font-bold text-slate-700 uppercase p-1 tracking-wider text-xs">Liste des Tables</h2>
                </div>
                <div class="overflow-y-auto max-h-[600px]">
                    @foreach($tables as $table)
                        <button wire:click="selectTable('{{ $table['name'] }}')" 
                            class="w-full text-left p-4 hover:bg-blue-50 transition-all border-b border-slate-50 flex items-center justify-between {{ $selectedTable === $table['name'] ? 'bg-blue-50 border-l-4 border-l-[#1e3a8a]' : '' }}">
                            <span class="font-bold text-slate-800">{{ $table['name'] }}</span>
                            <span class="bg-slate-100 text-slate-500 px-2 py-1 rounded text-xs font-black">{{ $table['count'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Main Content: Data View -->
            <div class="col-span-8 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden min-h-[600px]">
                @if($selectedTable)
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <h2 class="font-bold text-[#1e3a8a] uppercase tracking-wider text-xs">Données : {{ $selectedTable }}</h2>
                        <span class="text-[10px] text-slate-400 font-bold uppercase">50 derniers enregistrements</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50">
                                <tr>
                                    @foreach($columns as $column)
                                        <th class="px-4 py-3 text-[10px] font-black uppercase text-slate-500 border-b border-slate-200">{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($tableData as $row)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        @foreach($columns as $column)
                                            <td class="px-4 py-3 text-sm text-slate-600 font-medium">
                                                {{ Str::limit($row[$column] ?? 'NULL', 30) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($columns) }}" class="p-12 text-center text-slate-400 italic">Table vide</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center p-20 text-center">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.58 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.58 4 8 4s8-1.79 8-4M4 7c0-2.21 3.58-4 8-4s8 1.79 8 4m0 5c0 2.21-3.58 4-8 4s-8-1.79-8-4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Sélectionnez une table</h3>
                        <p class="text-sm text-slate-500 max-w-xs mx-auto">Choisissez une table dans la liste de gauche pour explorer les données réelles enregistrées par l'application.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
