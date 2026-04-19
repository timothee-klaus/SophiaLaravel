<div class="p-8 bg-white/70 backdrop-blur-sm rounded-xl shadow-xl border border-slate-200/60 relative overflow-hidden mt-6">
    <div class="absolute -top-[20%] -right-[10%] w-[30%] h-[50%] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 mb-8">
        <h3 class="text-lg font-bold text-slate-800 tracking-tight">Réinscription Rapide</h3>
        <p class="text-sm font-medium text-slate-500 mt-1">
            Année Active <span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-lg text-xs ml-1">{{ $activeYear->name ?? 'Aucune' }}</span>
        </p>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 text-sm text-emerald-800 rounded-xl bg-emerald-50/80 border border-emerald-200 backdrop-blur-sm relative z-10" role="alert">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 text-sm text-rose-800 rounded-xl bg-rose-50/80 border border-rose-200 backdrop-blur-sm relative z-10" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 relative z-10">
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Rechercher un élève (Ancienne Inscription)</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nom, Prénom ou Matricule" class="pl-10 w-full px-4 py-3 bg-white border border-slate-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
        </div>
    </div>

    @if(count($students) > 0)
        <div class="mb-6 border border-slate-200 rounded-xl shadow-sm bg-white overflow-hidden relative z-10">
            <ul class="max-h-48 overflow-y-auto divide-y divide-slate-100">
                @foreach($students as $student)
                    <li class="px-5 py-3 cursor-pointer transition-colors duration-150 {{ $selectedStudentId == $student->id ? 'bg-blue-50 border-l-4 border-l-blue-500' : 'hover:bg-slate-50 border-l-4 border-l-transparent' }}" wire:click="selectStudent({{ $student->id }})">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-800">{{ mb_strtoupper($student->last_name) }} {{ $student->first_name }}</span>
                            <span class="text-xs font-mono font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded-md">{{ $student->matricule }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @elseif($search)
        <div class="mb-6 p-6 border border-dashed border-slate-300 rounded-xl bg-slate-50/50 text-center relative z-10">
            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-sm font-medium text-slate-500">Aucun étudiant trouvé pour "{{ $search }}"</p>
        </div>
    @endif

    @if($selectedStudentId)
        <div class="p-6 mt-6 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl border border-slate-200 relative z-10 animate-fade-in shadow-inner">
            <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#0f172a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Affecter à une classe
            </h4>
            <div class="flex flex-col sm:flex-row gap-4 mt-2">
                <div class="relative flex-1">
                    <select wire:model="selectedLevelId" class="appearance-none w-full px-4 py-3 bg-white border border-slate-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 font-medium text-slate-700 transition-colors">
                        <option value="">-- Sélectionner une classe --</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <button wire:click="reenroll" class="px-6 py-3 whitespace-nowrap bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl font-bold shadow-md shadow-emerald-500/20 hover:shadow-lg hover:shadow-emerald-500/40 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Valider la Réinscription
                </button>
            </div>
        </div>
    @endif
</div>
