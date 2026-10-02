<div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Nom *</label>
        <input type="text" name="nom" wire:model.live.debounce.300ms="nom"
               class="mt-1 border rounded w-full px-3 py-2" required>
    </div>

    <div class="mt-4">
        <label class="block text-sm font-medium text-gray-700">Prénom *</label>
        <input type="text" name="prenom" wire:model.live.debounce.300ms="prenom"
               class="mt-1 border rounded w-full px-3 py-2" required>
    </div>

    @if(count($doublonsPotentiels) > 0)
        <div class="bg-yellow-50 border border-yellow-300 rounded-md p-4 mt-4">
            <p class="font-semibold text-yellow-800 mb-2">
                ⚠️ Doublons potentiels détectés :
            </p>
            <ul class="list-disc list-inside">
                @foreach($doublonsPotentiels as $doublon)
                    <li>
                        {{ $doublon->nom }} {{ $doublon->prenom }}
                        (Dossier : {{ $doublon->numero_dossier }})
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>