{{-- Overlay mobile --}}
<div x-show="sidebarOpen" @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/50 z-30 lg:hidden" x-cloak></div>

{{-- Sidebar --}}
<aside
    class="fixed inset-y-0 left-0 z-40 w-64 bg-gradient-to-b from-emerald-900 to-emerald-800 text-emerald-50 flex flex-col transform transition-transform duration-200 lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    {{-- Logo / nom clinique --}}
    <div class="flex items-center gap-3 px-5 py-6 border-b border-emerald-700/50">
        @if(clinique()->logo_url)
            <img src="{{ clinique()->logo_url }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
        @else
            <div class="w-10 h-10 rounded-lg bg-emerald-600 flex items-center justify-center flex-shrink-0">
                <span class="font-serif font-bold text-lg">{{ Str::substr(clinique()->nom_clinique ?? 'C', 0, 1) }}</span>
            </div>
        @endif
        <div class="min-w-0">
            <p class="font-serif text-sm font-semibold leading-tight truncate">{{ clinique()->nom_clinique ?? 'Clinique' }}</p>
            <p class="text-xs text-emerald-300 truncate">{{ clinique()->slogan ?? '' }}</p>
        </div>
    </div>

    {{-- Liens de navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('patientes.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('patientes.*') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/>
            </svg>
            Patientes
        </a>

        <a href="{{ route('rendez-vous.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('rendez-vous.*') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Rendez-vous
        </a>

        @if(auth()->user()->aRole('super_admin', 'admin'))
        <a href="{{ route('consultations.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('consultations.*') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Espace Médecin
        </a>
        @endif

        @if(auth()->user()->aRole('super_admin', 'admin', 'laborantin'))
        <a href="{{ route('laboratoire.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('laboratoire.index') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v4a1 1 0 01-1 1H4m11-5v4a1 1 0 001 1h4m-9 4v6m4-6v6m-8-6v6a2 2 0 002 2h8a2 2 0 002-2v-6M4 8h16"/>
            </svg>
            Laboratoire
        </a>
        @endif

        @if(auth()->user()->aRole('super_admin', 'admin', 'comptable'))
        <a href="{{ route('laboratoire.en-attente-paiement') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('laboratoire.en-attente-paiement') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3v-1m-3 1v-2m9-5V7a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2v-2m0-5h-4"/>
            </svg>
            Paiements Analyses
        </a>
        @endif

        @if(auth()->user()->aRole('super_admin', 'admin', 'secretaire'))
        <a href="{{ route('factures.create') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('factures.create') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nouvelle Facture
        </a>
        @endif

        @if(auth()->user()->aRole('super_admin', 'admin', 'comptable'))
        <a href="{{ route('factures.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('factures.index') || request()->routeIs('factures.historique') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2m9-8a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Comptabilité
        </a>
        @endif

        <div class="pt-3 mt-3 border-t border-emerald-700/50 space-y-1">

            @if(auth()->user()->aRole('super_admin'))
            <a href="{{ route('audit.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('audit.index') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Journal Audit
            </a>

            <a href="{{ route('utilisateurs.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('utilisateurs.*') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Comptes
            </a>
            @endif

            @if(auth()->user()->aRole('super_admin', 'admin'))
            <a href="{{ route('configuration.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('configuration.index') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Configuration
            </a>

            <a href="{{ route('annonces.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('annonces.*') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                Annonces
            </a>
            @endif

        </div>
    </nav>

    {{-- Utilisateur connecté / déconnexion --}}
    <div class="border-t border-emerald-700/50 p-3">
        <div class="flex items-center gap-3 px-2 py-2">
            <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center flex-shrink-0 text-xs font-semibold">
                {{ Str::substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-emerald-300 truncate">{{ ucfirst(str_replace('_', ' ', auth()->user()->role->nom ?? '')) }}</p>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-emerald-100 hover:bg-emerald-800 transition">
            Profil
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-emerald-100 hover:bg-emerald-800 transition w-full text-left">
                Déconnexion
            </button>
        </form>
    </div>

</aside>
