<div class="max-w-5xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden min-h-[500px]">
    <div class="bg-[#1e3a8a] text-white px-6 py-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Gestion des Paiements</h2>
    </div>
    <!-- Search Section -->
    <div class="p-6 border-b border-gray-100 bg-gray-50">
        <div class="relative max-w-lg">
            <input wire:model.live.debounce.300ms="search" type="search" class="block w-full py-2 pl-4 pr-3 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-800 focus:border-blue-800 focus:outline-none" placeholder="Rechercher l'élève..." autocomplete="off">
            @if(strlen($search) > 1)
                <div class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg top-full">
                    @if(count($students) > 0)
                        <ul class="py-1 text-sm text-gray-700">
                            @foreach($students as $s)
                                <li>
                                    <button wire:click="selectStudent({{ $s->id }})" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                        <span class="font-bold">{{ $s->first_name }} {{ $s->last_name }}</span> - {{ $s->matricule }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="px-4 py-2 text-sm text-gray-500">Aucun élève trouvé.</div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    @if($studentId && $enrollment)
        <div class="p-6">
            <!-- Informations de l'élève -->
            <div class="mb-6 flex items-center justify-between bg-white border border-blue-100 rounded-2xl p-4 shadow-sm shadow-blue-50">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-xl bg-[#1e3a8a]/10 flex items-center justify-center text-[#1e3a8a] font-bold text-xl uppercase">
                        {{ substr($enrollment->student->first_name, 0, 1) }}{{ substr($enrollment->student->last_name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $enrollment->student->first_name }} <span class="uppercase">{{ $enrollment->student->last_name }}</span></h3>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-sm text-gray-500 font-medium">Matricule : <span class="text-gray-700">{{ $enrollment->student->matricule }}</span></span>
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <span class="text-sm text-gray-500 font-medium">Statut : <span class="text-emerald-600 font-bold">Inscrit</span></span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center gap-2 py-2 px-4 rounded-xl text-sm font-bold bg-[#1e3a8a]/5 text-[#1e3a8a] border border-[#1e3a8a]/10">
                        <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Classe : {{ $enrollment->level->name ?? 'N/A' }}
                    </span>
                </div>
            </div>
            <!-- Alerts & Context -->
            <div class="mb-6 space-y-3">


                @if($examBlocked)
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-rose-500 text-white rounded-lg animate-bounce">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-black text-rose-800 uppercase tracking-wider">Scolarité Insuffisante : BLOQUÉ EXAMEN</p>
                                <p class="text-[10px] font-bold text-rose-600">Le seuil des 2 tranches après décembre n'est pas atteint.</p>
                            </div>
                        </div>
                        @if($enrollment->is_manually_unblocked)
                            <span class="text-[9px] font-black bg-white/50 text-rose-500 px-2 py-1 rounded border border-rose-100 uppercase">Dérogation Active</span>
                        @endif
                    </div>
                @endif
            </div>

            @if ($errorMessage)
                <div class="p-4 mb-4 text-sm font-bold text-rose-800 bg-rose-50/80 border border-rose-200 backdrop-blur-sm rounded-xl flex items-center justify-between animate-in fade-in slide-in-from-top-4 duration-300" role="alert">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $errorMessage }}
                    </div>
                    <button wire:click="closeMessage" class="text-rose-500 hover:text-rose-700 transition-colors p-1 hover:bg-rose-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            @if ($paymentSuccess)
                <div class="p-4 mb-4 text-sm font-bold text-emerald-800 bg-emerald-50/80 border border-emerald-200 backdrop-blur-sm rounded-xl flex items-center justify-between animate-in fade-in slide-in-from-top-4 duration-300" role="alert">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Paiement enregistré avec succès. 
                        @if($lastReceiptUrl)
                            <a href="{{ route('payments.receipt-download', $lastReceiptUrl) }}" class="underline font-black hover:text-emerald-900 ml-1">Télécharger le reçu</a>
                        @endif
                        </span>
                    </div>
                    <button wire:click="closeMessage" class="text-emerald-500 hover:text-emerald-700 transition-colors p-1 hover:bg-emerald-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Echéancier -->
                <div class="flex flex-col gap-6">
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 font-semibold text-gray-700">Échéancier Scolarité</div>
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2">Tranche</th>
                                    <th class="px-4 py-2">Montant</th>
                                    <th class="px-4 py-2">Date Limite</th>
                                    <th class="px-4 py-2">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($installments as $ins)
                                    @php
                                        $paid = $payments->where('type', 'tuition')->where('installment_number', $ins->installment_number)->sum('amount');
                                        $isPaid = $paid >= $ins->amount;
                                        $due = Carbon\Carbon::parse($ins->due_date);
                                        $isLate = !$isPaid && now()->greaterThan($due);
                                        $dateStr = "";
                                        if($ins->installment_number == 1) $dateStr = "Fin Octobre";
                                        elseif($ins->installment_number == 2) $dateStr = "Fin Décembre";
                                        elseif($ins->installment_number == 3) $dateStr = "Fin Février";
                                        elseif($ins->installment_number == 4) $dateStr = "15 Avril";
                                    @endphp
                                    <tr class="border-b border-gray-100">
                                        <td class="px-4 py-3 font-medium">T{{ $ins->installment_number }}</td>
                                        <td class="px-4 py-3">{{ number_format($ins->amount, 0, ',', ' ') }} F</td>
                                        <td class="px-4 py-3">
                                            <span class="{{ $isLate ? 'text-red-600 font-bold' : 'text-gray-500' }}">{{ $dateStr }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($isPaid)
                                                <span class="text-green-600 font-bold text-xs uppercase">Payé</span>
                                            @else
                                                <span class="text-orange-500 font-bold text-xs uppercase">{{ number_format($ins->amount - $paid, 0, ',', ' ') }} F</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Frais d'inscription et Divers -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 font-semibold text-gray-700">Frais d'Inscription et Divers</div>
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2">Type</th>
                                    <th class="px-4 py-2">Montant</th>
                                    <th class="px-4 py-2">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $regFeeAmount = $tuitionFee ? $tuitionFee->registration_fee : 0;
                                    $miscFeeAmount = $tuitionFee ? $tuitionFee->miscellaneous_fee : 0;
                                    $regPaidAmount = $payments->where('type', 'registration')->sum('amount');
                                    $miscPaidAmount = $payments->where('type', 'miscellaneous')->sum('amount');
                                    $isRegPaid = $regPaidAmount >= $regFeeAmount;
                                    $isMiscPaid = $miscPaidAmount >= $miscFeeAmount;
                                @endphp
                                <tr class="border-b border-gray-100">
                                    <td class="px-4 py-3 font-medium">Inscription</td>
                                    <td class="px-4 py-3">{{ number_format($regFeeAmount, 0, ',', ' ') }} F</td>
                                    <td class="px-4 py-3">
                                        @if($isRegPaid)
                                            <span class="text-green-600 font-bold text-xs uppercase">Payé</span>
                                        @else
                                            <span class="text-orange-500 font-bold text-xs uppercase">Reste: {{ number_format($regFeeAmount - $regPaidAmount, 0, ',', ' ') }} F</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-100">
                                    <td class="px-4 py-3 font-medium">Frais Divers</td>
                                    <td class="px-4 py-3">{{ number_format($miscFeeAmount, 0, ',', ' ') }} F</td>
                                    <td class="px-4 py-3">
                                        @if($isMiscPaid)
                                            <span class="text-green-600 font-bold text-xs uppercase">Payé</span>
                                        @else
                                            <span class="text-orange-500 font-bold text-xs uppercase">Reste: {{ number_format($miscFeeAmount - $miscPaidAmount, 0, ',', ' ') }} F</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Formulaire Paiement -->
                <div class="border border-gray-200 rounded-lg p-5">
                    <h4 class="font-semibold text-gray-700 mb-4">Nouveau Paiement</h4>
                    <form wire:submit.prevent="savePayment" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type de Paiement</label>
                            <select wire:model.live="type" class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="tuition">Scolarité</option>
                                <option value="miscellaneous">Frais Divers</option>
                                <option value="registration">Frais d'Inscription</option>
                            </select>
                        </div>
                        @if($type === 'tuition')
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tranche à payer</label>
                                <select wire:model.live="installment_number" class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Sélectionnez la tranche</option>
                                    @foreach($installments as $ins)
                                        @php
                                            $paid = $payments->where('type', 'tuition')->where('installment_number', $ins->installment_number)->sum('amount');
                                            $rem = $ins->amount - $paid;
                                        @endphp
                                        @if($rem > 0)
                                            <option value="{{ $ins->installment_number }}">T{{ $ins->installment_number }} (Reste: {{ number_format($rem, 0, ',', ' ') }} F)</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Montant (FCFA)</label>
                            <input type="number" wire:model="amount" class="mt-1 block w-full border border-gray-300 bg-white px-3 py-2 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @if($type === 'miscellaneous') 
                                <p class="text-xs text-blue-600 mt-1">Montant auto-calculé selon la règle des Frais Divers.</p>
                            @endif
                        </div>
                        <div class="pt-2">
                            <div class="mt-6">
                                <button type="submit" class="w-full bg-[#1e3a8a] text-white px-6 py-4 rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-800 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Confirmer et Enregistrer le Paiement
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    @elseif($studentId)
        <div class="p-6 text-center text-gray-500">Cet élève n'est pas "Inscrit" pour l'année académique active.</div>
    @else
        <div class="p-16 flex flex-col items-center justify-center text-gray-400">
            <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <p>Veuillez rechercher et sélectionner un élève pour afficher et enregistrer ses paiements.</p>
        </div>
    @endif
</div>
