<div x-data="{ showEditModal: @entangle('showEditModal'), deletingStudentId: null, showProfileModal: @entangle('showProfileModal') }">
    <div class="p-8 bg-white/70 backdrop-blur-sm rounded-xl shadow-xl border border-slate-200/60 relative overflow-hidden">
        <!-- Fond décoratif flouté -->
        <div class="absolute -top-[10%] -left-[10%] w-[30%] h-[50%] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>

    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4 relative z-10 w-full">
        <div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Filtres de l'annuaire</h3>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto">
            <!-- Filter Gender -->
            <div class="relative">
                <select wire:model.live="filterGender" class="appearance-none pl-4 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-slate-700 transition-colors cursor-pointer font-medium">
                    <option value="">Tous (Sexe)</option>
                    <option value="M">Masculins</option>
                    <option value="F">Féminins</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Filter Level -->
            <div class="relative">
                <select wire:model.live="filterLevel" class="appearance-none pl-4 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-slate-700 transition-colors cursor-pointer font-medium min-w-[160px]">
                    <option value="">Toutes les classes</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nom, Prénom, Matricule" class="pl-10 px-4 py-2.5 bg-white border border-slate-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-slate-700 transition-colors w-full md:w-64">
            </div>
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
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nom Prénom</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Sexe</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Statut / Classe</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions Rapides</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($students as $student)
                    @php
                        $enrollment = $student->enrollments->first();
                    @endphp
                    <tr wire:key="student-{{ $student->id }}" class="hover:bg-slate-50/80 transition-colors duration-150 ease-in-out group">
                        <td class="px-6 py-4 font-mono text-sm font-semibold text-slate-500">{{ $student->matricule }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ mb_strtoupper($student->last_name) }} {{ $student->first_name }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($student->gender == 'M')
                                <span class="bg-blue-100 text-blue-700 font-bold px-2.5 py-1 rounded-lg text-xs flex items-center inline-flex gap-1"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Garçon</span>
                            @else
                                <span class="bg-pink-100 text-pink-700 font-bold px-2.5 py-1 rounded-lg text-xs flex items-center inline-flex gap-1"><span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>Fille</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($enrollment && $enrollment->status == 'active')
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800">{{ $enrollment->level->name }}</span>
                                    <span class="text-xs font-medium text-emerald-600">Inscription Active</span>
                                </div>
                            @elseif($enrollment)
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800">{{ $enrollment->level->name }}</span>
                                    <span class="text-xs font-medium text-amber-600">{{ ucfirst($enrollment->status) }}</span>
                                </div>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold text-slate-500 bg-slate-100 rounded-lg border border-slate-200">Non Inscription</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                            <button wire:click="viewProfile({{ $student->id }})" class="px-3 py-1.5 text-xs font-bold text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-600 transition-all flex items-center gap-1.5 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Dossier
                            </button>
                            <button wire:click="editStudent({{ $student->id }})" class="px-3 py-1.5 text-xs font-bold text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors focus:outline-none">Modifier</button>
                            <button @click="deletingStudentId = {{ $student->id }}" class="px-3 py-1.5 text-xs font-bold text-rose-500 border border-transparent hover:border-rose-200 hover:bg-rose-50 rounded-lg transition-colors focus:outline-none">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4 opacity-70">
                                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <div class="max-w-sm">
                                    <h4 class="text-lg font-bold text-slate-700 tracking-tight">Aucune donnée</h4>
                                    <p class="text-sm font-medium text-slate-500 mt-1">Aucun élève trouvé avec ces critères.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 relative z-10">
        {{ $students->links() }}
    </div>
</div>

    <!-- Edit Modal -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;">
        <div @click.away="showEditModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
            <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-extrabold text-slate-800">Modifier l'Élève</h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 hover:bg-slate-200 p-2 rounded-xl transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-8">
                <form wire:submit.prevent="updateStudent">
                    <div class="grid grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Nom</label>
                            <input type="text" wire:model="editLastName" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors text-slate-800">
                            @error('editLastName') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Prénom</label>
                            <input type="text" wire:model="editFirstName" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors text-slate-800">
                            @error('editFirstName') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Matricule</label>
                        <input type="text" wire:model="editMatricule" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors text-slate-800 font-mono">
                        @error('editMatricule') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-5 mb-8">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Date de naissance</label>
                            <input type="date" wire:model="editBirthDate" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors text-slate-800">
                            @error('editBirthDate') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Sexe</label>
                            <select wire:model="editGender" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors text-slate-800 font-medium">
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                            @error('editGender') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-5 border-t border-slate-100">
                        <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-sm bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-bold">Annuler</button>
                        <button type="submit" class="px-5 py-2.5 text-sm bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:shadow-lg hover:shadow-blue-500/30 transition-all font-bold hover:-translate-y-0.5" wire:loading.attr="disabled">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Student Profile Slide-over / Modal -->
    @if($profileStudent)
    <div x-show="showProfileModal" x-cloak class="fixed inset-0 z-[60] flex justify-end bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;">
        <div @click.away="$wire.closeProfile()" class="bg-white w-full max-w-2xl h-full shadow-2xl flex flex-col transform transition-transform duration-300 ease-in-out" x-transition.enter.origin.right>
            
            <div class="bg-slate-900 text-white p-8 flex justify-between items-start shrink-0 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500 rounded-full blur-[50px] opacity-20 pointer-events-none"></div>
                <div class="relative z-10 flex gap-5">
                    <div class="w-20 h-20 bg-slate-800 rounded-2xl flex items-center justify-center border border-slate-700 text-3xl font-extrabold shadow-inner">
                        {{ substr($profileStudent->first_name, 0, 1) }}{{ substr($profileStudent->last_name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-extrabold tracking-tight">{{ mb_strtoupper($profileStudent->last_name) }} {{ $profileStudent->first_name }}</h2>
                        <div class="flex items-center gap-3 mt-1 text-slate-400 text-sm font-medium">
                            <span class="flex items-center gap-1.5 text-blue-300"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg> {{ $profileStudent->matricule }}</span>
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> {{ \Carbon\Carbon::parse($profileStudent->birth_date)->age }} ans</span>
                        </div>
                    </div>
                </div>
                <button @click="$wire.closeProfile()" class="text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 p-2 rounded-xl transition-colors relative z-10">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-8 bg-slate-50">
                
                @if($profileActiveEnrollment)
                    <!-- Statut Académique -->
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Année en cours</h4>
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mb-8 flex justify-between items-center group">
                        <div class="flex gap-4 items-center">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">{{ $profileActiveEnrollment->level->name }}</h3>
                                <p class="text-sm font-medium text-emerald-600 flex items-center gap-1.5 mt-0.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Inscrit / Actif</p>
                            </div>
                        </div>
                        <button wire:click="" class="opacity-0 group-hover:opacity-100 transition-opacity bg-blue-50 text-blue-700 px-4 py-2 font-bold text-sm rounded-xl hover:bg-blue-100 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Certificat Scolaire
                        </button>
                    </div>

                    <!-- Situation Financière -->
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Situation Financière</h4>
                    <div class="grid grid-cols-3 gap-4 mb-8">
                        <div class="bg-white border text-center border-slate-200 rounded-2xl p-5 shadow-sm">
                            <p class="text-xs font-bold text-slate-500 uppercase">Scolarité Totale</p>
                            <p class="text-xl font-extrabold text-slate-800 mt-2 font-mono">{{ number_format($profileTuitionFee, 0, ',', ' ') }}</p>
                        </div>
                        <div class="bg-white border text-center border-emerald-200 rounded-2xl p-5 shadow-sm">
                            <p class="text-xs font-bold text-emerald-600 uppercase">Total Payé</p>
                            <p class="text-xl font-extrabold text-emerald-700 mt-2 font-mono">{{ number_format($profileTotalPaid, 0, ',', ' ') }}</p>
                        </div>
                        <div class="bg-white border text-center {{ $profileBalance > 0 ? 'border-amber-200 bg-amber-50/30' : 'border-slate-200' }} rounded-2xl p-5 shadow-sm">
                            <p class="text-xs font-bold {{ $profileBalance > 0 ? 'text-amber-600' : 'text-slate-500' }} uppercase">Reste à payer</p>
                            <p class="text-xl font-extrabold {{ $profileBalance > 0 ? 'text-amber-700' : 'text-slate-800' }} mt-2 font-mono">{{ number_format($profileBalance, 0, ',', ' ') }}</p>
                        </div>
                    </div>
                @else
                    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm mb-8 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Aucune inscription active</h3>
                        <p class="text-sm text-slate-500 mt-2">Cet élève n'a pas d'inscription active pour l'année académique courante sélectionnée.</p>
                    </div>
                @endif
                
                <!-- Détails Personnel Supplémentaire -->
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Contacts & Historique</h4>
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm text-center py-10 opacity-70">
                    <p class="text-sm font-medium text-slate-500">Module de contacts parents & suivi disciplinaire en cours de développement.</p>
                </div>

            </div>
            
            <div class="bg-white border-t border-slate-200 p-6 flex justify-end">
                <button class="px-5 py-2.5 bg-slate-800 text-white font-bold rounded-xl shadow hover:bg-slate-700 transition" @click="$wire.closeProfile()">Fermer le dossier</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div x-show="deletingStudentId !== null" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;">
        <div @click.away="deletingStudentId = null" class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 transform transition-all">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-rose-100 rounded-full mb-4 ring-4 ring-rose-50">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-xl font-extrabold text-center text-slate-800">Supprimer cet élève</h3>
            <p class="text-sm text-center font-medium text-slate-500 mt-2 mb-6">Êtes-vous sûr de vouloir supprimer définitivement cet élève et toutes ses informations ?</p>
            <div class="flex justify-center gap-3">
                <button @click="deletingStudentId = null" class="px-5 py-2.5 font-bold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">Annuler</button>
                <button @click="$wire.deleteStudent(deletingStudentId); deletingStudentId = null" class="px-5 py-2.5 font-bold text-white bg-gradient-to-r from-rose-500 to-rose-600 rounded-xl hover:shadow-lg hover:shadow-rose-500/30 transition-all hover:-translate-y-0.5">Oui, Supprimer</button>
            </div>
        </div>
    </div>
</div>
