<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Configuration de la clinique
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('configuration.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Logo --}}
                    <div class="mb-6 flex items-center gap-4">
                        <div class="w-20 h-20 border rounded flex items-center justify-center bg-gray-50 overflow-hidden">
                            @if($config->logo_url)
                                <img src="{{ $config->logo_url }}" alt="Logo" class="w-full h-full object-contain">
                            @else
                                <span class="text-gray-300 text-xs text-center">Pas de logo</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Logo de la clinique</label>
                            <input type="file" name="logo" accept="image/png,image/jpeg"
                                class="mt-1 border rounded w-full px-3 py-2 text-sm">
                            @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Nom de la clinique *</label>
                            <input type="text" name="nom_clinique" value="{{ old('nom_clinique', $config->nom_clinique) }}"
                                class="mt-1 border rounded w-full px-3 py-2" required>
                            @error('nom_clinique') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Slogan</label>
                            <input type="text" name="slogan" value="{{ old('slogan', $config->slogan) }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Téléphone 1 *</label>
                            <input type="text" name="telephone_1" value="{{ old('telephone_1', $config->telephone_1) }}"
                                class="mt-1 border rounded w-full px-3 py-2" required>
                            @error('telephone_1') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Téléphone 2</label>
                            <input type="text" name="telephone_2" value="{{ old('telephone_2', $config->telephone_2) }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $config->email) }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Adresse *</label>
                            <input type="text" name="adresse" value="{{ old('adresse', $config->adresse) }}"
                                class="mt-1 border rounded w-full px-3 py-2" required>
                            @error('adresse') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ville</label>
                            <input type="text" name="ville" value="{{ old('ville', $config->ville) }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pays</label>
                            <input type="text" name="pays" value="{{ old('pays', $config->pays) }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Boîte Postale</label>
                            <input type="text" name="bp" value="{{ old('bp', $config->bp) }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Compte bancaire</label>
                            <input type="text" name="compte_bancaire" value="{{ old('compte_bancaire', $config->compte_bancaire) }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">SWIFT</label>
                            <input type="text" name="swift" value="{{ old('swift', $config->swift) }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Pied de page (reçus / factures)</label>
                            <input type="text" name="pied_de_page" value="{{ old('pied_de_page', $config->pied_de_page) }}"
                                class="mt-1 border rounded w-full px-3 py-2">
                        </div>

                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Enregistrer
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout> 
