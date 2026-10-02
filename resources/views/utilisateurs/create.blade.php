<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Nouveau compte utilisateur
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                <form method="POST" action="{{ route('utilisateurs.store') }}">
                    @csrf

                    <div class="space-y-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom complet *</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="mt-1 border rounded w-full px-3 py-2" required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="mt-1 border rounded w-full px-3 py-2" required>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mot de passe *</label>
                            <input type="password" name="password"
                                class="mt-1 border rounded w-full px-3 py-2" required>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rôle *</label>
                            <select name="role_id" class="mt-1 border rounded w-full px-3 py-2" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $role->nom)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Créer le compte
                        </button>
                        <a href="{{ route('utilisateurs.index') }}"
                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout> 
