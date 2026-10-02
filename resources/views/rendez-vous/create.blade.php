<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Nouveau Rendez-vous
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                @if(session('conflit'))
                    <div class="bg-red-100 border border-red-400 text-red-800 p-4 rounded mb-4">
                        <p class="font-bold">⚠ Conflit détecté</p>
                        <p>{{ session('conflit') }}</p>
                        <p class="mt-2 text-sm">
                            Si c'est volontaire (urgence, double consultation), coche la case ci-dessous et valide à nouveau.
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('rendez-vous.store') }}">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">

                        <div class="col-span-2 relative">
    <label class="block text-sm font-medium text-gray-700">Patiente *</label>
    <input type="text" id="patiente_recherche" autocomplete="off"
        placeholder="Tapez le nom, prénom, téléphone ou n° de dossier..."
        class="mt-1 border rounded w-full px-3 py-2">
    <input type="hidden" name="patiente_id" id="patiente_id" value="{{ old('patiente_id') }}">
    <div id="patiente_suggestions"
         class="hidden absolute z-10 bg-white border rounded w-full mt-1 max-h-60 overflow-y-auto shadow-lg">
    </div>
    <p id="patiente_selectionnee" class="text-sm text-green-700 mt-1 hidden"></p>
    @error('patiente_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

                        <div class="col-span-2">
    <label class="block text-sm font-medium text-gray-700">Médecin *</label>
    <select name="medecin_id" class="mt-1 border rounded w-full px-3 py-2" required>
        <option value="">-- Sélectionner --</option>
        @foreach($medecins as $medecin)
            <option value="{{ $medecin->id }}"
                {{ old('medecin_id') == $medecin->id ? 'selected' : '' }}>
                {{ $medecin->nom }}{{ $medecin->specialite ? ' — ' . $medecin->specialite : '' }}
            </option>
        @endforeach
    </select>
    @error('medecin_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div class="col-span-2">
    <label class="block text-sm font-medium text-gray-700">Motif du rendez-vous</label>
    <select name="motif_id" class="mt-1 border rounded w-full px-3 py-2">
        <option value="">-- Sélectionner un motif --</option>
        @foreach($services as $service)
            <option value="{{ $service->id }}"
                {{ old('motif_id') == $service->id ? 'selected' : '' }}>
                {{ $service->nom }}
            </option>
        @endforeach
    </select>
    @error('motif_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date et heure *</label>
                            <input type="datetime-local" name="date_heure_prevue"
                                value="{{ old('date_heure_prevue') }}"
                                class="mt-1 border rounded w-full px-3 py-2" required>
                            @error('date_heure_prevue') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <input type="hidden" name="duree_minutes" value="30">

                        @if(session('conflit'))
                        <div class="col-span-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="forcer_malgre_conflit" value="1">
                                <span class="text-sm font-medium text-gray-700">
                                    Créer quand même malgré le conflit
                                </span>
                            </label>
                        </div>
                        @endif

                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Créer le rendez-vous
                        </button>
                        <a href="{{ route('rendez-vous.index') }}"
                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script>
let rechercheTimeout;
const inputRecherche = document.getElementById('patiente_recherche');
const suggestionsDiv = document.getElementById('patiente_suggestions');
const patienteIdInput = document.getElementById('patiente_id');
const patienteSelectionneeP = document.getElementById('patiente_selectionnee');

inputRecherche.addEventListener('input', function () {
    const terme = this.value.trim();
    patienteIdInput.value = ''; // reset tant qu'on n'a pas re-choisi
    patienteSelectionneeP.classList.add('hidden');

    clearTimeout(rechercheTimeout);

    if (terme.length < 2) {
        suggestionsDiv.classList.add('hidden');
        suggestionsDiv.innerHTML = '';
        return;
    }

    rechercheTimeout = setTimeout(() => {
        fetch(`{{ route('patientes.recherche-ajax') }}?q=${encodeURIComponent(terme)}`)
            .then(res => res.json())
            .then(data => {
                suggestionsDiv.innerHTML = '';

                if (data.length === 0) {
                    suggestionsDiv.innerHTML = '<div class="p-3 text-gray-500 text-sm">Aucune patiente trouvée.</div>';
                    suggestionsDiv.classList.remove('hidden');
                    return;
                }

                data.forEach(patiente => {
                    const item = document.createElement('div');
                    item.className = 'p-3 hover:bg-blue-50 cursor-pointer text-sm border-b last:border-b-0';
                    item.textContent = patiente.label;
                    item.addEventListener('click', () => {
                        patienteIdInput.value = patiente.id;
                        inputRecherche.value = patiente.label;
                        patienteSelectionneeP.textContent = '✓ Patiente sélectionnée';
                        patienteSelectionneeP.classList.remove('hidden');
                        suggestionsDiv.classList.add('hidden');
                    });
                    suggestionsDiv.appendChild(item);
                });

                suggestionsDiv.classList.remove('hidden');
            });
    }, 300);
});

// Fermer la liste si on clique ailleurs
document.addEventListener('click', function (e) {
    if (!inputRecherche.contains(e.target) && !suggestionsDiv.contains(e.target)) {
        suggestionsDiv.classList.add('hidden');
    }
});
</script>
</x-app-layout> 
