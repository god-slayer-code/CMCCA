<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-emerald-900 tracking-wide">
            Nouvelle Annonce
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl border border-emerald-100 p-8">

                <div class="mb-6 pb-4 border-b border-emerald-100">
                    <p class="text-emerald-700 text-sm font-medium tracking-wide uppercase">Publication</p>
                    <p class="text-gray-500 text-sm mt-1">Cette annonce apparaîtra dans le bandeau du tableau de bord.</p>
                </div>

                <form method="POST" action="{{ route('annonces.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-5">

                        <div>
                            <label class="block text-sm font-medium text-emerald-900 mb-1">Titre *</label>
                            <input type="text" name="titre" value="{{ old('titre') }}"
                                class="border border-gray-300 rounded-lg w-full px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                                placeholder="Ex : Visite médicale du Dr. Diallo" required>
                            @error('titre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-emerald-900 mb-1">Contenu *</label>
                            <textarea name="contenu" rows="4"
                                class="border border-gray-300 rounded-lg w-full px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                                placeholder="Détails de l'annonce..." required>{{ old('contenu') }}</textarea>
                            @error('contenu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-emerald-900 mb-1">Image (optionnelle)</label>
                            <div class="border border-dashed border-emerald-300 rounded-lg p-4 bg-emerald-50/40">
                                <input type="file" name="image" accept="image/png,image/jpeg"
                                    class="text-sm text-gray-600 w-full
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-lg file:border-0
                                        file:bg-emerald-600 file:text-white
                                        file:text-sm file:font-medium
                                        hover:file:bg-emerald-700 file:cursor-pointer">
                            </div>
                            @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-sm font-medium text-emerald-900 mb-1">Date de début</label>
                                <input type="date" name="date_debut" value="{{ old('date_debut', now()->format('Y-m-d')) }}"
                                    class="border border-gray-300 rounded-lg w-full px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-emerald-900 mb-1">
                                    Date de fin <span class="text-gray-400 font-normal text-xs">(14 j. par défaut)</span>
                                </label>
                                <input type="date" name="date_fin" value="{{ old('date_fin') }}"
                                    class="border border-gray-300 rounded-lg w-full px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                                @error('date_fin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                    </div>

                    <div class="mt-8 flex gap-3 pt-6 border-t border-emerald-100">
                        <button type="submit"
                            class="bg-emerald-700 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-emerald-800 transition shadow-sm">
                            Publier l'annonce
                        </button>
                        <a href="{{ route('annonces.index') }}"
                            class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg font-medium hover:bg-gray-200 transition">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout> 
