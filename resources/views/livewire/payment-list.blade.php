<div x-data="{ showModal: @entangle('showModal') }">
    <div class="p-8 bg-white/70 backdrop-blur-sm rounded-xl shadow-xl border border-slate-200/60 relative overflow-hidden">
        <div class="absolute -top-[10%] -right-[10%] w-[30%] h-[50%] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>
    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4 relative z-10 w-full">
        <h3 class="text-lg font-bold text-slate-800">Caisse et Historique</h3>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <!-- Filter Type -->
            <div class="relative">
                <select wire:model.live="filterType" class="appearance-none pl-4 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-slate-700 transition-colors cursor-pointer font-medium">
                    <option value="">Tous les types</option>
                    <option value="registration">Inscription</option>
                    <option value="tuition">Scolarité (Tranches)</option>
                    <option value="miscellaneous">Divers</option>
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
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Élève, Matricule, Reçu..." class="pl-10 px-4 py-2.5 bg-white border border-slate-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-slate-700 transition-colors w-full md:w-64">
            </div>

            <button @click="showModal = true" class="px-5 py-2.5 bg-[#1e3a8a] text-white rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-800 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Encaisser
            </button>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200/60 shadow-sm bg-white relative z-10 text-nowrap">
        <table class="min-w-full text-left whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Reçu N°</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Élève</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Motif / Détails</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Montant</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Date & Heure</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions Reçu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-150 ease-in-out group">
                        <td class="px-6 py-4 font-mono text-[10px] font-semibold text-slate-500 leading-tight">
                            @php
                                $parts = explode('-', $payment->transaction_id);
                                $firstPart = $parts[0] . '-' . ($parts[1] ?? '');
                                $secondPart = isset($parts[2]) ? '-' . $parts[2] : '';
                            @endphp
                            <div>{{ $firstPart }}</div>
                            <div class="text-blue-500">{{ $secondPart }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">
                                {{ mb_strtoupper($payment->student->last_name) }} {{ $payment->student->first_name }}
                            </div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $payment->student->matricule }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($payment->type === 'registration')
                                <span class="bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-lg text-[10px] uppercase">Inscription</span>
                            @elseif($payment->type === 'miscellaneous')
                                <span class="bg-slate-100 text-slate-700 font-bold px-2.5 py-1 rounded-lg text-[10px] uppercase">Frais Divers</span>
                            @else
                                <span class="bg-blue-100 text-blue-700 font-bold px-2.5 py-1 rounded-lg text-[10px] uppercase">Tranche {{ $payment->installment_number }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-lg font-black text-[#1e3a8a]">{{ number_format($payment->amount, 0, ',', ' ') }}</span>
                            <span class="text-[10px] font-bold text-slate-400 ml-1">FCFA</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm font-medium text-slate-600">{{ $payment->created_at->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $payment->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('payments.receipt-preview', $payment) }}" target="_blank" 
                                   class="px-3 py-1.5 text-xs font-bold text-white border border-transparent rounded-lg bg-[#0f172a] hover:bg-black transition-all flex items-center gap-1.5 shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Aperçu
                                </a>
                                <a href="{{ route('payments.receipt-download', $payment) }}" 
                                   class="px-3 py-1.5 text-xs font-bold text-slate-600 border border-slate-200 rounded-lg bg-white hover:bg-slate-100 transition-all flex items-center gap-1.5 shadow-sm">
                                   <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                   Télécharger
                                </a>
                            </div>
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
                                    <h4 class="text-lg font-bold text-slate-700 tracking-tight">Aucun paiement</h4>
                                    <p class="text-sm font-medium text-slate-500 mt-1">L'historique des transactions apparaîtra ici.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>

    <!-- Modal Popup -->
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50" style="display: none;">
        <div @click.away="showModal = false" class="bg-gray-100 rounded-xl shadow-2xl w-full max-w-5xl max-h-[95vh] overflow-y-auto relative">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center z-10">
                <h3 class="text-xl font-bold text-gray-800">Enregistrer un Paiement</h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                @livewire('payment-manager')
            </div>
        </div>
    </div>
</div>

