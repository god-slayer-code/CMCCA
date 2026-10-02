 
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Liste des Patientes
            </h2>
            <a href="{{ route('patientes.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Nouvelle Patiente
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Barre de recherche --}}
            <form method="GET" action="{{ route('patientes.index') }}" class="mb-4">
                <input type="text" name="recherche"
                    value="{{ request('recherche') }}"
                    placeholder="Rechercher par nom, prénom, téléphone..."
                    class="border rounded px-4 py-2 w-full">
            </form>

            {{-- Tableau --}}
            <div class="bg-white shadow rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Dossier</th>
                            <th class="p-3 text-left">Nom complet</th>
                            <th class="p-3 text-left">Téléphone</th>
                            <th class="p-3 text-left">Statut</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patientes as $patiente)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ $patiente->numero_dossier }}</td>
                            <td class="p-3">{{ $patiente->nom_complet }}</td>
                            <td class="p-3">{{ $patiente->telephone ?? '-' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $patiente->statut === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100' }}">
                                    {{ $patiente->statut }}
                                </span>
                            </td>
                            <td class="p-3">
                                <a href="{{ route('patientes.show', $patiente) }}"
                                   class="text-blue-600 hover:underline">Voir</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                Aucune patiente trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $patientes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>