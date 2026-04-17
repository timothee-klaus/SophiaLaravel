<div>
    @if ($step === 1)
        <!-- Step 1: Director Info -->
        <div class="lg:hidden flex flex-col items-center mb-10">
            <div class="w-14 h-14 bg-[#1e3a8a] rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                <span class="text-white text-2xl font-black italic">S</span>
            </div>
            <h1 class="text-xl font-black tracking-[0.1em] text-slate-800 uppercase text-center">Institut Scolaire Sophia</h1>
        </div>

        <h2 class="text-3xl font-bold text-slate-900 mb-2 mt-4 lg:mt-0 tracking-tight">Inscription Directeur</h2>
        <p class="text-sm text-slate-500 mb-8 font-medium">Créez votre compte administrateur principal. Une vérification par e-mail est requise.</p>

        <form wire:submit.prevent="submitRequest" class="space-y-4">
            <div class="space-y-1">
                <label for="name" class="block text-sm font-semibold text-slate-700">Nom complet</label>
                <input wire:model="name" type="text" id="name" required 
                    class="block w-full px-4 py-3 border-slate-200 rounded-xl leading-5 bg-white shadow-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] sm:text-sm font-medium transition-all"
                    placeholder="Prénom et Nom">
                @error('name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-1">
                <label for="email" class="block text-sm font-semibold text-slate-700">Adresse E-mail</label>
                <input wire:model="email" type="email" id="email" required 
                    class="block w-full px-4 py-3 border-slate-200 rounded-xl leading-5 bg-white shadow-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] sm:text-sm font-medium transition-all"
                    placeholder="directeur@sophia.com">
                @error('email') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="password" class="block text-sm font-semibold text-slate-700">Mot de passe</label>
                    <input wire:model="password" type="password" id="password" required 
                        class="block w-full px-4 py-3 border-slate-200 rounded-xl leading-5 bg-white shadow-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] sm:text-sm font-medium transition-all"
                        placeholder="••••••••">
                </div>
                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirmation</label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation" required 
                        class="block w-full px-4 py-3 border-slate-200 rounded-xl leading-5 bg-white shadow-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] sm:text-sm font-medium transition-all"
                        placeholder="••••••••">
                </div>
                @error('password') <div class="col-span-2 text-xs text-red-500 font-medium">{{ $message }}</div> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-[#1e3a8a]/20 text-sm font-bold text-white bg-[#1e3a8a] hover:bg-[#152c6e] hover:-translate-y-0.5 transition-all duration-300">
                    <span wire:loading.remove>Vérifier mon e-mail</span>
                    <span wire:loading>Envoi en cours...</span>
                    <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
            
            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-sm font-bold text-[#1e3a8a] hover:underline transition-all">Retour à la connexion</a>
            </div>
        </form>

    @elseif ($step === 2)
        <!-- Step 2: Code Verification -->
        <div class="flex flex-col items-center mb-10 text-center">
            <div class="w-16 h-16 bg-blue-50 text-[#1e3a8a] rounded-full flex items-center justify-center mb-4 border border-blue-100 italic font-black text-2xl">
                @
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mb-2 tracking-tight">Code de vérification</h2>
            <p class="text-sm text-slate-500 font-medium">
                Vérifiez votre boîte mail. Saisissez le code envoyé à :<br>
                <span class="font-bold text-slate-900">{{ $email }}</span>
            </p>
        </div>

        <form wire:submit.prevent="verifyCode" class="space-y-6">
            <div class="space-y-1">
                <input wire:model="inputCode" type="text" id="inputCode" maxlength="6" required 
                    class="block w-full px-4 py-4 border-slate-200 rounded-xl leading-5 bg-white shadow-sm text-center text-2xl tracking-[0.5em] font-black focus:outline-none focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] transition-all"
                    placeholder="000000">
                @error('inputCode') <span class="text-xs text-center block text-red-500 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="pt-2">
                <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-[#1e3a8a]/20 text-sm font-bold text-white bg-[#1e3a8a] hover:bg-[#152c6e] hover:-translate-y-0.5 transition-all duration-300">
                    <span wire:loading.remove>Créer mon compte</span>
                    <span wire:loading>Création...</span>
                    <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </div>
            
            <div class="text-center mt-6">
                <button type="button" wire:click="$set('step', 1)" class="text-xs font-bold text-slate-500 hover:text-[#1e3a8a] uppercase tracking-widest transition-all">Précédent</button>
            </div>
        </form>
    @endif
</div>
