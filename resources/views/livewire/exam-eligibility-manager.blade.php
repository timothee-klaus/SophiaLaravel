<div x-data="{ showUnblockModal: @entangle('showUnblockModal') }">
    <div class="p-8 bg-white/70 backdrop-blur-sm rounded-xl shadow-xl border border-slate-200/60 relative overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] w-[30%] h-[50%] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>
    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4 relative z-10 w-full">
        <h3 class="text-lg font-bold text-slate-800">Suivi des Retards de Paiement</h3>

        <div class="flex items-center gap-4">
            <select wire:model.live="selectedCycle" class="px-4 py-2 border rounded-md">
                <option value="">Tous les cycles</option>
                <option value="preschool">Préscolaire</option>
                <option value="primary">Primaire</option>
                <option value="college">Collège</option>
                <option value="lycee">Lycée</option>
            </select>

            <select wire:model.live="selectedLevelId" class="px-4 py-2 border rounded-md min-w-[200px]">
                <option value="">Sélectionnez une classe</option>
                @foreach($levels as $level)
                    @if(empty($selectedCycle) || $level->cycle === $selectedCycle)
                        <option value="{{ $level->id }}">{{ $level->name }} ({{ strtoupper($level->cycle) }})</option>
                    @endif
                @endforeach
            </select>

            <button wire:click="exportPdf"
                    @if(empty($selectedLevelId)) disabled @endif
                    class="px-4 py-2 bg-[#1e3a8a] text-white rounded-md font-medium hover:bg-blue-800 disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Liste d'émargement
            </button>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('message') }}
        </div>
    @endif

    @if(count($enrollments) > 0)
    <div class="overflow-x-auto rounded-xl border border-slate-200/60 shadow-sm bg-white relative z-10 mt-6">
        <table class="min-w-full text-left whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Matricule</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nom & Prénom</th>
                    @if(!$selectedLevelId)
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Classe</th>
                    @endif
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Statut Paiement</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Conséquence</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($enrollments as $enrollment)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                        <td class="px-6 py-4 font-bold text-slate-800 tracking-tighter text-xs">{{ $enrollment->student->matricule }}</td>
                        <td class="px-6 py-4">
                            <div class="font-black text-slate-700 uppercase tracking-tight text-xs leading-tight">
                                {{ mb_strtoupper($enrollment->student->last_name) }}<br>
                                <span class="text-slate-500 font-bold capitalize">{{ $enrollment->student->first_name }}</span>
                            </div>
                        </td>
                        @if(!$selectedLevelId)
                            <td class="px-6 py-4 text-[10px] font-black text-[#1e3a8a] uppercase">{{ $enrollment->level->name }}</td>
                        @endif
                        <td class="px-6 py-4 text-center">
                            @if($enrollment->is_up_to_date)
                                <span class="bg-emerald-50 text-emerald-600 text-[10px] font-black px-2 py-1 rounded-md border border-emerald-100 uppercase tracking-wider">À Jour</span>
                            @else
                                <span class="bg-amber-50 text-amber-600 text-[10px] font-black px-2 py-1 rounded-md border border-amber-100 uppercase tracking-wider">En Retard</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(!$enrollment->is_eligible)
                                <span class="bg-rose-50 text-rose-600 text-[10px] font-black px-2 py-1 rounded-md border border-rose-100 uppercase tracking-wider shadow-sm">BLOQUÉ EXAMEN</span>
                                @if($enrollment->is_manually_unblocked)
                                    <p class="text-[9px] italic text-slate-400 mt-1">Dérogation : {{ $enrollment->manual_exam_unblock_reason }}</p>
                                @endif
                            @else
                                <span class="text-slate-300 text-[10px] font-bold">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(!$enrollment->is_eligible)
                                <button wire:click="openUnblockModal({{ $enrollment->id }})" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                </button>
                            @else
                                <span class="text-slate-300 text-xs font-bold">RAS</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="text-center py-20 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200 mt-8">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h4 class="text-slate-600 font-bold">Chargement de la liste globale...</h4>
            <p class="text-slate-400 text-xs mt-1">Utilisez les filtres pour affiner par cycle ou par classe.</p>
        </div>
    @endif
</div>

    <!-- Unblock Modal -->
    <div x-show="showUnblockModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50" style="display: none;">
        <div @click.away="showUnblockModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden relative">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 text-red-600">Levée Exceptionnelle de Blocage</h3>
                <button @click="showUnblockModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">La levée de blocage permet à un élève non en règle d'accéder aux examens de façon exceptionnelle. Cette action doit être validée par la direction.</p>
                <form wire:submit.prevent="unblockStudent">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Motif de la levée de blocage</label>
                        <textarea wire:model="unblockReason" rows="3" class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="Ex: Accord parental jusqu'au 15 Janvier, Décision du Directeur..."></textarea>
                        @error('unblockReason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showUnblockModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition shadow" wire:loading.attr="disabled">Valider la levée</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

