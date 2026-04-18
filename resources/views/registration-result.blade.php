<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Résultat | Sophia' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-['Inter'] antialiased bg-slate-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Background Branding -->
    <img src="{{ asset('images/campus-bg.png') }}" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay pointer-events-none" alt="Campus">
    <div class="absolute inset-0 bg-gradient-to-br from-[#1e3a8a]/40 via-slate-900 to-slate-900 pointer-events-none"></div>

    <!-- Soft decorative glow -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-lg">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
            <!-- Header/Icon area -->
            <div class="p-8 pb-0 text-center">
                <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6 {{ $status === 'success' ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' }}">
                    @if($status === 'success')
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    @else
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    @endif
                </div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $title ?? ($status === 'success' ? 'Opération Réussie' : 'Attention') }}</h1>
            </div>

            <!-- Content -->
            <div class="p-8 text-center text-slate-600 font-medium">
                <p class="leading-relaxed whitespace-pre-line">{{ $message }}</p>
                
                <div class="mt-10 pt-8 border-t border-slate-100">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-sm font-bold rounded-xl text-white bg-[#1e3a8a] hover:bg-[#152c6e] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a] shadow-lg shadow-[#1e3a8a]/20 transition-all hover:-translate-y-0.5 duration-300">
                        Retour à l'accueil
                    </a>
                </div>
            </div>

            <!-- Branding Footer -->
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 text-center">
                <p class="text-[10px] font-black tracking-[0.2em] text-[#1e3a8a] uppercase italic">Institut Scolaire Sophia</p>
            </div>
        </div>
    </div>
</body>
</html>
