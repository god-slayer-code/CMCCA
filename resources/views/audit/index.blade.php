<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Journal d'Audit — Super Admin
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filtres --}}
            <form method="GET" action="{{ route('audit.index') }}" class="bg-white shadow rounded p-4 mb-4">
                <div class="grid grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Action</label>
                        <select name="action" class="border rounded w-full px-2 py-1 text-sm" onchange="this.form.submit()">
                            <option value="">-- Toutes --</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Table</label>
                        <select name="table" class="border rounded w-full px-2 py-1 text-sm" onchange="this.form.submit()">
                            <option value="">-- Toutes --</option>
                            @foreach($tables as $table)
                                <option value="{{ $table }}" {{ request('table') === $table ? 'selected' : '' }}>
                                    {{ $table }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Utilisateur</label>
                        <select name="utilisateur" class="border rounded w-full px-2 py-1 text-sm" onchange="this.form.submit()">
                            <option value="">-- Tous --</option>
                            @foreach($utilisateurs as $u)
                                <option value="{{ $u->id }}" {{ request('utilisateur') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('audit.index') }}" class="text-blue-600 text-sm hover:underline">
                            Réinitialiser les filtres
                        </a>
                    </div>
                </div>
            </form>

            {{-- Tableau --}}
            <div class="bg-white shadow rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Date</th>
                            <th class="p-3 text-left">Utilisateur</th>
                            <th class="p-3 text-left">Action</th>
                            <th class="p-3 text-left">Table</th>
                            <th class="p-3 text-left">Enregistrement</th>
                            <th class="p-3 text-left">Détails</th>
                            <th class="p-3 text-left">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($journal as $entree)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 text-gray-500">{{ $entree->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="p-3 font-semibold">{{ $entree->user->name ?? 'Système' }}</td>
                            <td class="p-3">
                                @php
                                    $couleurs = [
                                        'creation'     => 'bg-green-100 text-green-800',
                                        'modification' => 'bg-yellow-100 text-yellow-800',
                                        'suppression'  => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $couleurs[$entree->action] ?? 'bg-gray-100' }}">
                                    {{ ucfirst($entree->action) }}
                                </span>
                            </td>
                            <td class="p-3 text-gray-600">{{ $entree->table_concernee }}</td>
                            <td class="p-3 text-gray-600">#{{ $entree->enregistrement_id }}</td>
                            <td class="p-3">
                                @if($entree->details_json && count($entree->details_json) > 0)
                                    <details class="text-xs">
                                        <summary class="cursor-pointer text-blue-600">Voir</summary>
                                        <pre class="bg-gray-50 p-2 rounded mt-1 overflow-x-auto">{{ json_encode($entree->details_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </details>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-3 text-gray-400 text-xs">{{ $entree->ip_adresse ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">
                                Aucune entrée dans le journal.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $journal->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
