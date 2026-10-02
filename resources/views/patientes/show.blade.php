<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Fiche Patiente — {{ $patiente->numero_dossier }}
            </h2>
            <a href="{{ route('patientes.edit', $patiente) }}"
               class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Modifier
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Infos principales --}}
            <div class="bg-white shadow rounded p-6">
                <h3 class="font-bold text-lg text-gray-700 mb-4">Informations personnelles</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nom complet</p>
                        <p class="font-semibold">{{ $patiente->nom_complet }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Numéro de dossier</p>
                        <p class="font-semibold">{{ $patiente->numero_dossier }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Téléphone</p>
                        <p class="font-semibold">{{ $patiente->telephone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Âge</p>
                        <p class="font-semibold">{{ $patiente->age ? $patiente->age . ' ans' : 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Groupe sanguin</p>
                        <p class="font-semibold">{{ $patiente->groupe_sanguin ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Adresse</p>
                        <p class="font-semibold">{{ $patiente->adresse ?? 'Non renseignée' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Statut</p>
                        <span class="px-2 py-1 rounded text-xs
                            {{ $patiente->statut === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100' }}">
                            {{ $patiente->statut }}
                        </span>
                    </div>
                    @if($patiente->est_mineure)
                    <div>
                        <p class="text-sm text-gray-500">Responsable légal</p>
                        <p class="font-semibold">{{ $patiente->responsable_legal_nom }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Motif de visite --}}
            <div class="bg-white shadow rounded p-6">
                <h3 class="font-bold text-lg text-gray-700 mb-4">Motif de la visite</h3>
                @if($patiente->motif_visite_id && $patiente->motifVisite)
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Motif</p>
                            <p class="font-semibold">{{ $patiente->motifVisite->nom }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Prix</p>
                            <p class="font-semibold text-green-700">
                                {{ number_format($patiente->motif_visite_prix, 0, ',', ' ') }} GNF
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Aucun motif de visite enregistré.</p>
                @endif
            </div>

            {{-- Assurance --}}
            <div class="bg-white shadow rounded p-6">
                <h3 class="font-bold text-lg text-gray-700 mb-4">
                    Assurance
                    @if($patiente->est_assuree)
                        <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">✅ Assurée</span>
                    @else
                        <span class="ml-2 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Non assurée</span>
                    @endif
                </h3>

                @if($patiente->assurances->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($patiente->assurances as $assurance)
                        <div class="border rounded p-3 {{ $assurance->estValide() ? 'border-blue-200 bg-blue-50' : 'border-gray-200 bg-gray-50' }}">
                            <div class="grid grid-cols-3 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-500">Nom assurance</p>
                                    <p class="font-semibold">{{ $assurance->notes ?? 'Non renseigné' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Taux de couverture</p>
                                    <p class="font-semibold text-blue-700">{{ $assurance->taux_couverture }}%</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Domaine</p>
                                    <p class="font-semibold">{{ ucfirst($assurance->domaine) }}</p>
                                </div>
                                @if($assurance->numero_police)
                                <div>
                                    <p class="text-gray-500">N° Police</p>
                                    <p class="font-semibold">{{ $assurance->numero_police }}</p>
                                </div>
                                @endif
                                @if($assurance->date_fin)
                                <div>
                                    <p class="text-gray-500">Expiration</p>
                                    <p class="font-semibold {{ $assurance->date_fin->isPast() ? 'text-red-600' : '' }}">
                                        {{ $assurance->date_fin->format('d/m/Y') }}
                                    </p>
                                </div>
                                @endif
                                <div>
                                    <p class="text-gray-500">Statut</p>
                                    <span class="px-2 py-1 rounded text-xs {{ $assurance->estValide() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $assurance->estValide() ? 'Valide' : 'Expirée/Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Aucune assurance enregistrée.</p>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('patientes.index') }}"
                   class="text-blue-600 hover:underline">
                    ← Retour à la liste
                </a>
            </div>

        </div>
    </div>
</x-app-layout>