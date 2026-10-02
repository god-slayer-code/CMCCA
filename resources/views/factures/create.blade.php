<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Nouvelle Facture
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('factures.store') }}">
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
                            <label class="block text-sm font-medium text-gray-700">Service / Acte *</label>
                            <select name="service_id" id="service_id" class="mt-1 border rounded w-full px-3 py-2"
                                onchange="afficherPrixFacture(this)" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}"
                                        data-prix="{{ $service->prix_standard }}"
                                        {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->nom }} — {{ number_format($service->prix_standard, 0, ',', ' ') }} GNF
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-2" id="prix_apercu" class="hidden">
                            <div class="bg-green-50 border border-green-200 rounded p-3">
                                <span class="text-green-700 font-semibold">
                                    Montant : <span id="prix_valeur_facture">--</span> GNF
                                </span>
                            </div>
                        </div>

                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Enregistrer la facture
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        function afficherPrixFacture(select) {
            const option = select.options[select.selectedIndex];
            const prix = option.getAttribute('data-prix');
            const div = document.getElementById('prix_apercu');
            const valeur = document.getElementById('prix_valeur_facture');

            if (prix) {
                valeur.textContent = new Intl.NumberFormat('fr-FR').format(prix);
                div.style.display = 'block';
            } else {
                div.style.display = 'none';
            }
        }
    </script>
    <script>
let rechercheTimeoutFacture;
const inputRechercheFacture = document.getElementById('patiente_recherche');
const suggestionsDivFacture = document.getElementById('patiente_suggestions');
const patienteIdInputFacture = document.getElementById('patiente_id');
const patienteSelectionneeFactureP = document.getElementById('patiente_selectionnee');

inputRechercheFacture.addEventListener('input', function () {
    const terme = this.value.trim();
    patienteIdInputFacture.value = '';
    patienteSelectionneeFactureP.classList.add('hidden');

    clearTimeout(rechercheTimeoutFacture);

    if (terme.length < 2) {
        suggestionsDivFacture.classList.add('hidden');
        suggestionsDivFacture.innerHTML = '';
        return;
    }

    rechercheTimeoutFacture = setTimeout(() => {
        fetch(`{{ route('patientes.recherche-ajax') }}?q=${encodeURIComponent(terme)}`)
            .then(res => res.json())
            .then(data => {
                suggestionsDivFacture.innerHTML = '';

                if (data.length === 0) {
                    suggestionsDivFacture.innerHTML = '<div class="p-3 text-gray-500 text-sm">Aucune patiente trouvée.</div>';
                    suggestionsDivFacture.classList.remove('hidden');
                    return;
                }

                data.forEach(patiente => {
                    const item = document.createElement('div');
                    item.className = 'p-3 hover:bg-blue-50 cursor-pointer text-sm border-b last:border-b-0';
                    item.textContent = patiente.label;
                    item.addEventListener('click', () => {
                        patienteIdInputFacture.value = patiente.id;
                        inputRechercheFacture.value = patiente.label;
                        patienteSelectionneeFactureP.textContent = '✓ Patiente sélectionnée';
                        patienteSelectionneeFactureP.classList.remove('hidden');
                        suggestionsDivFacture.classList.add('hidden');
                    });
                    suggestionsDivFacture.appendChild(item);
                });

                suggestionsDivFacture.classList.remove('hidden');
            });
    }, 300);
});

document.addEventListener('click', function (e) {
    if (!inputRechercheFacture.contains(e.target) && !suggestionsDivFacture.contains(e.target)) {
        suggestionsDivFacture.classList.add('hidden');
    }
});
</script>
</x-app-layout> 
