<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Laboratoire — Demandes à traiter
        </h2>
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
                            <th class="p-3 text-left">Patiente</th>
                            <th class="p-3 text-left">Dossier</th>
                            <th class="p-3 text-left">Type d'analyse</th>
                            <th class="p-3 text-left">Date demande</th>
                            <th class="p-3 text-left">Paiement</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandes as $demande)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 font-semibold">{{ $demande->patiente->nom_complet }}</td>
                            <td class="p-3">{{ $demande->patiente->numero_dossier }}</td>
                            <td class="p-3">{{ $demande->type_analyse }}</td>
                            <td class="p-3">{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                                    ✅ Payé
                                </span>
                            </td>
                            <td class="p-3">
                               <a href="{{ route('laboratoire.voir-parametres', $demande) }}"
   class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
    📋 Saisir résultats
</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                Aucune demande en attente.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>