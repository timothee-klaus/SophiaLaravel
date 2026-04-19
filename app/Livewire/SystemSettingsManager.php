<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SystemSettingsManager extends Component
{
    public bool $maintenanceMode = false;
    public string $successMessage = '';
    public string $errorMessage = '';

    public function toggleMaintenance()
    {
        // Mocking maintenance mode toggle
        $this->maintenanceMode = !$this->maintenanceMode;
        $this->successMessage = $this->maintenanceMode 
            ? 'Plateforme passée en mode maintenance.' 
            : 'Plateforme en ligne et accessible.';
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            $this->successMessage = 'Cache du système vidé avec succès.';
        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur lors du vidage du cache : ' . $e->getMessage();
        }
    }

    public function exportDatabase()
    {
        // Mocking export for demo
        $this->successMessage = 'Sauvegarde de la base de données générée avec succès. Le fichier sera prêt dans un instant.';
    }

    public function render()
    {
        return <<<'BLADE'
        <div class="space-y-8 mt-10">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#0f172a] flex items-center justify-center text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Paramètres Système Avancés</h3>
                            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">Maintenance et outils d'administration globale</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if($successMessage)
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-3 animate-fade-in">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm font-bold text-emerald-700">{{ $successMessage }}</span>
                        </div>
                    @endif

                    @if($errorMessage)
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-center gap-3 animate-fade-in">
                            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm font-bold text-rose-700">{{ $errorMessage }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Mode Maintenance -->
                        <div class="p-5 border border-slate-100 rounded-2xl bg-white shadow-sm flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-black text-slate-800 uppercase mb-1 tracking-wider">Mode Maintenance</h4>
                                <p class="text-[10px] text-slate-400 font-medium leading-relaxed">Désactivez temporairement l'accès à la plateforme pour les utilisateurs non-administrateurs.</p>
                            </div>
                            <div class="mt-6 flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase {{ $maintenanceMode ? 'text-rose-500' : 'text-emerald-500' }}">
                                    {{ $maintenanceMode ? 'Activé' : 'Désactivé' }}
                                </span>
                                <button wire:click="toggleMaintenance" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $maintenanceMode ? 'bg-[#0f172a]' : 'bg-slate-200' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $maintenanceMode ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Sauvegarde -->
                        <div class="p-5 border border-slate-100 rounded-2xl bg-white shadow-sm flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-black text-slate-800 uppercase mb-1 tracking-wider">Base de Données</h4>
                                <p class="text-[10px] text-slate-400 font-medium leading-relaxed">Exportez l'état actuel de toutes les tables pour archivage ou migration.</p>
                            </div>
                            <div class="mt-6">
                                <button wire:click="exportDatabase" class="w-full py-2.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-bold text-[10px] uppercase hover:bg-[#0f172a] hover:text-white hover:border-[#0f172a] transition-all flex items-center justify-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Exporter (.SQL)
                                </button>
                            </div>
                        </div>

                        <!-- Cache -->
                        <div class="p-5 border border-slate-100 rounded-2xl bg-white shadow-sm flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-black text-slate-800 uppercase mb-1 tracking-wider">Vider le Cache</h4>
                                <p class="text-[10px] text-slate-400 font-medium leading-relaxed">Réinitialisez les fichiers temporaires pour résoudre les problèmes d'affichage ou de configuration.</p>
                            </div>
                            <div class="mt-6">
                                <button wire:click="clearCache" class="w-full py-2.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-bold text-[10px] uppercase hover:bg-amber-100 hover:text-amber-800 hover:border-amber-200 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Nettoyer les fichiers
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        BLADE;
    }
}
