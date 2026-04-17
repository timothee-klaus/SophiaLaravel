<div>
    @if ($step === 1)
        <!-- Step 1: Identification -->
        <div class="lg:hidden flex flex-col items-center mb-10">
            <div class="w-14 h-14 bg-[#1e3a8a] rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                <span class="text-white text-2xl font-black italic">S</span>
            </div>
            <h1 class="text-xl font-black tracking-[0.1em] text-slate-800 uppercase text-center">Institut Scolaire Sophia</h1>
        </div>

        <h2 class="text-3xl font-bold text-slate-900 mb-2 mt-4 lg:mt-0 tracking-tight">Demande d'accès</h2>
        <p class="text-sm text-slate-500 mb-8 font-medium">Inscrivez-vous pour rejoindre l'équipe administrative. Votre demande sera soumise au directeur après vérification.</p>

        <form wire:submit.prevent="submitRequest" class="space-y-6">
            <div class="space-y-1">
                <label for="name" class="block text-sm font-semibold text-slate-700">Nom complet</label>
                <input wire:model="name" type="text" id="name" required 
                    class="block w-full px-4 py-3.5 border-slate-200 rounded-xl leading-5 bg-white shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] sm:text-sm font-medium transition-all"
                    placeholder="Jean Dupont">
                @error('name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-1">
                <label for="email" class="block text-sm font-semibold text-slate-700">Adresse E-mail professionnelle</label>
                <input wire:model="email" type="email" id="email" required 
                    class="block w-full px-4 py-3.5 border-slate-200 rounded-xl leading-5 bg-white shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] sm:text-sm font-medium transition-all"
                    placeholder="sec@sophia.com">
                @error('email') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="pt-2">
                <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-[#1e3a8a]/20 text-sm font-bold text-white bg-[#1e3a8a] hover:bg-[#152c6e] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a] hover:-translate-y-0.5 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove>Envoyer le code de vérification</span>
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
        <div class="flex flex-col items-center mb-10">
            <div class="w-16 h-16 bg-blue-50 text-[#1e3a8a] rounded-full flex items-center justify-center mb-4 border border-blue-100 italic font-black text-2xl">
                @
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mb-2 tracking-tight">Vérifiez votre e-mail</h2>
            <p class="text-sm text-center text-slate-500 font-medium">
                Nous avons envoyé un code de vérification à :<br>
                <span class="font-bold text-slate-900">{{ $email }}</span>
            </p>
        </div>

        <form wire:submit.prevent="verifyCode" class="space-y-6">
            <div class="space-y-1">
                <label for="inputCode" class="block text-sm font-semibold text-slate-700">Code de vérification (6 chiffres)</label>
                <input wire:model="inputCode" type="text" id="inputCode" maxlength="6" required 
                    class="block w-full px-4 py-4 border-slate-200 rounded-xl leading-5 bg-white shadow-sm text-center text-2xl tracking-[0.5em] font-black focus:outline-none focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] transition-all"
                    placeholder="000000">
                @error('inputCode') <span class="text-xs text-center block text-red-500 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="pt-2">
                <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-[#1e3a8a]/20 text-sm font-bold text-white bg-[#1e3a8a] hover:bg-[#152c6e] hover:-translate-y-0.5 transition-all duration-300 disabled:opacity-50">
                    <span wire:loading.remove>Soumettre la demande</span>
                    <span wire:loading>Vérification...</span>
                    <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </div>
            
            <div class="text-center mt-6">
                <button type="button" wire:click="$set('step', 1)" class="text-xs font-bold text-slate-500 hover:text-[#1e3a8a] uppercase tracking-widest transition-all">Modifier l'email</button>
            </div>
        </form>

    @elseif ($step === 3)
        <!-- Step 3: Success / Waiting Approval -->
        <div class="flex flex-col items-center text-center">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-6 border border-emerald-100 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            
            <h2 class="text-2xl font-bold text-slate-900 mb-2 tracking-tight">Demande envoyée !</h2>
            <p class="text-sm text-slate-500 font-medium mb-8 leading-relaxed px-4">
                Votre email a été vérifié avec succès. Votre demande d'accès est maintenant en attente d'approbation par le directeur.
            </p>

            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-6 mb-10 w-full text-left">
                <h4 class="text-xs font-black uppercase tracking-wider text-blue-600 mb-3">Prochaines étapes</h4>
                <ul class="space-y-3 text-[13px] font-medium text-slate-600">
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px] shrink-0">1</span>
                        Notification envoyée au directeur.
                    </li>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-[10px] shrink-0">2</span>
                        Le directeur valide votre demande.
                    </li>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-[10px] shrink-0">3</span>
                        Vous recevrez un email d'activation.
                    </li>
                </ul>
            </div>

            <a href="{{ route('login') }}" 
                class="inline-flex items-center gap-2 py-3 px-8 text-sm font-bold text-[#1e3a8a] border border-[#1e3a8a]/20 rounded-xl hover:bg-white hover:shadow-sm transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour à l'accueil
            </a>
        </div>
    @endif
</div>
