<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-[1600px] mx-auto px-8">
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-6">
                <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl font-black italic">S</span>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Console de Gestion de Données</h1>
                    <p class="text-slate-500 font-medium">Accès direct et complet aux tables de production</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 text-[#1e3a8a] px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm border border-blue-200">
                    {{ count($tables) }} Tables actives
                </div>
                <a href="/" class="text-slate-400 hover:text-slate-600 font-bold text-sm transition-colors decoration-none">Retour au site →</a>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-10">
            <!-- Sidebar: Table List -->
            <div class="col-span-3 bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden self-start">
                <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="font-black text-slate-400 uppercase tracking-[0.2em] text-[10px]">Structure de la base</h2>
                </div>
                <div class="overflow-y-auto max-h-[700px] divide-y divide-slate-50">
                    @foreach($tables as $table)
                        <button wire:click="selectTable('{{ $table['name'] }}')" 
                            class="w-full text-left p-5 hover:bg-slate-50 transition-all flex items-center justify-between group {{ $selectedTable === $table['name'] ? 'bg-blue-50 border-r-4 border-r-[#1e3a8a]' : '' }}">
                            <div class="flex flex-col">
                                <span class="font-bold text-sm {{ $selectedTable === $table['name'] ? 'text-[#1e3a8a]' : 'text-slate-700 font-medium' }}">{{ $table['name'] }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $table['count'] }} lignes</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Main Content: Data View -->
            <div class="col-span-9 bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden min-h-[700px]">
                @if($selectedTable)
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <h2 class="font-black text-slate-900 uppercase tracking-widest text-xs mb-1">Affichage de la table : <span class="text-[#1e3a8a]">{{ $selectedTable }}</span></h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Aperçu des 50 derniers enregistrements</p>
                        </div>
                        <div class="flex gap-3">
                             <!-- Action buttons could go here (export, refresh, etc) -->
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50">
                                    @foreach($columns as $column)
                                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 border-b border-slate-100">{{ $column }}</th>
                                    @endforeach
                                    <th class="px-6 py-4 text-[10px] font-black uppercase text-red-400 border-b border-slate-100 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($tableData as $row)
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        @foreach($columns as $column)
                                            <td class="px-6 py-4 text-sm text-slate-600 font-medium whitespace-nowrap">
                                                @if(is_array($row[$column] ?? null))
                                                    <span class="text-[10px] bg-slate-100 px-1 rounded">ARRAY</span>
                                                @else
                                                    {{ Str::limit(strip_tags((string)($row[$column] ?? 'NULL')), 40) }}
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4 text-right">
                                            @if(isset($row['id']))
                                                <button 
                                                    wire:click="deleteRow({{ $row['id'] }})"
                                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cet enregistrement ?"
                                                    class="text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-lg transition-all opacity-0 group-hover:opacity-100"
                                                    title="Supprimer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($columns) + 1 }}" class="p-20 text-center">
                                            <p class="text-slate-400 font-bold italic">Aucun enregistrement trouvé</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center p-32 text-center">
                        <div class="w-24 h-24 bg-blue-50 rounded-3xl flex items-center justify-center mb-8 shadow-inner">
                            <svg class="w-12 h-12 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 002-2h6a2 2 0 002 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-2">Explorateur Sophia</h3>
                        <p class="text-slate-500 font-medium max-w-sm mx-auto leading-relaxed">
                            Veuillez sélectionner une table dans la colonne de gauche pour commencer la gestion de vos données.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
