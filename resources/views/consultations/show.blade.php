<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Consultation — {{ $patiente->nom_complet }}
            </h2>
            <a href="{{ route('consultations.index') }}"
               class="text-blue-600 hover:underline text-sm">
                ← Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('success') }}</div>
            @endif

            {{-- Fiche patiente --}}
            <div class="bg-white shadow rounded p-6">
                <h3 class="font-bold text-lg text-gray-700 mb-4">Informations patiente</h3>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Dossier</p>
                        <p class="font-semibold">{{ $patiente->numero_dossier }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Âge</p>
                        <p class="font-semibold">{{ $patiente->age ? $patiente->age . ' ans' : 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Groupe sanguin</p>
                        <p class="font-semibold">{{ $patiente->groupe_sanguin ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Téléphone</p>
                        <p class="font-semibold">{{ $patiente->telephone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Motif de visite</p>
                        <p class="font-semibold">{{ $patiente->motifVisite->nom ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Assurance</p>
                        @if($patiente->est_assuree)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">✅ Assurée</span>
                        @else
                            <span class="text-gray-400 text-xs">Non assurée</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Nouvelle consultation --}}
            <div class="bg-white shadow rounded p-6">
                <h3 class="font-bold text-lg text-gray-700 mb-4">Nouvelle consultation</h3>

                <form method="POST" action="{{ route('consultations.store') }}">
                    @csrf
                    <input type="hidden" name="patiente_id" value="{{ $patiente->id }}">

                    <div class="grid grid-cols-2 gap-4">

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Acte / Service *</label>
                            <select name="service_id" class="mt-1 border rounded w-full px-3 py-2" required>
                                <option value="">-- Sélectionner un acte --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">
                                        {{ $service->nom }} — {{ number_format($service->prix_standard, 0, ',', ' ') }} GNF
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Motif de consultation</label>
                            <textarea name="motif" rows="2"
                                class="mt-1 border rounded w-full px-3 py-2"
                                placeholder="Motif de la visite..."></textarea>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Diagnostic</label>
                            <textarea name="diagnostic" rows="3"
                                class="mt-1 border rounded w-full px-3 py-2"
                                placeholder="Diagnostic médical..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Poids (kg)</label>
                            <input type="number" name="poids" step="0.1"
                                class="mt-1 border rounded w-full px-3 py-2"
                                placeholder="Ex: 65.5">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tension artérielle</label>
                            <input type="text" name="tension"
                                class="mt-1 border rounded w-full px-3 py-2"
                                placeholder="Ex: 120/80">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Température (°C)</label>
                            <input type="number" name="temperature" step="0.1"
                                class="mt-1 border rounded w-full px-3 py-2"
                                placeholder="Ex: 37.5">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Notes supplémentaires</label>
                            <textarea name="notes" rows="2"
                                class="mt-1 border rounded w-full px-3 py-2"
                                placeholder="Notes..."></textarea>
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Enregistrer la consultation
                        </button>
                    </div>
                </form>
            </div>
            {{-- Demande d'analyse --}}
<div class="bg-white shadow rounded p-6">
    <h3 class="font-bold text-lg text-gray-700 mb-4">Demander une analyse</h3>
    <form method="POST" action="{{ route('laboratoire.creer-demande') }}">
        @csrf
        <input type="hidden" name="patiente_id" value="{{ $patiente->id }}">
        <div class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Type d'analyse</label>
                <input type="text" name="type_analyse"
                    placeholder="Ex: NFS, Glycémie, Échographie..."
                    class="mt-1 border rounded w-full px-3 py-2" required>
            </div>
            <button type="submit"
                class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                Envoyer au labo
            </button>
        </div>
    </form>
</div>

{{-- Résultats d'analyses --}}
<div class="bg-white shadow rounded p-6">
    <h3 class="font-bold text-lg text-gray-700 mb-4">Résultats d'analyses</h3>
    @if($resultatsAnalyses->isNotEmpty())
        <div class="space-y-3">
            @foreach($resultatsAnalyses as $demande)
            <div class="border rounded p-3 {{ $demande->resultat && $demande->resultat->valeur_hors_norme ? 'border-red-200 bg-red-50' : 'border-gray-200' }}">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold">{{ $demande->type_analyse }}</p>
                        <p class="text-xs text-gray-500">Demandé le {{ $demande->created_at->format('d/m/Y H:i') }}</p>
                        @if($demande->resultat)
                            <p class="text-sm mt-2"><strong>Résultat :</strong> {{ $demande->resultat->resultat }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                Saisi par {{ $demande->resultat->saisiPar->name ?? '-' }} le {{ $demande->resultat->created_at->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                    <div class="text-right">
                        @if($demande->resultat && $demande->resultat->valeur_hors_norme)
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">⚠ Hors norme</span>
                        @elseif($demande->resultat)
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">✅ Résultat disponible</span>
                        @elseif($demande->paiement_valide)
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">En cours au labo</span>
                        @else
                            <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded">En attente de paiement</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-sm">Aucune analyse demandée pour cette patiente.</p>
    @endif
</div>



            {{-- Historique des consultations --}}
            @if($consultations->isNotEmpty())
            <div class="bg-white shadow rounded p-6">
                <h3 class="font-bold text-lg text-gray-700 mb-4">Historique des consultations</h3>
                <div class="space-y-3">
                    @foreach($consultations as $consultation)
                    <div class="border rounded p-4 {{ $consultation->estValidee() ? 'border-green-200 bg-green-50' : 'border-yellow-200 bg-yellow-50' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold">{{ $consultation->service->nom ?? 'Acte non renseigné' }}</p>
                                <p class="text-sm text-gray-500">{{ $consultation->created_at->format('d/m/Y H:i') }}</p>
                                @if($consultation->diagnostic)
                                    <p class="text-sm mt-1"><strong>Diagnostic :</strong> {{ $consultation->diagnostic }}</p>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <span class="px-2 py-1 rounded text-xs {{ $consultation->estValidee() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $consultation->estValidee() ? '✅ Validée' : '⏳ Brouillon' }}
                                </span>
                                @if(!$consultation->estValidee())
                                    <form method="POST" action="{{ route('consultations.valider', $consultation) }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700">
                                            Valider
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('consultations.imprimer', $consultation) }}"
                                   target="_blank"
                                   class="bg-gray-600 text-white px-2 py-1 rounded text-xs hover:bg-gray-700">
                                    🖨 Imprimer
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout> 
