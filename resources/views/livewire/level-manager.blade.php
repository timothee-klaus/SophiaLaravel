<div class="p-8 bg-white/70 backdrop-blur-sm rounded-xl shadow-xl border border-slate-200/60 relative overflow-hidden mt-6">
    <div class="absolute -bottom-[10%] -right-[10%] w-[30%] h-[50%] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 mb-8">
        <h3 class="text-lg font-bold text-slate-800 tracking-tight">Classes & Niveaux d'Étude</h3>
        <p class="text-sm font-medium text-slate-500 mt-1">Configurez les cycles, les frais, et les classes d'examen.</p>
    </div>

    <div class="mb-10 relative z-10">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-[#1e3a8a]">Configuration des Frais par Cycle</h4>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1 tracking-wider">Mise à jour centralisée des frais d'inscription et frais divers</p>
                </div>
                <div class="flex gap-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#1e3a8a]/20"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-[#1e3a8a]/40"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-[#1e3a8a]/60"></div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500 border-collapse">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-50 border-b border-slate-100 font-semibold tracking-wider">
                        <tr>
                            <th scope="col" class="px-6 py-4">Cycle</th>
                            <th scope="col" class="px-6 py-4 text-right">Inscription</th>
                            <th scope="col" class="px-6 py-4 text-right">Divers (Régulier)</th>
                            <th scope="col" class="px-6 py-4 text-right">Divers (Examen)</th>
                            <th scope="col" class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach(['preschool' => 'Maternelle', 'primary' => 'Primaire', 'college' => 'Collège', 'lycee' => 'Lycée'] as $cycleKey => $cycleLabel)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2.5 h-2.5 rounded-full @if($cycleKey == 'preschool') bg-emerald-500 @elseif($cycleKey == 'primary') bg-amber-500 @elseif($cycleKey == 'college') bg-indigo-500 @else bg-rose-500 @endif"></div>
                                        <span class="font-semibold text-slate-700 uppercase tracking-tight">{{ $cycleLabel }}</span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 text-right">
                                    @if($editingCycleKey === $cycleKey)
                                        <input type="number" wire:model.defer="cycleFees.{{ $cycleKey }}.registration_fee" class="w-32 bg-white border-slate-200 rounded-lg text-xs font-mono font-bold text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] py-1.5 px-3 ml-auto block">
                                        @error('cycleFees.'.$cycleKey.'.registration_fee') <span class="text-[9px] text-rose-500 block mt-1 font-bold">{{ $message }}</span> @enderror
                                    @else
                                        <span class="font-bold text-slate-600 tracking-tighter">{{ number_format($cycleFees[$cycleKey]['registration_fee'], 0, ',', ' ') }} <small class="text-[10px] text-slate-400">FCFA</small></span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    @if($editingCycleKey === $cycleKey)
                                        <input type="number" wire:model.defer="cycleFees.{{ $cycleKey }}.miscellaneous_fee" class="w-32 bg-white border-slate-200 rounded-lg text-xs font-mono font-bold text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] py-1.5 px-3 ml-auto block">
                                        @error('cycleFees.'.$cycleKey.'.miscellaneous_fee') <span class="text-[9px] text-rose-500 block mt-1 font-bold">{{ $message }}</span> @enderror
                                    @else
                                        <span class="font-bold text-slate-600 tracking-tighter">{{ number_format($cycleFees[$cycleKey]['miscellaneous_fee'], 0, ',', ' ') }} <small class="text-[10px] text-slate-400">FCFA</small></span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    @if($editingCycleKey === $cycleKey)
                                        <input type="number" wire:model.defer="cycleFees.{{ $cycleKey }}.exam_miscellaneous_fee" class="w-32 bg-white border-slate-200 rounded-lg text-xs font-mono font-bold text-[#1e3a8a] focus:ring-[#1e3a8a] focus:border-[#1e3a8a] py-1.5 px-3 ml-auto block">
                                        @error('cycleFees.'.$cycleKey.'.exam_miscellaneous_fee') <span class="text-[9px] text-rose-500 block mt-1 font-bold">{{ $message }}</span> @enderror
                                    @else
                                        <span class="font-bold text-[#1e3a8a] tracking-tighter">{{ number_format($cycleFees[$cycleKey]['exam_miscellaneous_fee'], 0, ',', ' ') }} <small class="text-[10px] text-slate-400">FCFA</small></span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($editingCycleKey === $cycleKey)
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="updateCycleFees('{{ $cycleKey }}')" class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition-all" title="Enregistrer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                            <button wire:click="cancelCycleEdit" class="p-1.5 bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition-all" title="Annuler">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center gap-3">
                                            <button wire:click="startEditingCycle('{{ $cycleKey }}')" class="p-1.5 bg-slate-100 text-slate-400 hover:text-[#1e3a8a] hover:bg-slate-200 rounded-lg transition-all" title="Modifier les frais">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            @if(session()->has('cycle_message_' . $cycleKey))
                                                <div class="flex items-center gap-1">
                                                    <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-tighter">Ok</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($successMessage)
        <div class="mb-6 p-4 text-sm text-emerald-800 rounded-xl bg-emerald-50/80 border border-emerald-200 backdrop-blur-sm relative z-10 flex items-center justify-between animate-in fade-in slide-in-from-top-4 duration-300" role="alert">
            <div class="flex items-center gap-3 font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ $successMessage }}
            </div>
            <button wire:click="closeMessage" class="text-emerald-500 hover:text-emerald-700 transition-colors p-1 hover:bg-emerald-100 rounded-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    @if ($errorMessage)
        <div class="mb-6 p-4 text-sm text-rose-800 rounded-xl bg-rose-50/80 border border-rose-200 backdrop-blur-sm relative z-10 flex items-center justify-between" role="alert">
            <div class="flex items-center gap-3 font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $errorMessage }}
            </div>
            <button wire:click="closeMessage" class="text-rose-500 hover:text-rose-700 transition-colors p-1 hover:bg-rose-100 rounded-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <div class="bg-gradient-to-br from-slate-50 to-white border border-slate-200 rounded-2xl p-6 mb-8 relative z-10 shadow-sm transition-all hover:shadow-md">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-[#0f172a]"></span>
            Création d'une Nouvelle Classe
        </h4>
        <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-slate-500 mb-1.5 uppercase tracking-wider">Nom de la Classe</label>
                <input type="text" wire:model="name" placeholder="Ex: 6ème A" class="block w-full border border-slate-200 bg-slate-50/30 px-4 py-2.5 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-all text-slate-800 font-bold placeholder:font-normal placeholder:text-slate-300">
            </div>
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-slate-500 mb-1.5 uppercase tracking-wider">Cycle</label>
                <select wire:model="cycle" class="block w-full border border-slate-200 bg-slate-50/30 px-4 py-2.5 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-all text-slate-800 font-bold">
                    <option value="preschool">Maternelle</option>
                    <option value="primary">Primaire</option>
                    <option value="college">Collège</option>
                    <option value="lycee">Lycée</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-slate-500 mb-1.5 uppercase tracking-wider">Scolarité (FCFA)</label>
                <input type="number" wire:model="total_amount" class="block w-full text-right border border-slate-200 bg-slate-50/30 px-4 py-2.5 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-all text-slate-800 font-mono font-bold">
            </div>
            <div class="md:col-span-2 flex flex-col items-center justify-center pb-2">
                <label class="block text-[10px] font-black text-slate-500 mb-2 uppercase tracking-wider">Classe d'Examen ?</label>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="is_exam_class" class="sr-only peer">
                    <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            <div class="md:col-span-3">
                <button type="submit" class="w-full bg-[#1e3a8a] text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-800 transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-900/10 active:scale-95 group">
                    <span>AJOUTER LA CLASSE</span>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200/60 shadow-sm bg-white relative z-10">
        <table class="w-full text-sm text-left text-slate-500">
            <thead class="text-xs text-slate-400 uppercase bg-slate-50 border-b border-slate-100 font-semibold tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4">Nom de la Classe</th>
                    <th scope="col" class="px-6 py-4">Cycle</th>
                    <th scope="col" class="px-6 py-4 text-center">Effectif</th>
                    <th scope="col" class="px-6 py-4 text-center">Examen</th>
                    <th scope="col" class="px-6 py-4 text-right">Scolarité</th>
                    <th scope="col" class="px-6 py-4 text-right">Inscription</th>
                    <th scope="col" class="px-6 py-4 text-right">Frais Divers</th>
                    <th scope="col" class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php
                    $cycleMap = [
                        'preschool' => 'Maternelle',
                        'primary' => 'Primaire',
                        'college' => 'Collège',
                        'lycee' => 'Lycée'
                    ];
                @endphp
                @forelse($this->levels as $level)
                    @php
                        $cycleStyles = [
                            'preschool' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            'primary' => 'bg-amber-50 text-amber-700 border-amber-100',
                            'college' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                            'lycee' => 'bg-rose-50 text-rose-700 border-rose-100',
                        ];
                        $fee = $level->tuitionFees->first(); 
                    @endphp
                    @if($editingLevelId === $level->id)
                        <tr class="bg-blue-50/50 border-l-2 border-blue-600">
                            <td class="px-6 py-4">
                                <input type="text" wire:model="editName" class="w-full border border-blue-300 rounded-lg px-3 py-1.5 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold text-slate-800 shadow-sm">
                            </td>
                            <td class="px-6 py-4">
                                <select wire:model="editCycle" class="w-full border border-blue-300 rounded-lg px-3 py-1.5 focus:border-blue-500 focus:ring-blue-500 text-xs font-medium text-slate-800 bg-white shadow-sm">
                                    <option value="preschool">Maternelle</option>
                                    <option value="primary">Primaire</option>
                                    <option value="college">Collège</option>
                                    <option value="lycee">Lycée</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-[10px] font-bold text-slate-400">{{ $level->enrollments_count }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" wire:model="editIsExamClass" class="w-5 h-5 text-blue-600 rounded-lg border-blue-300 focus:ring-blue-500 cursor-pointer">
                            </td>
                            <td class="px-6 py-4 text-right">
                                <input type="number" wire:model="editTotalAmount" class="w-full border border-blue-300 rounded-lg px-3 py-1.5 text-right focus:border-blue-500 focus:ring-blue-500 text-xs font-mono font-bold text-slate-800 shadow-sm">
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-[10px] font-bold text-slate-400 italic">Sync Cycle</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-[10px] font-bold text-slate-400 italic">Sync Cycle</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="update" class="p-1.5 bg-[#1e3a8a] text-white rounded-lg hover:bg-blue-800 transition-all shadow-md shadow-blue-500/10" title="Enregistrer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                    <button wire:click="cancelEdit" class="p-1.5 bg-slate-200 text-slate-600 rounded-lg hover:bg-slate-300 transition-all" title="Annuler">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150 ease-in-out group">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-700 group-hover:text-[#1e3a8a] transition-colors">{{ $level->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="{{ $cycleStyles[$level->cycle] ?? 'bg-slate-50 text-slate-700' }} font-bold px-2.5 py-1 rounded-lg text-xs flex items-center inline-flex gap-1.5 border">
                                    {{ $cycleMap[$level->cycle] ?? $level->cycle }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 font-bold text-slate-600">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    {{ $level->enrollments_count }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($level->is_exam_class) 
                                    <div class="inline-flex items-center justify-center w-7 h-7 bg-rose-50 text-rose-500 rounded-full border border-rose-100 shadow-sm" title="Classe à Examen Officiel">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    </div>
                                @else 
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-200 mx-auto"></div>
                                @endif
                            </td>
                            @php $fee = $level->tuitionFees->first(); @endphp
                            <td class="px-6 py-4 text-right">
                                <span class="font-extrabold text-slate-700">{{ number_format($fee->total_amount ?? 0, 0, ',', ' ') }} <span class="text-[10px] text-slate-400 font-medium">F</span></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-slate-600">{{ number_format($fee->registration_fee ?? 0, 0, ',', ' ') }} <span class="text-[10px] text-slate-400 font-medium">F</span></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-slate-600">{{ number_format($fee->miscellaneous_fee ?? 0, 0, ',', ' ') }} <span class="text-[10px] text-slate-400 font-medium">F</span></span>
                            </td>
                             <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="manageInstallments({{ $level->id }})" class="p-2 text-[#1e3a8a] hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-blue-100" title="Gérer les Tranches">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </button>
                                    <button wire:click="edit({{ $level->id }})" class="p-2 text-slate-400 hover:text-[#1e3a8a] hover:bg-slate-100 rounded-xl transition-colors focus:outline-none" title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @if($fee && $managingTuitionFeeId === $fee->id)
                            <tr class="bg-blue-50/30">
                                <td colspan="8" class="px-8 py-8 border-y-2 border-blue-100/50">
                                    <div class="relative">
                                        <div class="flex items-center justify-between mb-8">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-blue-600 rounded-lg shadow-lg shadow-blue-500/20">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                </div>
                                                <div>
                                                    <h5 class="text-sm font-extrabold text-[#111827] uppercase tracking-wider">Échéancier de Paiement</h5>
                                                    <p class="text-[10px] font-bold text-blue-600 mt-0.5">{{ $installmentLevelName }}</p>
                                                </div>
                                            </div>
                                            <button wire:click="manageInstallments({{ $level->id }})" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-200 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>

                                        @if (session()->has('modal_error'))
                                            <div class="mb-6 p-4 text-xs font-bold text-rose-800 rounded-xl bg-rose-50 border border-rose-100 flex items-center gap-3">
                                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                {{ session('modal_error') }}
                                            </div>
                                        @endif

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                            @forelse($tempInstallments as $index => $inst)
                                                <div class="p-6 rounded-2xl bg-white border border-slate-200/60 shadow-sm relative group hover:border-blue-300 transition-all overflow-hidden">
                                                    <div class="absolute top-0 right-0 w-16 h-16 -mr-8 -mt-8 bg-blue-50/50 rounded-full"></div>
                                                    <div class="absolute top-0 left-0 bg-[#1e3a8a] text-white px-3 py-1.5 rounded-br-xl flex items-center justify-center text-[10px] font-black shadow-sm z-20">
                                                        {{ $inst['installment_number'] }}
                                                    </div>
                                                    
                                                    <div class="space-y-5 pt-2 relative z-10">
                                                        <div>
                                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Montant Exigible (F)</label>
                                                            <input type="number" wire:model.live="tempInstallments.{{ $index }}.amount" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-mono font-black text-slate-700 focus:ring-blue-500 focus:border-blue-500 py-2.5">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Date Limite</label>
                                                            <input type="date" wire:model.live="tempInstallments.{{ $index }}.due_date" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 py-2.5">
                                                        </div>
                                                        <button wire:click="removeInstallment({{ $index }})" class="absolute top-0 right-0 p-2 text-slate-300 hover:text-rose-500 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="md:col-span-3 py-10 text-center text-slate-400 italic text-sm font-bold bg-white rounded-2xl border-2 border-dashed border-slate-100">
                                                    Aucune tranche. Ajoutez des points de paiement pour diviser la scolarité.
                                                </div>
                                            @endforelse
                                        </div>

                                        <div class="flex items-center justify-between border-t border-slate-100 pt-8 mt-10">
                                            <button wire:click="addInstallment" class="inline-flex items-center gap-3 group">
                                                <span class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-[#0f172a] text-xl font-black transition-all group-hover:bg-[#0f172a] group-hover:text-white shadow-sm">+</span>
                                                <span class="text-xs font-black text-slate-600 uppercase tracking-widest group-hover:text-blue-600 transition-colors">Nouveau Point de Paiement</span>
                                            </button>

                                            <div class="flex gap-4">
                                                <button wire:click="manageInstallments({{ $level->id }})" class="px-6 py-2.5 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-800 transition-all">Annuler</button>
                                                <button wire:click="saveInstallments" class="px-10 py-3 bg-[#1e3a8a] rounded-xl font-black text-xs uppercase tracking-widest text-white shadow-xl shadow-blue-900/20 hover:bg-blue-800 active:scale-95 transition-all flex items-center gap-3">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    Valider l'Échéancier
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endif
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3 opacity-60">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-slate-700">Aucune classe</h4>
                                    <p class="text-xs font-medium text-slate-500 mt-1">Aucune classe n'a été configurée pour le moment.</p>
                                </div>
                                <button @click="showModal = true" class="mt-2 text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">Ajouter une classe</button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
