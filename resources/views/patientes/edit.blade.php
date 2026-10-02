<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Modifier — {{ $patiente->numero_dossier }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                <form method="POST" action="{{ route('patientes.update', $patiente) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom *</label>
                            <input type="text" name="nom"
                                value="{{ old('nom', $patiente->nom) }}"
                                class="mt-1 border rounded w-full px-3 py-2" required>
                            @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prénom *</label>
                            <input type="text" name="prenom"
                                value="{{ old('prenom', $patiente->prenom) }}"
                                class="mt-1 border rounded w-full px-3 py-2" required>
                            @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="text" name="telephone"
                                value="{{ old('telephone', $patiente->telephone) }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Adresse</label>
                            <input type="text" name="adresse"
                                value="{{ old('adresse', $patiente->adresse) }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Groupe sanguin</label>
                            <select name="groupe_sanguin" class="mt-1 border rounded w-full px-3 py-2">
                                <option value="">-- Sélectionner --</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $gs)
                                    <option value="{{ $gs }}"
                                        {{ old('groupe_sanguin', $patiente->groupe_sanguin) === $gs ? 'selected' : '' }}>
                                        {{ $gs }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Sauvegarder
                        </button>
                        <a href="{{ route('patientes.show', $patiente) }}"
                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
         {{-- Section Assurance --}}
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 mt-4">
    <div class="bg-white shadow rounded p-6">
        <h3 class="font-bold text-lg text-gray-700 mb-4">Assurance</h3>

        <form method="POST" action="{{ route('patientes.update-assurance', $patiente) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="est_assuree" value="1" id="edit_est_assuree"
                        {{ $patiente->est_assuree ? 'checked' : '' }}
                        onchange="document.getElementById('edit_bloc_assurance').style.display = this.checked ? 'block' : 'none'">
                    <span class="text-sm font-medium text-gray-700">Patiente assurée</span>
                </label>
            </div>

            <div id="edit_bloc_assurance" style="{{ $patiente->est_assuree ? '' : 'display:none' }}">
                @php $assuranceActuelle = $patiente->assurances->first(); @endphp
                <div class="grid grid-cols-2 gap-4 bg-blue-50 border border-blue-200 rounded p-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom de l'assurance</label>
                        <input type="text" name="assurance_nom"
                            value="{{ old('assurance_nom', $assuranceActuelle->notes ?? '') }}"
                            class="mt-1 border rounded w-full px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Taux de couverture (%)</label>
                        <input type="number" name="assurance_taux" min="0" max="100"
                            value="{{ old('assurance_taux', $assuranceActuelle->taux_couverture ?? '') }}"
                            class="mt-1 border rounded w-full px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Numéro de police</label>
                        <input type="text" name="assurance_numero_police"
                            value="{{ old('assurance_numero_police', $assuranceActuelle->numero_police ?? '') }}"
                            class="mt-1 border rounded w-full px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date d'expiration</label>
                        <input type="date" name="assurance_date_fin"
                            value="{{ old('assurance_date_fin', $assuranceActuelle?->date_fin?->format('Y-m-d')) }}"
                            class="mt-1 border rounded w-full px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Enregistrer l'assurance
                </button>
            </div>
        </form>
    </div>
</div>

        </div>
    </div>
</x-app-layout> 
