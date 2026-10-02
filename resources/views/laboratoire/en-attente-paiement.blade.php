<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Comptabilité — Analyses en attente de paiement
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Patiente</th>
                            <th class="p-3 text-left">Dossier</th>
                            <th class="p-3 text-left">Type d'analyse</th>
                            <th class="p-3 text-left">Demandé par</th>
                            <th class="p-3 text-left">Date</th>
                            <th class="p-3 text-left">Montant</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandes as $demande)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 font-semibold">{{ $demande->patiente->nom_complet }}</td>
                            <td class="p-3">{{ $demande->patiente->numero_dossier }}</td>
                            <td class="p-3 text-gray-400 italic">{{ $demande->type_analyse }}</td>
                            <td class="p-3">{{ $demande->demandePar->name ?? '-' }}</td>
                            <td class="p-3">{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3">
                                {{-- Prix affiché mais non modifiable --}}
                                @if($demande->consultation && $demande->consultation->service)
                                    <span class="font-semibold">
                                        {{ number_format($demande->consultation->service->prix_standard, 0, ',', ' ') }} GNF
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-3">
    <form method="POST" action="{{ route('laboratoire.valider-paiement', $demande) }}">
        @csrf
        <div class="flex gap-2 items-center">
            <input type="number" name="montant_paye"
                placeholder="Montant"
                value="{{ $demande->consultation->service->prix_standard ?? '' }}"
                class="border rounded px-2 py-1 text-sm w-32" required>
            <button type="submit"
                onclick="return confirm('Confirmer la validation du paiement ?')"
                class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                ✅ Valider paiement
            </button>
        </div>
    </form>
</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">
                                Aucune demande en attente de paiement.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>