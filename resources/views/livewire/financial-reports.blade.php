<div class="p-8 bg-white/70 backdrop-blur-sm rounded-xl shadow-xl border border-slate-200/60 overflow-hidden mb-8 relative">
    <div class="absolute -bottom-[20%] -right-[10%] w-[30%] h-[50%] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>
    <div class="px-2 py-2 relative z-10 w-full mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Paramètres des exports</h3>
            <p class="text-sm font-medium text-slate-500 mt-1">Configurez les périodes et éditez vos archives financières.</p>
        </div>
        <div class="flex flex-wrap gap-2 md:gap-3">
            @if($activeTab === 'payments')
                <a href="{{ route('reports.payments.pdf', ['action' => 'stream', 'start' => $startDate, 'end' => $endDate, 'level' => $paymentLevelId, 'year' => $paymentAcademicYearId]) }}" target="_blank" class="flex items-center gap-2 bg-[#0f172a] text-white px-4 md:px-5 py-2 rounded-xl font-bold hover:bg-black hover:-translate-y-0.5 transition-all shadow-sm text-sm md:text-base">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Aperçu PDF
                </a>
                <a href="{{ route('reports.payments.pdf', ['action' => 'download', 'start' => $startDate, 'end' => $endDate, 'level' => $paymentLevelId, 'year' => $paymentAcademicYearId]) }}" class="flex items-center gap-2 bg-red-600 text-white px-4 md:px-5 py-2 rounded-xl font-bold hover:bg-red-700 hover:-translate-y-0.5 transition-all shadow-sm text-sm md:text-base">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Télécharger PDF
                </a>
                <button type="button" wire:click="exportPayments('xlsx')" class="flex items-center gap-2 bg-emerald-600 text-white px-4 md:px-5 py-2 rounded-xl font-bold hover:bg-emerald-700 hover:-translate-y-0.5 transition-all shadow-sm text-sm md:text-base">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Excel
                </button>
            @else
                <a href="{{ route('reports.balances.pdf', ['action' => 'stream', 'level' => $balanceLevelId, 'year' => $balanceAcademicYearId]) }}" target="_blank" class="flex items-center gap-2 bg-[#0f172a] text-white px-4 md:px-5 py-2 rounded-xl font-bold hover:bg-black hover:-translate-y-0.5 transition-all shadow-sm text-sm md:text-base">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Aperçu PDF
                </a>
                <a href="{{ route('reports.balances.pdf', ['action' => 'download', 'level' => $balanceLevelId, 'year' => $balanceAcademicYearId]) }}" class="flex items-center gap-2 bg-red-600 text-white px-4 md:px-5 py-2 rounded-xl font-bold hover:bg-red-700 hover:-translate-y-0.5 transition-all shadow-sm text-sm md:text-base">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Télécharger PDF
                </a>
                <button type="button" wire:click="exportBalances('xlsx')" class="flex items-center gap-2 bg-emerald-600 text-white px-4 md:px-5 py-2 rounded-xl font-bold hover:bg-emerald-700 hover:-translate-y-0.5 transition-all shadow-sm text-sm md:text-base">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Excel
                </button>
            @endif
        </div>
    </div>
    <!-- Tabs -->
    <div class="flex border-b border-gray-200">
        <button wire:click="$set('activeTab', 'payments')" class="flex-1 py-4 text-sm font-medium text-center transition-colors {{ $activeTab === 'payments' ? 'text-[#1e3a8a] border-b-2 border-[#1e3a8a] bg-blue-50/30' : 'text-gray-500 hover:bg-gray-50' }}">
            Historique des Paiements
        </button>
        <button wire:click="$set('activeTab', 'balances')" class="flex-1 py-4 text-sm font-medium text-center transition-colors {{ $activeTab === 'balances' ? 'text-[#1e3a8a] border-b-2 border-[#1e3a8a] bg-blue-50/30' : 'text-gray-500 hover:bg-gray-50' }}">
            Soldes Élèves (Impayés)
        </button>
    </div>
    <div class="p-6">
        @if($activeTab === 'payments')
            <form wire:submit.prevent="exportPayments" class="space-y-6">
                <!-- Filters for Payments -->
                <!-- Filters for Payments -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Année Académique</label>
                        <select wire:model.live="paymentAcademicYearId" class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Classe (Optionnel)</label>
                        <select wire:model.live="paymentLevelId" class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Toutes les classes</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }} ({{ ucfirst($level->cycle) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de Début</label>
                        <input type="date" wire:model.live="startDate" class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de Fin</label>
                        <input type="date" wire:model.live="endDate" class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                </div>
            </form>


        @else
            <!-- Filters for Balances -->
            <form wire:submit.prevent="exportBalances" class="space-y-6">
                <div class="pb-4 border-b border-slate-100">
                    <h4 class="text-sm font-bold text-slate-600 uppercase tracking-wider">Filtres de génération</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Année Académique</label>
                        <select wire:model.live="balanceAcademicYearId" class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Classe (Optionnel)</label>
                        <select wire:model.live="balanceLevelId" class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Toutes les classes</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }} ({{ ucfirst($level->cycle) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>


        @endif
    </div>
</div>
