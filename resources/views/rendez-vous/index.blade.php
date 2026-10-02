<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Rendez-vous — {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('rendez-vous.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Nouveau RDV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Sélecteur de date --}}
            <form method="GET" action="{{ route('rendez-vous.index') }}" class="mb-4 flex gap-2 items-center">
                <input type="date" name="date" value="{{ $date }}"
                    class="border rounded px-3 py-2"
                    onchange="this.form.submit()">
                <a href="{{ route('rendez-vous.index') }}"
                   class="text-blue-600 text-sm hover:underline">Aujourd'hui</a>
            </form>

            <div class="bg-white shadow rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Heure</th>
                            <th class="p-3 text-left">Patiente</th>
                            <th class="p-3 text-left">Médecin</th>
                            <th class="p-3 text-left">Motif</th>
                            <th class="p-3 text-left">Durée</th>
                            <th class="p-3 text-left">Statut</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rendezVous as $rdv)
                        <tr class="border-t hover:bg-gray-50 {{ $rdv->statut === 'urgence' ? 'bg-red-50' : '' }}">
                            <td class="p-3 font-semibold">
                                {{ $rdv->date_heure_prevue->format('H:i') }}
                            </td>
                            <td class="p-3">
                                <a href="{{ route('patientes.show', $rdv->patiente) }}"
                                   class="text-blue-600 hover:underline">
                                    {{ $rdv->patiente->nom_complet }}
                                </a>
                            </td>
                            <td class="p-3">{{ $rdv->medecin->nom }}</td>
                            <td class="p-3 text-gray-600">{{ $rdv->motif->nom ?? '-' }}</td>
                            <td class="p-3">{{ $rdv->duree_minutes }} min</td>
                            <td class="p-3">
                                @php
                                    $couleurs = [
                                        'planifie' => 'bg-blue-100 text-blue-800',
                                        'confirme' => 'bg-green-100 text-green-800',
                                        'en_cours' => 'bg-yellow-100 text-yellow-800',
                                        'termine'  => 'bg-gray-100 text-gray-800',
                                        'annule'   => 'bg-red-100 text-red-800',
                                        'absent'   => 'bg-orange-100 text-orange-800',
                                        'urgence'  => 'bg-red-500 text-white',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $couleurs[$rdv->statut] ?? 'bg-gray-100' }}">
                                    {{ $rdv->statut === 'urgence' ? '🚨 URGENCE' : ucfirst($rdv->statut) }}
                                </span>
                            </td>
                            <td class="p-3">
                                @if(!in_array($rdv->statut, ['annule', 'termine']))
                                <form method="POST" action="{{ route('rendez-vous.annuler', $rdv) }}"
                                      onsubmit="return confirm('Annuler ce rendez-vous ?') && (this.motif.value = prompt('Motif d\'annulation :') || '')">
                                    @csrf
                                    <input type="hidden" name="motif">
                                    <button type="submit" class="text-red-600 hover:underline text-xs">
                                        Annuler
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                Aucun rendez-vous ce jour.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout> 
