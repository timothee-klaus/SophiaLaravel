<div x-data="{
    revenueChartInstance: null,
    enrollmentsChartInstance: null,
    hasRevenueData() {
        return $wire.data && $wire.data.length > 0 && $wire.data.some(v => v > 0);
    },
    hasEnrollmentData() {
        return $wire.levelData && $wire.levelData.length > 0 && $wire.levelData.some(v => v > 0);
    },
    init() {
        if (typeof Chart === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = () => { this.renderCharts(); };
            document.head.appendChild(script);
        } else {
            this.renderCharts();
        }
        
        $watch('$wire.levelData', () => {
            this.renderCharts();
        });
    },
    renderCharts() {
        setTimeout(() => {
            if (this.hasRevenueData()) this.renderRevenueChart();
            if (this.hasEnrollmentData()) this.renderEnrollmentsChart();
        }, 50);
    },
    renderRevenueChart() {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        if (this.revenueChartInstance) this.revenueChartInstance.destroy();
        this.revenueChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [...$wire.labels],
                datasets: [{
                    data: [...$wire.data],
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#f59e0b'],
                    hoverBackgroundColor: ['#2563eb', '#7c3aed', '#d97706'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: '\'Inter\', sans-serif' }, usePointStyle: true, padding: 20 }
                    }
                },
                cutout: '70%'
            }
        });
    },
    renderEnrollmentsChart() {
        const ctx = document.getElementById('enrollmentsChart');
        if (!ctx) return;
        if (this.enrollmentsChartInstance) this.enrollmentsChartInstance.destroy();
        this.enrollmentsChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [...$wire.levelLabels],
                datasets: [{
                    label: 'Nombre d\'élèves',
                    data: [...$wire.levelData],
                    backgroundColor: '#6366f1',
                    borderRadius: 6,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: true, color: '#f1f5f9', drawBorder: false },
                        ticks: { font: { family: '\'Inter\', sans-serif' }, precision: 0 }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: '\'Inter\', sans-serif' } }
                    }
                }
            }
        });
    }
}" class="space-y-6">    <!-- KPI Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Recettes du jour -->
        <div class="p-6 bg-white border border-slate-200/60 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Recettes de l'année</h3>
                    <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($yearlyRevenue, 0, ',', ' ') }} <span class="text-lg text-slate-500 font-medium">F</span></p>
                </div>
            </div>
        </div>

        <!-- Inscriptions du mois -->
        <div class="p-6 bg-white border border-slate-200/60 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Inscriptions</h3>
                    <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $yearlyEnrollments }}</p>
                </div>
            </div>
        </div>

        <!-- Dossiers incomplets -->
        <div class="p-6 bg-white border border-slate-200/60 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 bg-amber-50 text-amber-500 rounded-xl shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Dossiers Incomplets</h3>
                    <p class="text-3xl font-extrabold text-amber-500 mt-1">{{ $incompleteFiles }}</p>
                </div>
            </div>
        </div>

        <!-- Retards critiques -->
        <div class="p-6 bg-white border border-red-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-2 h-full bg-red-500 group-hover:w-3 transition-all duration-300"></div>
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 bg-red-50 text-red-500 rounded-xl shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Retards Critiques</h3>
                    <p class="text-3xl font-extrabold text-red-500 mt-1">{{ $lateStudentsCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Graphiques Modulaires -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Chart: Répartition des revenus -->
        <div class="p-6 bg-white border border-slate-200/60 rounded-2xl shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Répartition des Revenus</h3>
            <div class="relative h-64 w-full flex flex-col justify-center items-center">
                <canvas id="revenueChart" x-show="hasRevenueData()"></canvas>
                <div x-show="!hasRevenueData()" class="flex flex-col items-center justify-center space-y-3 opacity-60 py-10">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    <p class="text-sm font-medium text-slate-400">Aucun revenu enregistré</p>
                </div>
            </div>
        </div>

        <!-- Chart: Inscriptions par niveau -->
        <div class="p-6 bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h3 class="text-lg font-bold text-slate-800">Inscriptions par Niveau</h3>
                <div class="relative min-w-[140px]">
                    <select wire:model.live="filterCycle" class="appearance-none block w-full pl-4 pr-10 py-2 text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer">
                        <option value="">Tous les cycles</option>
                        <option value="preschool">Maternelle</option>
                        <option value="primary">Primaire</option>
                        <option value="college">Collège</option>
                        <option value="lycee">Lycée</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
            <div class="relative h-64 w-full flex flex-col justify-center items-center">
                <canvas id="enrollmentsChart" x-show="hasEnrollmentData()"></canvas>
                <div x-show="!hasEnrollmentData()" class="flex flex-col items-center justify-center space-y-3 opacity-60 py-10">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <p class="text-sm font-medium text-slate-400">Aucune inscription active</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Tableau récent -->
    <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden mt-6">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800">Activités Récentes</h3>
            <a href="{{ route('payments') }}" class="px-4 py-2 text-sm font-semibold text-white transition-all bg-gradient-to-tr from-blue-600 to-purple-600 rounded-xl hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5">
                + Nouveau Paiement
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-400 uppercase bg-slate-50 border-b border-slate-100 font-semibold tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4">Reçu N°</th>
                        <th scope="col" class="px-6 py-4">Élève</th>
                        <th scope="col" class="px-6 py-4">Motif / Détails</th>
                        <th scope="col" class="px-6 py-4 text-right">Montant</th>
                        <th scope="col" class="px-6 py-4 text-center">Date & Heure</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentPayments as $payment)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-500">{{ $payment->transaction_id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ mb_strtoupper($payment->student->last_name) }} {{ $payment->student->first_name }}</div>
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
                                        <p class="text-sm font-medium text-slate-500 mt-1">Aucun paiement récent dans le système.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 text-center border-t border-slate-100 bg-slate-50/50">
            <a href="{{ route('payments') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors">Tout afficher l'historique &rarr;</a>
        </div>
    </div>

    <!-- Alpine component logic has been moved inline on x-data above to prevent SPA bugs -->
</div>
