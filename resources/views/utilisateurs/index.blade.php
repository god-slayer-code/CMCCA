<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Gestion des comptes utilisateurs
            </h2>
            <a href="{{ route('utilisateurs.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Nouveau compte
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

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
                            <th class="p-3 text-left">Nom</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Rôle</th>
                            <th class="p-3 text-left">Statut</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($utilisateurs as $u)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 font-semibold">{{ $u->name }}</td>
                            <td class="p-3">{{ $u->email }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">
                                    {{ $u->role->nom ?? 'Aucun rôle' }}
                                </span>
                            </td>
                            <td class="p-3">
                                @if($u->actif)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Actif</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Désactivé</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="flex gap-2 items-center">
                                    <a href="{{ route('utilisateurs.edit', $u) }}"
                                       class="text-blue-600 hover:underline text-xs">
                                        Modifier
                                    </a>
                                    @if($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('utilisateurs.toggle-actif', $u) }}">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('{{ $u->actif ? 'Désactiver' : 'Réactiver' }} ce compte ?')"
                                                class="text-xs {{ $u->actif ? 'text-red-600' : 'text-green-600' }} hover:underline">
                                                {{ $u->actif ? 'Désactiver' : 'Réactiver' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">(vous)</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                Aucun utilisateur.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout> 
