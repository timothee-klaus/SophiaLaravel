<div class="p-8 bg-white/70 backdrop-blur-sm rounded-xl shadow-xl border border-slate-200/60 relative overflow-hidden">
    <!-- Fond décoratif flouté -->
    <div class="absolute -bottom-[20%] -left-[10%] w-[30%] h-[50%] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>

    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4 relative z-10 w-full">
        <div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Années Académiques</h3>
            <p class="text-slate-500 text-sm mt-1">Gérez le statut des différents cycles.</p>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto">
            <input type="text" wire:model="name" placeholder="Ex: 2026-2027" class="pl-4 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-slate-700 transition-colors w-full md:w-48">
            <button wire:click="createYear" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Créer l'année
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 text-sm text-emerald-800 rounded-xl bg-emerald-50/80 border border-emerald-200 backdrop-blur-sm relative z-10" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div x-data="{ closingYearId: null }" class="overflow-x-auto rounded-xl border border-slate-200/60 shadow-sm bg-white relative z-10">
        <table class="min-w-full text-left whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Année Scolaire</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions Rapides</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($academicYears as $year)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-150 ease-in-out group">
                        <td class="px-6 py-4 font-extrabold text-slate-800 tracking-wide">{{ $year->name }}</td>
                        <td class="px-6 py-4">
                            @if($year->is_current)
                                <span class="px-3 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded-lg border border-emerald-200 flex inline-flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active</span>
                            @elseif($year->is_closed)
                                <span class="px-3 py-1 text-xs font-bold text-rose-700 bg-rose-100 rounded-lg border border-rose-200">Clôturée (Archives)</span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold text-slate-600 bg-slate-100 rounded-lg border border-slate-200">En attente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                            @if(!$year->is_current && !$year->is_closed)
                                <button wire:click="setActive({{ $year->id }})" class="px-3 py-1.5 text-xs font-bold text-emerald-600 border border-emerald-200 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Activer
                                </button>
                            @endif
                            @if(!$year->is_closed)
                                <button @click="closingYearId = {{ $year->id }}" class="px-3 py-1.5 text-xs font-bold text-rose-600 border border-rose-200 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Clôturer
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center opacity-60">
                                <svg class="w-10 h-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-sm font-medium text-slate-500">Aucune année configurée.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Modal de confirmation interactif Alpine JS -->
        <div x-show="closingYearId !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;">
            <div @click.away="closingYearId = null" class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 transform transition-all">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-rose-100 rounded-full mb-4 ring-4 ring-rose-50">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-extrabold text-center text-slate-800">Clôturer l'année académique</h3>
                <p class="text-sm text-center font-medium text-slate-500 mt-2 mb-6">Voulez-vous vraiment clôturer cette année ? L'historique des opérations financières et les inscriptions seront définitivement gelés et conservés dans les archives.</p>
                <div class="flex justify-center gap-3">
                    <button @click="closingYearId = null" class="px-5 py-2.5 font-bold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">Annuler</button>
                    <button @click="$wire.closeYear(closingYearId); closingYearId = null" class="px-5 py-2.5 font-bold text-white bg-gradient-to-r from-rose-500 to-rose-600 rounded-xl hover:shadow-lg hover:shadow-rose-500/30 transition-all hover:-translate-y-0.5">Oui, Clôturer</button>
                </div>
            </div>
        </div>
    </div>
</div>
