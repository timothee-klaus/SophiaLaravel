<div class="flex items-center gap-4 ml-4" x-data="{ showNotifications: false, showYear: false }">
    <!-- Bouton Notifications -->
    <div class="relative">
        <button @click="showNotifications = !showNotifications; if(showNotifications) { $wire.markAsRead() }" @click.away="showNotifications = false" class="relative p-2 text-slate-400 transition-all duration-300 bg-slate-800 rounded-full hover:text-white hover:bg-slate-700 hover:shadow-md focus:outline-none hover:-translate-y-0.5">
            @if($notifications->count() > 0)
                <span class="absolute top-1 right-1 flex w-2.5 h-2.5 z-10">
                    <span class="absolute inline-flex w-full h-full bg-rose-400 rounded-full opacity-75 animate-ping"></span>
                    <span class="relative inline-flex w-full h-full bg-rose-500 rounded-full border border-slate-900"></span>
                </span>
            @endif
            <svg class="w-5 h-5 relative z-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
        </button>

        <!-- Dropdown Notifications -->
        <div x-show="showNotifications" x-transition.opacity x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden z-50 origin-top-right backdrop-blur-lg" style="display: none;">
            <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-800">Notifications</h3>
                <span class="text-xs text-slate-500 font-medium badge">{{ $notifications->count() > 0 ? $notifications->count() . ' Nouveaux' : 'À jour' }}</span>
            </div>
            <div class="max-h-64 overflow-y-auto w-full divide-y divide-slate-50">
                @forelse($notifications as $notification)
                    <div class="px-4 py-3 hover:bg-blue-50/50 transition duration-150 cursor-pointer w-full text-left flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-xs text-slate-800 font-semibold">{{ $notification->data['title'] ?? 'Notification' }}</p>
                            <p class="text-xs text-slate-500 mt-0.5 leading-snug">{{ $notification->data['message'] ?? 'Aucun détail' }}</p>
                            <p class="text-[10px] text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-700">Tout va bien !</h4>
                        <p class="text-xs text-slate-500 mt-1">Vous n'avez pas de nouvelles notifications.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="h-6 w-px bg-slate-700"></div>

    <!-- Menu Utilisateur (Dropdown Année Académique) -->
    <div class="relative group">
        <button @click="showYear = !showYear" @click.away="showYear = false" class="flex items-center gap-2 p-2 px-3 text-sm font-medium text-slate-300 transition-all duration-300 rounded-xl hover:text-white hover:bg-slate-800 focus:outline-none border border-transparent hover:border-slate-700">
            <span class="hidden md:block">Année : <strong class="text-blue-400 font-extrabold ml-1">{{ $activeYear ? $activeYear->name : 'Aucune' }}</strong></span>
            <svg class="w-4 h-4 text-slate-500 group-hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>
        
        <!-- Dropdown Années -->
        <div x-show="showYear" x-transition.opacity x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden z-50 origin-top-right transition-all backdrop-blur-lg p-1" style="display: none;">
            <div class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Passer à l'année :</div>
            @forelse($academicYears as $year)
                <button wire:click="switchYear({{ $year->id }})" class="w-full text-left px-4 py-2 text-sm font-bold rounded-xl flex justify-between items-center transition-colors {{ $activeYearId == $year->id ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    {{ $year->name }}
                    @if($activeYearId == $year->id)
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    @endif
                </button>
            @empty
                <div class="px-4 py-6 text-center">
                    <p class="text-xs text-slate-400 italic">Aucune année configurée</p>
                    <a href="{{ route('academic-years') }}" class="text-[10px] font-bold text-blue-600 hover:underline mt-2 inline-block">Gérer les années</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
