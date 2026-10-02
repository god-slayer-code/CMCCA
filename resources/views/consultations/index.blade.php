<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Espace Médecin — Liste des Patientes
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Dossier</th>
                            <th class="p-3 text-left">Nom complet</th>
                            <th class="p-3 text-left">Téléphone</th>
                            <th class="p-3 text-left">Motif visite</th>
                            <th class="p-3 text-left">Assurance</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patientes as $patiente)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ $patiente->numero_dossier }}</td>
                            <td class="p-3 font-semibold">{{ $patiente->nom_complet }}</td>
                            <td class="p-3">{{ $patiente->telephone ?? '-' }}</td>
                            <td class="p-3">{{ $patiente->motifVisite->nom ?? '-' }}</td>
                            <td class="p-3">
                                @if($patiente->est_assuree)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">✅ Assurée</span>
                                @else
                                    <span class="text-gray-400 text-xs">Non assurée</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <a href="{{ route('consultations.show', $patiente) }}"
                                   class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                    Voir / Consulter
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                Aucune patiente trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout> 
