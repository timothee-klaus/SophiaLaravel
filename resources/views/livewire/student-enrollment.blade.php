<div class="w-full bg-white rounded-xl shadow-[0_0_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden relative">
    <!-- Barre Décorative Haute -->
    <div class="h-2 w-full bg-gradient-to-r from-blue-500 to-purple-500"></div>

    <!-- En-tête Wizard -->
    <div class="px-8 py-6 pb-2">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Assistant d'Inscription</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Étape {{ min($step, 4) }} sur 4</p>
    </div>

    <!-- ProgressBar -->
    <div class="flex items-center px-8 mb-4">
        @foreach(['Identité', 'Affectation', 'Acompte', 'Confirmation'] as $index => $label)
            <div class="flex-1 text-center relative group">
                <div class="py-2">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider {{ $step >= ($index + 1) ? 'text-blue-600' : 'text-slate-400' }} transition-colors">
                        {{ $label }}
                    </span>
                </div>
                <div class="mt-2 h-1.5 rounded-full {{ $step >= ($index + 1) ? 'bg-blue-600' : 'bg-slate-200' }} transition-all duration-500 mx-2"></div>
            </div>
        @endforeach
    </div>

    <!-- Contenu Formulaire -->
    <div class="p-8 pt-4">
        @if($step === 1)
            <div class="space-y-6 animate-fade-in">
                <h3 class="text-lg font-bold text-slate-800">Informations de l'Élève</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nom de famille <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="last_name" class="block w-full border border-slate-300 bg-slate-50 px-4 py-2.5 rounded-xl text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm transition-all" placeholder="Dupont">
                        @error('last_name') <span class="text-rose-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Prénom(s) <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="first_name" class="block w-full border border-slate-300 bg-slate-50 px-4 py-2.5 rounded-xl text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm transition-all" placeholder="Jean">
                        @error('first_name') <span class="text-rose-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date de Naissance <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="birth_date" class="block w-full border border-slate-300 bg-slate-50 px-4 py-2.5 rounded-xl text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm transition-all">
                        @error('birth_date') <span class="text-rose-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sexe <span class="text-rose-500">*</span></label>
                        <select wire:model="gender" class="block w-full border border-slate-300 bg-slate-50 px-4 py-2.5 rounded-xl text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm transition-all">
                            <option value="">Sélectionner</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                        @error('gender') <span class="text-rose-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lieu de Naissance</label>
                        <input type="text" wire:model="birth_place" class="block w-full border border-slate-300 bg-slate-50 px-4 py-2.5 rounded-xl text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm transition-all" placeholder="Ex: Lomé">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nationalité</label>
                        <input type="text" wire:model="nationality" class="block w-full border border-slate-300 bg-slate-50 px-4 py-2.5 rounded-xl text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm transition-all" placeholder="Ex: Togolaise">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Adresse / Quartier de Résidence</label>
                    <input type="text" wire:model="address" class="block w-full border border-slate-300 bg-slate-50 px-4 py-2.5 rounded-xl text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm transition-all" placeholder="Ex: Adidogomé, Lomé">
                </div>

                <!-- Guardian Info Section -->
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 shadow-sm">
                    <h4 class="text-xs font-black text-[#1e3a8a] uppercase tracking-widest mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Responsable Légal / Tuteur
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Nom Complet <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="guardian_name" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a]/20 focus:border-[#1e3a8a] transition-all bg-white" placeholder="Nom et Prénoms">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Lien de parenté <span class="text-rose-500">*</span></label>
                            <select wire:model="guardian_relation" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a]/20 focus:border-[#1e3a8a] transition-all bg-white">
                                <option value="">Choisir...</option>
                                <option value="Père">Père</option>
                                <option value="Mère">Mère</option>
                                <option value="Tuteur">Tuteur</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Profession du Tuteur</label>
                            <input type="text" wire:model="guardian_profession" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a]/20 focus:border-[#1e3a8a] transition-all bg-white" placeholder="Ex: Enseignant, Commerçant...">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Téléphone <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="guardian_phone" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a]/20 focus:border-[#1e3a8a] transition-all bg-white" placeholder="+228 90 00 00 00">
                        </div>
                    </div>
                    <div class="mt-5">
                        <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Email</label>
                        <input type="email" wire:model="guardian_email" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a]/20 focus:border-[#1e3a8a] transition-all bg-white" placeholder="tuteur@email.com">
                    </div>
                </div>

                <div class="mt-4 pt-6 border-t border-slate-100">
                    <h4 class="text-sm font-bold text-slate-700 mb-4">Documents Numérisés (Optionnel)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 hover:border-blue-300 transition-colors group">
                            <label class="block text-xs font-bold text-slate-600 mb-2">Acte de naissance</label>
                            <input type="file" wire:model="birth_certificate" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer" accept=".pdf,image/*">
                            @error('birth_certificate') <span class="text-rose-500 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 hover:border-blue-300 transition-colors group">
                            <label class="block text-xs font-bold text-slate-600 mb-2">Photos (PDF/Img)</label>
                            <input type="file" wire:model="photo" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer" accept=".pdf,image/*">
                            @error('photo') <span class="text-rose-500 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 hover:border-blue-300 transition-colors group">
                            <label class="block text-xs font-bold text-slate-600 mb-2">Attestation</label>
                            <input type="file" wire:model="attestation" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer" accept=".pdf,image/*">
                            @error('attestation') <span class="text-rose-500 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($step === 2)
            <div class="space-y-6 animate-fade-in">
                <h3 class="text-lg font-bold text-slate-800">Affectation Pédagogique</h3>
                
                <!-- NEW: Academic Year selector -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Année Académique <span class="text-rose-500 font-bold">*</span></label>
                    <select wire:model.live="academic_year_id" class="block w-full md:w-1/2 border border-slate-300 bg-slate-50 px-4 py-2.5 rounded-xl text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm font-medium transition-all">
                        <option value="">Sélectionnez l'année de rattachement</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }} {{ $year->is_current ? '(En cours)' : '' }}</option>
                        @endforeach
                    </select>
                    @error('academic_year_id') <span class="text-rose-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Cycle Scolaire <span class="text-rose-500 font-bold">*</span></label>
                        <select wire:model.live="cycle" class="block w-full border border-slate-300 bg-slate-50 px-4 py-2.5 rounded-xl text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm transition-all">
                            <option value="">Sélectionnez un cycle</option>
                            <option value="preschool">Préscolaire</option>
                            <option value="primary">Primaire</option>
                            <option value="college">Collège</option>
                            <option value="lycee">Lycée</option>
                        </select>
                        @error('cycle') <span class="text-rose-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Classe / Niveau <span class="text-rose-500 font-bold">*</span></label>
                        <select wire:model="level_id" class="block w-full border border-slate-300 bg-slate-50 px-4 py-2.5 rounded-xl text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed" {{ empty($availableLevels) ? 'disabled' : '' }}>
                            <option value="">Sélectionnez une classe</option>
                            @foreach($availableLevels as $lvl)
                                <option value="{{ $lvl->id }}">{{ $lvl->name }}</option>
                            @endforeach
                        </select>
                        @error('level_id') <span class="text-rose-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if(!empty($documents))
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-5 shadow-sm">
                    <h4 class="font-bold text-blue-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Documents Requis à fournir physiquement
                    </h4>
                    <ul class="space-y-3">
                        @foreach($documents as $doc)
                        <li class="flex items-start">
                            <input type="checkbox" class="mt-1 w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                            <span class="ml-3 text-sm font-medium text-slate-700">{{ $doc }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        @endif

        @if($step === 3)
            <div class="space-y-6 animate-fade-in">
                <h3 class="text-lg font-bold text-slate-800">Validation Financière Initiale</h3>
                <div class="bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-xl p-8 text-center shadow-inner">
                    <p class="text-slate-600 mb-2 font-medium">Acompte à payer pour le cycle : <span class="text-slate-800 font-bold ml-1 uppercase">{{ $cycle }}</span></p>
                    <p class="text-5xl font-extrabold text-blue-600 my-4 tracking-tighter">{{ number_format($registrationFee, 0, ',', ' ') }} <span class="text-2xl text-blue-500">FCFA</span></p>
                    <p class="text-sm border-t border-slate-200 mt-6 pt-4 text-slate-500 w-3/4 mx-auto">À régler auprès du secrétariat ou en ligne pour que l'inscription soit comptabilisée.</p>
                </div>
            </div>
        @endif

        @if($step === 4)
            <div class="space-y-6 animate-fade-in">
                <h3 class="text-lg font-bold text-slate-800">Résumé et Confirmation Finale</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 bg-slate-50 p-6 rounded-xl border border-slate-200">
                    <div>
                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider">Identité Élève</dt>
                        <dd class="mt-1 text-base font-bold text-slate-800 bg-white border border-slate-200 px-4 py-2.5 rounded-xl shadow-sm">{{ strtoupper($last_name) }} {{ $first_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider">Cycle Affecté</dt>
                        <dd class="mt-1 text-base font-bold text-blue-700 bg-blue-50 border border-blue-200 px-4 py-2.5 rounded-xl shadow-sm uppercase">{{ $cycle }}</dd>
                    </div>
                    <div class="col-span-full mt-2">
                        <div class="p-5 bg-emerald-50 rounded-xl border border-emerald-200 flex flex-col md:flex-row items-center justify-between shadow-sm">
                            <span class="text-emerald-800 font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Montant initial d'inscription validé
                            </span>
                            <span class="text-2xl font-extrabold text-emerald-700 mt-2 md:mt-0">{{ number_format($registrationFee, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </dl>
            </div>
        @endif

        @if($step === 5)
            <div class="text-center py-12 animate-fade-in">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-100 mb-6 shadow-iner ring-8 ring-emerald-50">
                    <svg class="h-10 w-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800 tracking-tight">Inscription Finalisée !</h3>
                <p class="text-slate-500 mt-3 font-medium">L'interfaçage avec les finances et le registre comptable a été réalisé avec succès.</p>
                <div class="mt-10 flex justify-center gap-4">
                    <button wire:click="closeForm" class="px-6 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 transition-colors">Fermer</button>
                    <button wire:click="$set('step', 1)" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-blue-500/30 transition-all hover:-translate-y-0.5">
                        Inscrire un autre élève
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Navigation du Wizard -->
    @if($step < 5)
        <div class="bg-slate-50 px-8 py-5 border-t border-slate-100 flex justify-between items-center mt-2 rounded-b-xl">
            @if($step > 1)
                <button wire:click="previousStep" type="button" class="px-5 py-2.5 bg-white border border-slate-300 shadow-sm text-sm font-bold rounded-xl text-slate-700 hover:bg-slate-50 hover:text-slate-900 focus:outline-none transition-colors">
                    &larr; Précédent
                </button>
            @else
                <div></div> <!-- Placeholder pour flex-between -->
            @endif
            
            @if($step < 4)
                <button wire:click="nextStep" type="button" class="px-5 py-2.5 shadow-sm text-sm font-bold rounded-xl text-white bg-[#1e3a8a] hover:bg-[#152c6e] focus:outline-none transition-all hover:scale-105">
                    Continuer &rarr;
                </button>
            @else
                <button wire:click="submit" type="button" class="px-6 py-2.5 shadow-md shadow-emerald-500/20 text-sm font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none transition-colors ml-auto flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Confirmer définitivement
                </button>
            @endif
        </div>
    @endif
</div>
