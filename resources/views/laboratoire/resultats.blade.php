 <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Laboratoire — Tous les résultats
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Patiente</th>
                            <th class="p-3 text-left">Dossier</th>
                            <th class="p-3 text-left">Type d'analyse</th>
                            <th class="p-3 text-left">Résultat</th>
                            <th class="p-3 text-left">Hors norme</th>
                            <th class="p-3 text-left">Saisi par</th>
                            <th class="p-3 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resultats as $resultat)
                        <tr class="border-t hover:bg-gray-50 {{ $resultat->valeur_hors_norme ? 'bg-red-50' : '' }}">
                            <td class="p-3 font-semibold">{{ $resultat->demandeAnalyse->patiente->nom_complet }}</td>
                            <td class="p-3">{{ $resultat->demandeAnalyse->patiente->numero_dossier }}</td>
                            <td class="p-3">{{ $resultat->demandeAnalyse->type_analyse }}</td>
                            <td class="p-3">{{ $resultat->resultat }}</td>
                            <td class="p-3">
                                @if($resultat->valeur_hors_norme)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">
                                        ⚠ Hors norme
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                                        Normal
                                    </span>
                                @endif
                            </td>
                            <td class="p-3">{{ $resultat->saisiPar->name ?? '-' }}</td>
                            <td class="p-3">{{ $resultat->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">
                                Aucun résultat enregistré.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>