<div x-data="{ showUnblockModal: @entangle('showUnblockModal') }">
    <div class="p-8 bg-white/70 backdrop-blur-sm rounded-xl shadow-xl border border-slate-200/60 relative overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] w-[30%] h-[50%] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>
    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4 relative z-10 w-full">
        <h3 class="text-lg font-bold text-slate-800">Filtres de recherche</h3>

        <div class="flex items-center gap-4">
            <select wire:model.live="selectedLevelId" class="px-4 py-2 border rounded-md">
                <option value="">Sélectionnez une classe d'examen</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}">{{ $level->name }} ({{ strtoupper($level->cycle) }})</option>
                @endforeach
            </select>

            <button wire:click="exportPdf"
                    @if(empty($selectedLevelId)) disabled @endif
                    class="px-4 py-2 bg-[#1e3a8a] text-white rounded-md font-medium hover:bg-blue-800 disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimer Liste d'émargement
            </button>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('message') }}
        </div>
    @endif

    @if($selectedLevelId)
    <div class="overflow-x-auto rounded-xl border border-slate-200/60 shadow-sm bg-white relative z-10 mt-6">
        <table class="min-w-full text-left whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Matricule</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nom & Prénom</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Sexe</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Statut Examen</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($enrollments as $enrollment)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $enrollment->student->matricule }}</td>
                        <td class="px-6 py-4 {{ !$enrollment->is_eligible ? 'text-rose-500 line-through' : 'font-bold text-slate-700' }}">
                            {{ mb_strtoupper($enrollment->student->last_name) }} {{ $enrollment->student->first_name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-center text-slate-600">{{ $enrollment->student->gender }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($enrollment->is_eligible)
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-lg">Autorisé</span>
                                @if($enrollment->is_manually_unblocked)
                                    <p class="text-[10px] italic text-slate-400 mt-1">Déblocage: {{ $enrollment->manual_exam_unblock_reason }}</p>
                                @endif
                            @else
                                <span class="bg-rose-100 text-rose-800 text-xs font-bold px-3 py-1 rounded-lg">Paiement requis</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(!$enrollment->is_eligible)
                                <button wire:click="openUnblockModal({{ $enrollment->id }})" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline transition-colors">Levée de blocage</button>
                            @else
                                <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4 opacity-70">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <div class="max-w-sm">
                                    <h4 class="text-lg font-bold text-slate-700 tracking-tight">Liste vide</h4>
                                    <p class="text-sm font-medium text-slate-500 mt-1">Aucun élève trouvé dans cette classe pour ces critères.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @else
        <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
            Sélectionnez une classe pour afficher la liste des élèves.
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

