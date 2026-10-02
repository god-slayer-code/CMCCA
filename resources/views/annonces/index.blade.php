<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif text-2xl text-emerald-900 tracking-wide">
                Annonces
            </h2>
            <a href="{{ route('annonces.create') }}"
               class="bg-emerald-700 text-white px-5 py-2 rounded-lg font-medium hover:bg-emerald-800 transition shadow-sm">
                + Nouvelle annonce
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-800 border border-emerald-200 p-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @forelse($annonces as $annonce)
            <div class="bg-white shadow-md rounded-xl border border-emerald-100 overflow-hidden">
                <div class="flex">
                    @if($annonce->image_url)
                        <img src="{{ $annonce->image_url }}" alt="" class="w-32 h-32 object-cover">
                    @endif
                    <div class="p-5 flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-serif text-lg text-emerald-900">{{ $annonce->titre }}</h3>
                                <p class="text-gray-600 text-sm mt-1">{{ Str::limit($annonce->contenu, 150) }}</p>
                            </div>
                            @if($annonce->actif && $annonce->date_fin >= now())
                                <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full font-medium whitespace-nowrap ml-3">
                                    ● Active
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded-full font-medium whitespace-nowrap ml-3">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-400">
                                Du {{ $annonce->date_debut->format('d/m/Y') }}
                                @if($annonce->date_fin) au {{ $annonce->date_fin->format('d/m/Y') }} @endif
                                — par {{ $annonce->createdBy->name ?? '-' }}
                            </p>
                            <div class="flex gap-3">
                                <form method="POST" action="{{ route('annonces.toggle-actif', $annonce) }}">
                                    @csrf
                                    <button type="submit" class="text-emerald-700 text-xs hover:underline font-medium">
                                        {{ $annonce->actif ? 'Désactiver' : 'Réactiver' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('annonces.destroy', $annonce) }}"
                                    onsubmit="return confirm('Supprimer définitivement cette annonce ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-xs hover:underline font-medium">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white shadow rounded-xl border border-emerald-100 p-10 text-center">
                <p class="text-gray-400">Aucune annonce publiée pour le moment.</p>
            </div>
            @endforelse

        </div>
    </div>
</x-app-layout> 
