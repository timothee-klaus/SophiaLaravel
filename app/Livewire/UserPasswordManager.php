<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserPasswordManager extends Component
{
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';
    public string $successMessage = '';

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'new_password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas.',
            'new_password.min' => 'Le nouveau mot de passe doit faire au moins 8 caractères.',
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->successMessage = 'Votre mot de passe a été mis à jour avec succès.';
    }

    public function render()
    {
        return <<<'BLADE'
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-8">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#0f172a] flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Paramètres de Sécurité</h3>
                        <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">Modifier votre mot de passe personnel</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if($successMessage)
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-3 animate-fade-in">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm font-bold text-emerald-700">{{ $successMessage }}</span>
                        <button wire:click="$set('successMessage', '')" class="ml-auto text-emerald-400 hover:text-emerald-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                <form wire:submit.prevent="updatePassword" class="max-w-md space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2 ml-1">Mot de passe actuel</label>
                        <input type="password" wire:model="current_password" 
                            class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0f172a]/20 focus:border-[#0f172a] transition-all bg-slate-50/50" 
                            placeholder="••••••••">
                        @error('current_password') <span class="text-rose-500 text-[10px] font-bold mt-1 block px-1 uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2 ml-1">Nouveau mot de passe</label>
                            <input type="password" wire:model="new_password" 
                                class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0f172a]/20 focus:border-[#0f172a] transition-all bg-slate-50/50" 
                                placeholder="Min. 8 caractères">
                            @error('new_password') <span class="text-rose-500 text-[10px] font-bold mt-1 block px-1 uppercase">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2 ml-1">Confirmation</label>
                            <input type="password" wire:model="new_password_confirmation" 
                                class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0f172a]/20 focus:border-[#0f172a] transition-all bg-slate-50/50" 
                                placeholder="Confirmer">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" 
                            class="px-6 py-2.5 bg-[#0f172a] text-white rounded-xl font-bold text-sm shadow-md shadow-[#0f172a]/20 hover:bg-[#000000] hover:-translate-y-0.5 transition-all active:scale-95">
                            Mettre à jour mon mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
        BLADE;
    }
}
