<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Tableau de bord
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('dashboard', ['periode' => 'jour']) }}"
                   class="px-3 py-1 rounded text-sm {{ $periode === 'jour' ? 'bg-emerald-700 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Aujourd'hui
                </a>
                <a href="{{ route('dashboard', ['periode' => 'semaine']) }}"
                   class="px-3 py-1 rounded text-sm {{ $periode === 'semaine' ? 'bg-emerald-700 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Cette semaine
                </a>
                <a href="{{ route('dashboard', ['periode' => 'mois']) }}"
                   class="px-3 py-1 rounded text-sm {{ $periode === 'mois' ? 'bg-emerald-700 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Ce mois
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Bandeau roulant : annonces + stats --}}
            <div class="bg-gradient-to-r from-emerald-800 to-emerald-700 text-white rounded-xl shadow-md overflow-hidden">
                <div id="bandeau-contenu" class="flex items-center gap-4 px-6 py-6 min-h-[90px] transition-opacity duration-500">
                </div>
            </div>

            {{-- Cartes stats — petites, carrées, indépendantes --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <div class="bg-white shadow rounded-lg p-4 border-l-4 border-blue-600">
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.7M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                        </svg>
                        <p class="text-xs text-gray-500">Patientes actives</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['patientes_total'] }}</p>
                </div>

                <div class="bg-white shadow rounded-lg p-4 border-l-4 border-purple-600">
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                        <p class="text-xs text-gray-500">Rendez-vous</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['rdv_periode'] }}</p>
                </div>

                <div class="bg-white shadow rounded-lg p-4 border-l-4 border-emerald-600">
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.652a3.75 3.75 0 010-5.304m5.304 0a3.75 3.75 0 010 5.304m-7.425 2.121a6.75 6.75 0 010-9.546m9.546 0a6.75 6.75 0 010 9.546M5.106 18.894c-3.808-3.807-3.808-9.98 0-13.788m13.788 0c3.808 3.807 3.808 9.98 0 13.788M12 12h.008v.008H12V12z"/>
                        </svg>
                        <p class="text-xs text-gray-500">Consultations</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['consultations_periode'] }}</p>
                </div>

                <div class="bg-white shadow rounded-lg p-4 border-l-4 border-amber-500">
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        <p class="text-xs text-gray-500">Urgences</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['urgences_periode'] }}</p>
                </div>

               @if(auth()->user()->aRole('super_admin', 'admin'))
<div class="bg-white shadow rounded-lg p-4 border-l-4 border-emerald-600">
    <div class="flex items-center gap-2 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs text-gray-500">Revenus (payés)</p>
    </div>
    <p class="text-xl font-bold text-emerald-700">{{ number_format($stats['revenus_periode'], 0, ',', ' ') }} GNF</p>
    <p class="text-xs text-gray-400 mt-1">{{ $stats['factures_attente'] }} factures en attente</p>
</div>
@endif

                <div class="bg-white shadow rounded-lg p-4 border-l-4 border-rose-500">
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                        </svg>
                        <p class="text-xs text-gray-500">Brouillons</p>
                    </div>
                    <p class="text-2xl font-bold text-rose-700">{{ $stats['consultations_brouillon'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">non encore validées</p>
                </div>

                <div class="bg-white shadow rounded-lg p-4 border-l-4 border-orange-500">
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>
                        </svg>
                        <p class="text-xs text-gray-500">Analyses en attente</p>
                    </div>
                    <p class="text-2xl font-bold text-orange-700">{{ $stats['analyses_attente_paiement'] + $stats['analyses_attente_labo'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $stats['analyses_attente_paiement'] }} paiement · {{ $stats['analyses_attente_labo'] }} labo</p>
                </div>

                <div class="bg-white shadow rounded-lg p-4 border-l-4 border-slate-400">
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/>
                        </svg>
                        <p class="text-xs text-gray-500">RDV aujourd'hui</p>
                    </div>
                    <p class="text-2xl font-bold text-slate-700">{{ $stats['rdv_aujourdhui'] }}</p>
                </div>

            </div>

            {{-- Flux de patients + Répartition par acte --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-4">Flux de patients (7 derniers jours) & Répartition par acte</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">Nouvelles patientes / jour</p>
                        <canvas id="fluxChart" height="220"></canvas>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-2">Répartition par acte (période sélectionnée)</p>
                        @if($repartitionActes->isNotEmpty())
                            <canvas id="repartitionChart" height="220"></canvas>
                        @else
                            <p class="text-gray-400 text-sm text-center py-10">Aucune consultation sur cette période.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Prochains rendez-vous + Accès rapide --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white shadow rounded-lg p-4">
                    <h3 class="font-semibold text-gray-700 mb-3">Prochains rendez-vous</h3>
                    @if($prochainesRdv->isNotEmpty())
                        <div class="space-y-2">
                            @foreach($prochainesRdv as $rdv)
                            <div class="flex justify-between items-center border-t pt-2 text-sm">
                                <div>
                                    <span class="font-semibold">{{ $rdv->patiente->nom_complet }}</span>
                                    <span class="text-gray-400"> — {{ $rdv->medecin->nom }}</span>
                                </div>
                                <span class="text-gray-600">{{ $rdv->date_heure_prevue->format('d/m/Y H:i') }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-sm">Aucun rendez-vous à venir.</p>
                    @endif
                </div>

                <div class="bg-white shadow rounded-lg p-4">
                    <h3 class="font-semibold text-gray-700 mb-3">Accès rapide</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('patientes.index') }}" class="bg-blue-50 hover:bg-blue-100 rounded p-3 text-center text-sm text-blue-800">
                            Patientes
                        </a>
                        <a href="{{ route('rendez-vous.index') }}" class="bg-purple-50 hover:bg-purple-100 rounded p-3 text-center text-sm text-purple-800">
                            Rendez-vous
                        </a>
                        @if(auth()->user()->aRole('super_admin', 'admin'))
                        <a href="{{ route('consultations.index') }}" class="bg-green-50 hover:bg-green-100 rounded p-3 text-center text-sm text-green-800">
                            Espace Médecin
                        </a>
                        @endif
                        @if(auth()->user()->aRole('super_admin', 'admin', 'laborantin'))
                        <a href="{{ route('laboratoire.index') }}" class="bg-orange-50 hover:bg-orange-100 rounded p-3 text-center text-sm text-orange-800">
                            Laboratoire
                        </a>
                        @endif
                        @if(auth()->user()->aRole('super_admin', 'admin', 'comptable'))
                        <a href="{{ route('factures.index') }}" class="bg-emerald-50 hover:bg-emerald-100 rounded p-3 text-center text-sm text-emerald-800">
                            Comptabilité
                        </a>
                        @endif
                        @if(auth()->user()->aRole('super_admin'))
                        <a href="{{ route('audit.index') }}" class="bg-red-50 hover:bg-red-100 rounded p-3 text-center text-sm text-red-800">
                            Journal Audit
                        </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        const fluxCtx = document.getElementById('fluxChart');
        if (fluxCtx) {
            new Chart(fluxCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(collect($flux7jours)->pluck('label')) !!},
                    datasets: [{
                        label: 'Nouvelles patientes',
                        data: {!! json_encode(collect($flux7jours)->pluck('total')) !!},
                        backgroundColor: '#059669',
                        borderRadius: 4,
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }

        const repartitionCtx = document.getElementById('repartitionChart');
        if (repartitionCtx) {
            new Chart(repartitionCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($repartitionActes->pluck('nom')) !!},
                    datasets: [{
                        data: {!! json_encode($repartitionActes->pluck('total')) !!},
                        backgroundColor: ['#065f46', '#059669', '#34d399', '#f59e0b', '#dc2626', '#0891b2'],
                    }]
                },
                options: {
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
                }
            });
        }

        // Bandeau roulant
        const slides = [];

        @foreach($annonces as $annonce)
        slides.push({
            type: 'annonce',
            titre: @json($annonce->titre),
            contenu: @json(\Illuminate\Support\Str::limit($annonce->contenu, 120)),
            image: @json($annonce->image_url),
        });
        @endforeach

        slides.push({ type: 'stat', texte: 'Rendez-vous sur la période', valeur: {{ $stats['rdv_periode'] }} });
        slides.push({ type: 'stat', texte: 'Consultations sur la période', valeur: {{ $stats['consultations_periode'] }} });
        slides.push({ type: 'stat', texte: 'Patientes actives', valeur: {{ $stats['patientes_total'] }} });
        @if(auth()->user()->aRole('super_admin', 'admin'))
slides.push({ type: 'stat', texte: 'Revenus encaissés sur la période', valeur: new Intl.NumberFormat('fr-FR').format({{ $stats['revenus_periode'] }}) + ' GNF' });
@endif

        let indexSlide = 0;
        const bandeau = document.getElementById('bandeau-contenu');

        function afficherSlide() {
            if (slides.length === 0) return;
            const slide = slides[indexSlide];
            bandeau.style.opacity = 0;

            setTimeout(() => {
                if (slide.type === 'annonce') {
                    bandeau.innerHTML = `
                        ${slide.image ? `<img src="${slide.image}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">` : ''}
                        <div>
                            <p class="font-serif text-xl font-semibold">${slide.titre}</p>
                            <p class="text-sm text-emerald-100 mt-1">${slide.contenu}</p>
                        </div>
                    `;
                } else {
                    bandeau.innerHTML = `
                        <div>
                            <p class="text-3xl font-bold">${slide.valeur}</p>
                            <p class="text-sm text-emerald-100 mt-1">${slide.texte}</p>
                        </div>
                    `;
                }
                bandeau.style.opacity = 1;
            }, 300);

            indexSlide = (indexSlide + 1) % slides.length;
        }

        afficherSlide();
        setInterval(afficherSlide, 3000);
    </script>
</x-app-layout>