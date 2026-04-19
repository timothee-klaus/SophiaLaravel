<div class="p-8 bg-white/70 backdrop-blur-sm rounded-xl shadow-xl border border-slate-200/60 relative overflow-hidden">
    <!-- Fond décoratif flouté -->
    <div class="absolute -bottom-[20%] -left-[10%] w-[30%] h-[50%] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>

    <div class="flex flex-col mb-8 gap-6 relative z-10 w-full">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Architecture Académique</h3>
                <p class="text-slate-500 text-sm font-medium mt-1">Gérez le cycle de vie et les dates des années scolaires.</p>
            </div>
            
            <button wire:click="createYear" class="w-full md:w-auto px-6 py-2.5 bg-[#0f172a] text-white rounded-xl font-bold shadow-md shadow-black/10 hover:bg-black hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Créer l'année scolaire
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50/50 p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Libellé de l'année</label>
                <input type="text" wire:model="name" placeholder="Ex: 2026-2027" class="w-full pl-4 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:border-[#0f172a] focus:ring-4 focus:ring-[#0f172a]/5 transition-all text-sm font-bold text-slate-700">
            </div>
            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Ouverture (Date Début)</label>
                <input type="date" wire:model="start_date" class="w-full pl-4 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:border-[#0f172a] focus:ring-4 focus:ring-[#0f172a]/5 transition-all text-sm font-bold text-slate-700">
            </div>
            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Fermeture (Archivage)</label>
                <input type="date" wire:model="end_date" class="w-full pl-4 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:border-[#0f172a] focus:ring-4 focus:ring-[#0f172a]/5 transition-all text-sm font-bold text-slate-700">
            </div>
        </div>
        <div class="flex gap-4">
            @error('name') <p class="text-[10px] text-rose-500 font-bold uppercase ml-2">{{ $message }}</p> @enderror
            @error('start_date') <p class="text-[10px] text-rose-500 font-bold uppercase ml-2">{{ $message }}</p> @enderror
            @error('end_date') <p class="text-[10px] text-rose-500 font-bold uppercase ml-2">{{ $message }}</p> @enderror
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 text-xs font-bold text-emerald-700 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 animate-fade-in">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 text-xs font-bold text-rose-700 rounded-xl bg-rose-50 border border-rose-100 flex items-center gap-3 animate-fade-in">
            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div x-data="{ closingYearId: null, deletingYearId: null }" class="overflow-hidden rounded-2xl border border-slate-200 shadow-sm bg-white relative z-10">
        <table class="min-w-full text-left">
            <thead class="bg-slate-50/80 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.1em]">Année & Période</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.1em]">Statut</th>
                    <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.1em]">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($academicYears as $year)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 tracking-tight">{{ $year->name }}</span>
                                <span class="text-[10px] font-medium text-slate-400 uppercase">
                                    {{ $year->start_date ? \Carbon\Carbon::parse($year->start_date)->translatedFormat('d M Y') : 'N/A' }} 
                                    — 
                                    {{ $year->end_date ? \Carbon\Carbon::parse($year->end_date)->translatedFormat('d M Y') : 'N/A' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($year->is_current)
                                <span class="px-2.5 py-1 text-[10px] font-black text-emerald-700 bg-emerald-50 rounded-lg border border-emerald-100 flex inline-flex items-center gap-1.5 uppercase tracking-tighter shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active</span>
                            @elseif($year->is_closed)
                                <span class="px-2.5 py-1 text-[10px] font-black text-slate-500 bg-slate-50 rounded-lg border border-slate-200 uppercase tracking-tighter shadow-sm">Clôturée / Archivée</span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-black text-amber-700 bg-amber-50 rounded-lg border border-amber-100 uppercase tracking-tighter shadow-sm whitespace-nowrap">En attente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$year->is_current && !$year->is_closed)
                                    <button wire:click="setActive({{ $year->id }})" class="px-3 py-1.5 text-[10px] font-black text-emerald-600 border border-emerald-100 rounded-lg hover:bg-[#0f172a] hover:text-white transition-all uppercase tracking-tighter shadow-sm">
                                        Activer
                                    </button>
                                @endif
                                @if(!$year->is_closed)
                                    <button @click="closingYearId = {{ $year->id }}" class="px-3 py-1.5 text-[10px] font-black text-rose-600 border border-rose-100 rounded-lg hover:bg-rose-600 hover:text-white transition-all uppercase tracking-tighter shadow-sm">
                                        Clôturer
                                    </button>
                                @endif
                                <button @click="deletingYearId = {{ $year->id }}" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-400 italic text-sm">
                            Aucune année configurée pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Modals de confirmation (Hors Tableau) -->
        <template x-if="closingYearId !== null">
            <div class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-fade-in">
                <div @click.away="closingYearId = null" class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center animate-scale-up">
                    <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-6 ring-4 ring-amber-50/50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2">Clôturer l'année ?</h3>
                    <p class="text-sm font-medium text-slate-500 mb-8 leading-relaxed">Cette action va geler toutes les données financières pour archivage. Cette opération est délicate.</p>
                    <div class="flex flex-col gap-3">
                        <button @click="$wire.closeYear(closingYearId); closingYearId = null" class="w-full py-3 bg-[#0f172a] text-white rounded-xl font-bold shadow-lg shadow-black/20 hover:bg-black transition-all">Confirmer la Clôture</button>
                        <button @click="closingYearId = null" class="w-full py-3 bg-slate-100 text-slate-500 rounded-xl font-bold hover:bg-slate-200 transition-all">Annuler</button>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="deletingYearId !== null">
            <div class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-fade-in">
                <div @click.away="deletingYearId = null" class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center animate-scale-up">
                    <div class="w-14 h-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-6 ring-4 ring-rose-50/50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2">Supprimer l'année ?</h3>
                    <p class="text-sm font-medium text-slate-500 mb-8 leading-relaxed">L'année sera définitivement supprimée. Les inscriptions liées empêcheront la suppression pour votre sécurité.</p>
                    <div class="flex flex-col gap-3">
                        <button @click="$wire.deleteYear(deletingYearId); deletingYearId = null" class="w-full py-3 bg-rose-600 text-white rounded-xl font-bold shadow-lg shadow-rose-600/20 hover:bg-rose-700 transition-all">Oui, Supprimer</button>
                        <button @click="deletingYearId = null" class="w-full py-3 bg-slate-100 text-slate-500 rounded-xl font-bold hover:bg-slate-200 transition-all">Annuler</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
