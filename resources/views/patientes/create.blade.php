<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Nouvelle Patiente
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                @if(session('warning'))
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 p-4 rounded mb-4">
                        <p class="font-bold">⚠ {{ session('warning') }}</p>
                        <p class="mt-2 font-semibold">Fiches similaires trouvées :</p>
                        <ul class="list-disc ml-4 mt-1">
                            @foreach(session('doublons_potentiels') as $d)
                                <li>
                                    {{ $d->nom_complet }} — {{ $d->telephone }}
                                    <a href="{{ route('patientes.show', $d) }}"
                                       target="_blank"
                                       class="text-blue-600 underline ml-2">
                                       Voir la fiche (nouvel onglet)
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <p class="mt-3 text-sm">
                            Si ce n'est pas la même personne, cliquez sur
                            <strong>"Créer quand même"</strong> ci-dessous.
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('patientes.store') }}">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom *</label>
                            <input type="text" name="nom" value="{{ old('nom') }}"
                                class="mt-1 border rounded w-full px-3 py-2" required>
                            @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prénom *</label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}"
                                class="mt-1 border rounded w-full px-3 py-2" required>
                            @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="text" name="telephone" value="{{ old('telephone') }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                       <div class="col-span-2">
    <label class="block text-sm font-medium text-gray-700">Date de naissance ou âge</label>
    <div class="grid grid-cols-2 gap-3 mt-1">
        <div>
            <input type="date" name="date_naissance" value="{{ old('date_naissance') }}"
                class="border rounded w-full px-3 py-2" placeholder="Date de naissance">
            <p class="text-xs text-gray-400 mt-1">Si connue</p>
        </div>
        <div>
            <input type="number" name="age_approximatif" value="{{ old('age_approximatif') }}"
                min="0" max="120" placeholder="Ex: 35 ans"
                class="border rounded w-full px-3 py-2">
            <p class="text-xs text-gray-400 mt-1">Si date inconnue, indiquer l'âge estimé</p>
        </div>
    </div>
</div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Groupe sanguin</label>
                            <select name="groupe_sanguin" class="mt-1 border rounded w-full px-3 py-2">
                                <option value="">-- Sélectionner --</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $gs)
                                    <option value="{{ $gs }}" {{ old('groupe_sanguin') === $gs ? 'selected' : '' }}>
                                        {{ $gs }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Adresse</label>
                            <input type="text" name="adresse" value="{{ old('adresse') }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                        {{-- Motif de visite avec prix automatique --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Motif de la visite</label>
                            <select name="motif_visite_id" id="motif_visite_id"
                                class="mt-1 border rounded w-full px-3 py-2"
                                onchange="afficherPrix(this)">
                                <option value="">-- Sélectionner un motif --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}"
                                        data-prix="{{ $service->prix_standard }}"
                                        {{ old('motif_visite_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="prix_affiche" class="mt-2 hidden">
                                <span class="text-green-700 font-semibold text-sm">
                                    💰 Prix : <span id="prix_valeur"></span> GNF
                                </span>
                                <input type="hidden" name="motif_visite_prix" id="motif_visite_prix">
                            </div>
                        </div>

                        {{-- Assurance --}}
                        <div class="col-span-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="est_assuree" value="1" id="est_assuree"
                                    {{ old('est_assuree') ? 'checked' : '' }}
                                    onchange="toggleAssurance(this)">
                                <span class="text-sm font-medium text-gray-700">Patiente assurée</span>
                            </label>
                        </div>

                        {{-- Détails assurance --}}
                        <div class="col-span-2" id="bloc_assurance" style="display:none">
                            <div class="bg-blue-50 border border-blue-200 rounded p-4">
                                <p class="font-semibold text-blue-800 mb-3">Détails de l'assurance</p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nom de l'assurance</label>
                                        <input type="text" name="assurance_nom" value="{{ old('assurance_nom') }}"
                                            placeholder="Ex: SONU, Activa..."
                                            class="mt-1 border rounded w-full px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Taux de couverture (%)</label>
                                        <input type="number" name="assurance_taux" id="assurance_taux"
                                            value="{{ old('assurance_taux') }}"
                                            min="0" max="100" placeholder="Ex: 80"
                                            class="mt-1 border rounded w-full px-3 py-2"
                                            onchange="calculerPartPatiente()">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Numéro de police</label>
                                        <input type="text" name="assurance_numero_police"
                                            value="{{ old('assurance_numero_police') }}"
                                            placeholder="Numéro de contrat"
                                            class="mt-1 border rounded w-full px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date d'expiration</label>
                                        <input type="date" name="assurance_date_fin"
                                            value="{{ old('assurance_date_fin') }}"
                                            class="mt-1 border rounded w-full px-3 py-2">
                                    </div>
                                </div>

                                {{-- Calcul automatique --}}
                                <div id="calcul_assurance" class="mt-3 hidden bg-white rounded p-3 border">
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div>
                                            <p class="text-gray-500">Prix total</p>
                                            <p class="font-bold" id="calc_prix_total">-- GNF</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Prise en charge assurance</p>
                                            <p class="font-bold text-green-600" id="calc_prise_charge">-- GNF</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Patiente paie</p>
                                            <p class="font-bold text-red-600" id="calc_part_patiente">-- GNF</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="est_mineure" value="1"
                                    {{ old('est_mineure') ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">Patiente mineure</span>
                            </label>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Responsable légal (obligatoire si mineure)
                            </label>
                            <input type="text" name="responsable_legal_nom"
                                value="{{ old('responsable_legal_nom') }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                            @error('responsable_legal_nom')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-6 flex gap-3">
                        @if(session('doublons_potentiels'))
                            <button type="submit" name="confirmer_malgre_doublon" value="1"
                                class="bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600">
                                Créer quand même (ce n'est pas un doublon)
                            </button>
                        @else
                            <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                Créer la fiche
                            </button>
                        @endif
                        <a href="{{ route('patientes.index') }}"
                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        function afficherPrix(select) {
            const option = select.options[select.selectedIndex];
            const prix = option.getAttribute('data-prix');
            const div = document.getElementById('prix_affiche');
            const valeur = document.getElementById('prix_valeur');
            const input = document.getElementById('motif_visite_prix');

            if (prix) {
                const prixFormate = new Intl.NumberFormat('fr-FR').format(prix);
                valeur.textContent = prixFormate;
                input.value = prix;
                div.classList.remove('hidden');
                calculerPartPatiente();
            } else {
                div.classList.add('hidden');
                input.value = '';
            }
        }

        function toggleAssurance(checkbox) {
            const bloc = document.getElementById('bloc_assurance');
            if (checkbox.checked) {
                bloc.style.display = 'block';
            } else {
                bloc.style.display = 'none';
            }
            calculerPartPatiente();
        }

        function calculerPartPatiente() {
            const prixInput = document.getElementById('motif_visite_prix');
            const tauxInput = document.getElementById('assurance_taux');
            const calcDiv = document.getElementById('calcul_assurance');
            const assureCheck = document.getElementById('est_assuree');

            const prix = parseFloat(prixInput ? prixInput.value : 0);
            const taux = parseFloat(tauxInput ? tauxInput.value : 0);

            if (prix > 0 && taux > 0 && assureCheck.checked) {
                const priseEnCharge = prix * (taux / 100);
                const partPatiente = prix - priseEnCharge;

                document.getElementById('calc_prix_total').textContent =
                    new Intl.NumberFormat('fr-FR').format(prix) + ' GNF';
                document.getElementById('calc_prise_charge').textContent =
                    new Intl.NumberFormat('fr-FR').format(priseEnCharge) + ' GNF';
                document.getElementById('calc_part_patiente').textContent =
                    new Intl.NumberFormat('fr-FR').format(partPatiente) + ' GNF';

                calcDiv.classList.remove('hidden');
            } else {
                calcDiv.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>