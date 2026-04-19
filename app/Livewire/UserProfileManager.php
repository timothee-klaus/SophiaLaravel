<?php

namespace App\Livewire;

use Livewire\Component;

class UserProfileManager extends Component
{
    public string $name = '';
    public string $email = '';
    public string $language = 'fr';
    public array $notifications = [
        'email_alerts' => true,
        'browser_notifications' => false,
        'payment_reports' => true,
    ];
    public string $successMessage = '';

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        // In a real app, these would come from user settings table or JSON column
        $this->language = 'fr';
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->successMessage = 'Profil et préférences mis à jour.';
    }

    public function render()
    {
        return <<<'BLADE'
        <div class="space-y-8">
            <!-- Informations de base -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#0f172a] flex items-center justify-center text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Informations Personnelles</h3>
                            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">Gérez vos coordonnées de compte</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if($successMessage)
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm font-bold text-emerald-700">{{ $successMessage }}</span>
                        </div>
                    @endif

                    <form wire:submit.prevent="updateProfile" class="max-w-4xl space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2 ml-1">Nom Complet</label>
                                <input type="text" wire:model="name" class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0f172a]/20 focus:border-[#0f172a] transition-all bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2 ml-1">Adresse E-mail</label>
                                <input type="email" wire:model="email" class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0f172a]/20 focus:border-[#0f172a] transition-all bg-slate-50/50">
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <!-- Langue -->
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-3 ml-1">Langue & Région</label>
                                <select wire:model="language" class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0f172a]/20 focus:border-[#0f172a] transition-all bg-slate-50/50">
                                    <option value="fr">Français (France)</option>
                                    <option value="en">English (US)</option>
                                </select>
                            </div>

                            <!-- Notifications -->
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-3 ml-1">Préférences de Notification</label>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" wire:model="notifications.email_alerts" class="w-4 h-4 text-[#0f172a] border-slate-300 rounded focus:ring-[#0f172a]">
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#0f172a] transition-colors">Alertes de connexion par e-mail</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" wire:model="notifications.payment_reports" class="w-4 h-4 text-[#0f172a] border-slate-300 rounded focus:ring-[#0f172a]">
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#0f172a] transition-colors">Rapports financiers hebdomadaires</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-[#0f172a] text-white rounded-xl font-bold text-sm shadow-md shadow-black/10 hover:bg-black hover:-translate-y-0.5 transition-all">
                                Sauvegarder les préférences
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sessions actives (Aperçu) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#0f172a] flex items-center justify-center text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 21a11.955 11.955 0 01-9.618-7.016m14.436-4.384L15 12M9 12l1.118-4.016A11.955 11.955 0 0112 3a11.955 11.955 0 019.618 7.016"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Sécurité de l'Appareil</h3>
                            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">Gérez vos sessions actives</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-white rounded-lg border border-slate-200">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Linux - Chrome (Session actuelle)</p>
                                    <p class="text-xs text-slate-500">Dernière activité : Il y a un instant • Lomé, Togo</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase rounded-lg">Actif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        BLADE;
    }
}
