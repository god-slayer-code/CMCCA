<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Historique complet des factures
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">N° Facture</th>
                            <th class="p-3 text-left">Patiente</th>
                            <th class="p-3 text-left">Service</th>
                            <th class="p-3 text-left">Montant</th>
                            <th class="p-3 text-left">Statut</th>
                            <th class="p-3 text-left">Date</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($factures as $facture)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ $facture->numero_facture }}</td>
                            <td class="p-3 font-semibold">{{ $facture->patiente->nom_complet }}</td>
                            <td class="p-3 text-gray-500">{{ $facture->service->nom ?? '-' }}</td>
                            <td class="p-3">{{ number_format($facture->montant_final, 0, ',', ' ') }} GNF</td>
                            <td class="p-3">
                                @php
                                    $couleurs = [
                                        'en_attente_validation' => 'bg-orange-100 text-orange-800',
                                        'partiellement_payee'   => 'bg-yellow-100 text-yellow-800',
                                        'payee'                 => 'bg-green-100 text-green-800',
                                        'annulee'               => 'bg-red-100 text-red-800',
                                    ];
                                    $labels = [
                                        'en_attente_validation' => 'En attente',
                                        'partiellement_payee'   => 'Partielle',
                                        'payee'                 => 'Payée',
                                        'annulee'               => 'Annulée',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $couleurs[$facture->statut] ?? '' }}">
                                    {{ $labels[$facture->statut] ?? $facture->statut }}
                                </span>
                            </td>
                            <td class="p-3">{{ $facture->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3">
                                <a href="{{ route('factures.recu', $facture) }}" target="_blank"
                                   class="text-blue-600 hover:underline text-xs">
                                    🖨 Reçu
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">
                                Aucune facture enregistrée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $factures->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
