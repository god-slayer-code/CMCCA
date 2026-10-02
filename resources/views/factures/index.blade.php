<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Comptabilité — Factures en attente
            </h2>
            <a href="{{ route('factures.historique') }}"
               class="text-blue-600 hover:underline text-sm">
                Voir l'historique complet →
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">N° Facture</th>
                            <th class="p-3 text-left">Patiente</th>
                            <th class="p-3 text-left">Service</th>
                            <th class="p-3 text-left">Montant</th>
                            <th class="p-3 text-left">Statut</th>
                            <th class="p-3 text-left">Encaisser</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($factures as $facture)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ $facture->numero_facture }}</td>
                            <td class="p-3 font-semibold">{{ $facture->patiente->nom_complet }}</td>
                            <td class="p-3 text-gray-500">{{ $facture->service->nom ?? '-' }}</td>
                            <td class="p-3">
                                <span class="font-semibold" style="background:#f3f4f6; padding:2px 8px; border-radius:4px;">
                                    {{ number_format($facture->montant_final, 0, ',', ' ') }} GNF
                                </span>
                                @if($facture->montant_ajuste)
                                    <p class="text-xs text-orange-600 mt-1">
                                        Remise appliquée (prix initial : {{ number_format($facture->montant_service, 0, ',', ' ') }} GNF)
                                    </p>
                                @endif
                                @if(auth()->user()->aRole('super_admin', 'admin') && !in_array($facture->statut, ['payee', 'annulee']))
                                    <button type="button"
                                        onclick="toggleRemise({{ $facture->id }})"
                                        class="text-xs text-blue-600 hover:underline mt-1 block">
                                        ✏️ Appliquer une remise
                                    </button>
                                    <div id="remise-{{ $facture->id }}" class="hidden mt-2 bg-gray-50 border rounded p-2" style="min-width:220px;">
                                        <form method="POST" action="{{ route('factures.appliquer-remise', $facture) }}">
                                            @csrf

                                            <div class="flex gap-2 mb-1">
                                                <select name="type_remise" id="type-remise-{{ $facture->id }}"
                                                    onchange="majApercu({{ $facture->id }}, {{ $facture->montant_service }})"
                                                    class="border rounded px-2 py-1 text-xs">
                                                    <option value="pourcentage">%</option>
                                                    <option value="montant">GNF</option>
                                                </select>
                                                <input type="number" name="valeur_remise" id="valeur-remise-{{ $facture->id }}"
                                                    placeholder="Ex: 10"
                                                    oninput="majApercu({{ $facture->id }}, {{ $facture->montant_service }})"
                                                    min="0.01" step="0.01"
                                                    class="border rounded px-2 py-1 text-xs w-24" required>
                                            </div>

                                            <p id="apercu-{{ $facture->id }}" class="text-xs text-gray-500 mb-1">
                                                Prix initial : {{ number_format($facture->montant_service, 0, ',', ' ') }} GNF
                                            </p>

                                            <input type="text" name="motif_ajustement" placeholder="Motif (optionnel)"
                                                class="border rounded px-2 py-1 text-xs w-full mb-1">

                                            <button type="submit"
                                                onclick="return confirm('Confirmer la remise ?')"
                                                class="bg-orange-500 text-white px-2 py-1 rounded text-xs hover:bg-orange-600">
                                                Confirmer la remise
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $facture->statut === 'partiellement_payee' ? 'bg-yellow-100 text-yellow-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ $facture->statut === 'partiellement_payee' ? 'Partiellement payée' : 'En attente' }}
                                </span>
                                @if($facture->solde_du < $facture->montant_final)
                                    <p class="text-xs text-gray-500 mt-1">
                                        Reste : {{ number_format($facture->solde_du, 0, ',', ' ') }} GNF
                                    </p>
                                @endif
                            </td>
                            <td class="p-3">
    <form method="POST" action="{{ route('factures.valider-paiement', $facture) }}" id="form-{{ $facture->id }}">
        @csrf
        <div class="flex flex-col gap-2" style="min-width:200px;">

            <div class="flex gap-2 items-center">
                <input type="number" name="montant" id="montant-{{ $facture->id }}"
                    placeholder="Montant reçu"
                    oninput="verifierMontant({{ $facture->id }}, {{ $facture->solde_du }})"
                    class="border rounded px-2 py-1 text-sm w-32" required>
                <select name="mode_paiement" class="border rounded px-2 py-1 text-sm">
                    <option value="especes">Espèces</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="carte">Carte</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <p id="message-montant-{{ $facture->id }}" class="text-xs"></p>

            <button type="submit"
                onclick="return confirm('Confirmer l\'encaissement ?')"
                class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 w-fit">
                ✅ Valider le paiement
            </button>
        </div>
    </form>
</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                Aucune facture en attente.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function toggleRemise(factureId) {
        document.getElementById('remise-' + factureId).classList.toggle('hidden');
    }

    function majApercu(factureId, prixInitial) {
        const type = document.getElementById('type-remise-' + factureId).value;
        const valeur = parseFloat(document.getElementById('valeur-remise-' + factureId).value) || 0;
        const apercu = document.getElementById('apercu-' + factureId);

        let nouveauMontant = prixInitial;

        if (type === 'pourcentage') {
            nouveauMontant = prixInitial - (prixInitial * valeur / 100);
        } else {
            nouveauMontant = prixInitial - valeur;
        }

        if (nouveauMontant < 0) nouveauMontant = 0;

        apercu.innerHTML = 'Prix initial : ' + new Intl.NumberFormat('fr-FR').format(prixInitial) + ' GNF' +
            '<br><span class="font-semibold text-orange-600">Nouveau montant : ' +
            new Intl.NumberFormat('fr-FR').format(nouveauMontant) + ' GNF</span>';
    }

    function verifierMontant(factureId, soldeDu) {
        const input = document.getElementById('montant-' + factureId);
        const message = document.getElementById('message-montant-' + factureId);
        const montant = parseFloat(input.value) || 0;

        if (montant === 0) {
            message.textContent = '';
            return;
        }

        if (montant < soldeDu) {
            message.innerHTML = '⚠ Montant insuffisant — il manque ' +
                new Intl.NumberFormat('fr-FR').format(soldeDu - montant) + ' GNF';
            message.className = 'text-xs text-orange-600';
        } else if (montant > soldeDu) {
            message.innerHTML = '⚠ Montant en excès de ' +
                new Intl.NumberFormat('fr-FR').format(montant - soldeDu) + ' GNF';
            message.className = 'text-xs text-red-600';
        } else {
            message.innerHTML = '✓ Montant exact';
            message.className = 'text-xs text-green-600';
        }
    }
</script>

</x-app-layout>