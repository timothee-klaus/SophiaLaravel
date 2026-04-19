<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#0f172a] flex items-center justify-center text-white shadow-lg shadow-black/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight">Explorateur d'Audit</h2>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Traçabilité complète des actions système & CRUD</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button wire:click="$refresh" class="p-2.5 text-slate-400 hover:text-[#0f172a] hover:bg-white rounded-xl transition-all border border-transparent hover:border-slate-200 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </button>
            </div>
        </div>

        <div class="p-8 bg-white">
            <!-- Filtres -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Recherche</label>
                    <input type="text" wire:model.live="search" placeholder="ID, Modèle, Événement..." class="w-full pl-4 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#0f172a] focus:ring-4 focus:ring-[#0f172a]/5 transition-all text-xs font-bold text-slate-700">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Événement</label>
                    <select wire:model.live="event" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#0f172a] focus:ring-4 focus:ring-[#0f172a]/5 transition-all text-xs font-bold text-slate-700 appearance-none">
                        <option value="">Tous</option>
                        @foreach($events as $e)
                            <option value="{{ $e }}">{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Utilisateur</label>
                    <select wire:model.live="userId" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#0f172a] focus:ring-4 focus:ring-[#0f172a]/5 transition-all text-xs font-bold text-slate-700 appearance-none">
                        <option value="">Tous</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Depuis</label>
                    <input type="date" wire:model.live="dateFrom" class="w-full pl-4 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#0f172a] focus:ring-4 focus:ring-[#0f172a]/5 transition-all text-xs font-bold text-slate-700">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jusqu'à</label>
                    <input type="date" wire:model.live="dateTo" class="w-full pl-4 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#0f172a] focus:ring-4 focus:ring-[#0f172a]/5 transition-all text-xs font-bold text-slate-700">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Date & Heure</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Utilisateur</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Événement</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Modèle / ID</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">IP / Navigateur</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Détails</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-50">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-[11px] font-black text-slate-700 tracking-tight">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-black">
                                            {{ substr($log->user?->name ?? 'SYS', 0, 1) }}
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-600">{{ $log->user?->name ?? 'Système' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $color = match($log->event) {
                                            'create' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'update' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'delete' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            'login' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'logout' => 'bg-slate-100 text-slate-600 border-slate-200',
                                            default => 'bg-slate-50 text-slate-600'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-tighter border {{ $color }}">
                                        {{ $log->event }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-bold text-slate-700 tracking-tight">{{ class_basename($log->auditable_type) ?: '-' }}</span>
                                        <span class="text-[9px] font-black text-slate-400">ID: {{ $log->auditable_id ?: 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-500">{{ $log->ip_address }}</span>
                                        <span class="text-[9px] text-slate-300 truncate max-w-[150px]" title="{{ $log->user_agent }}">{{ $log->user_agent }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button x-data x-on:click="$dispatch('open-modal', { id: 'audit-detail-{{ $log->id }}' })" class="text-blue-600 hover:text-blue-800 text-[10px] font-black uppercase tracking-widest transition-all">Voir JSON</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-slate-400 italic font-medium">Aucun log trouvé pour ces critères.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
