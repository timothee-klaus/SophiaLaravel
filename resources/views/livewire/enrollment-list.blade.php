<div x-data="{ showModal: @entangle('showModal'), deletingEnrollmentId: null }">
    <div class="p-8 bg-white/70 backdrop-blur-sm rounded-xl shadow-xl border border-slate-200/60 relative overflow-hidden">
        <!-- Fond décoratif flouté -->
        <div class="absolute -top-[10%] -right-[10%] w-[30%] h-[50%] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>

    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4 relative z-10">
        <div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Outils d'inscription</h3>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="relative">
                <select wire:model.live="filterLevelId" class="appearance-none pl-4 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-slate-700 font-medium transition-colors">
                    <option value="">Toutes les classes</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher (nom, matricule)..." class="pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm w-64 text-slate-700 transition-colors">
            </div>

            <button @click="showModal = true" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 transition-all duration-300">
                + Nouvelle Inscription
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 text-sm text-emerald-800 rounded-xl bg-emerald-50/80 border border-emerald-200 backdrop-blur-sm relative z-10" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-slate-200/60 shadow-sm bg-white relative z-10">
        <table class="min-w-full text-left whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Matricule</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Élève</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Classe</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date d'inscription</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($enrollments as $enrollment)
                    <tr wire:key="enrollment-{{ $enrollment->id }}" class="hover:bg-slate-50/80 transition-colors duration-150 ease-in-out group">
                        <td class="px-6 py-4 font-mono text-sm text-slate-500 group-hover:text-blue-600 transition-colors">{{ $enrollment->student->matricule }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ mb_strtoupper($enrollment->student->last_name) }} {{ $enrollment->student->first_name }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $enrollment->level->name }}</td>
                        <td class="px-6 py-4">
                            @if($enrollment->status == 'active')
                                <span class="px-3 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded-lg border border-emerald-200">Actif</span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold text-slate-700 bg-slate-100 rounded-lg border border-slate-200">{{ ucfirst($enrollment->status ?? 'Inconnu') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $enrollment->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <button @click="deletingEnrollmentId = {{ $enrollment->id }}" class="text-rose-500 py-1.5 px-3 rounded-lg hover:bg-rose-50 transition-colors font-semibold text-xs border border-transparent hover:border-rose-200">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4 opacity-70">
                                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <div class="max-w-sm">
                                    <h4 class="text-lg font-bold text-slate-700 tracking-tight">Aucune donnée</h4>
                                    <p class="text-sm font-medium text-slate-500 mt-1">Aucune inscription trouvée.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6 relative z-10">
        {{ $enrollments->links() }}
    </div>
</div>

    <!-- Modal Popup pour le Formulaire d'inscription -->
    <div x-show="showModal" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="showModal = false" class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto relative transform transition-all">
            <div class="sticky top-0 bg-white/90 backdrop-blur-md border-b border-slate-100 px-8 py-5 flex justify-between items-center z-10">
                <h3 class="text-2xl font-bold text-slate-800">Formulaire d'Inscription</h3>
                <button @click="showModal = false" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Injection du Livewire Component -->
            <div class="p-6 md:p-8 bg-slate-50/30">
                @livewire('student-enrollment')
            </div>
        </div>
    </div>
    <!-- Modal de confirmation de Suppression Alpine JS -->
    <div x-show="deletingEnrollmentId !== null" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;">
        <div @click.away="deletingEnrollmentId = null" class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 transform transition-all">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-rose-100 rounded-full mb-4 ring-4 ring-rose-50">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-xl font-extrabold text-center text-slate-800">Supprimer l'inscription</h3>
            <p class="text-sm text-center font-medium text-slate-500 mt-2 mb-6">Êtes-vous sûr de vouloir supprimer cette inscription et tous ses paiements associés ? Cette action est irréversible.</p>
            <div class="flex justify-center gap-3">
                <button @click="deletingEnrollmentId = null" class="px-5 py-2.5 font-bold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">Annuler</button>
                <button @click="$wire.deleteEnrollment(deletingEnrollmentId); deletingEnrollmentId = null" class="px-5 py-2.5 font-bold text-white bg-gradient-to-r from-rose-500 to-rose-600 rounded-xl hover:shadow-lg hover:shadow-rose-500/30 transition-all hover:-translate-y-0.5">Oui, Supprimer</button>
            </div>
        </div>
    </div>
</div>
