<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nouvelle intervention
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('interventions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                                <select name="client_id" id="client_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('client_id') border-red-500 @enderror">
                                    <option value="">Sélectionnez un client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }} - {{ $client->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type_appareil" class="block text-sm font-medium text-gray-700 mb-2">Type d'appareil *</label>
                                <select name="type_appareil" id="type_appareil" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('type_appareil') border-red-500 @enderror">
                                    <option value="">Sélectionnez...</option>
                                    <option value="Ordinateur portable">Ordinateur portable</option>
                                    <option value="PC fixe">PC fixe</option>
                                    <option value="Smartphone">Smartphone</option>
                                    <option value="Tablette">Tablette</option>
                                    <option value="Imprimante">Imprimante</option>
                                    <option value="Autre">Autre</option>
                                </select>
                                @error('type_appareil')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description du problème *</label>
                            <textarea name="description" id="description" rows="5" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <div>
                                <label for="priorite" class="block text-sm font-medium text-gray-700 mb-2">Priorité *</label>
                                <select name="priorite" id="priorite" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="normale" selected>Normale</option>
                                    <option value="basse">Basse</option>
                                    <option value="haute">Haute</option>
                                </select>
                            </div>

                            <div>
                                <label for="technicien_id" class="block text-sm font-medium text-gray-700 mb-2">Technicien</label>
                                <select name="technicien_id" id="technicien_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Non assigné</option>
                                    @foreach($techniciens as $technicien)
                                        <option value="{{ $technicien->id }}">{{ $technicien->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date_prevue" class="block text-sm font-medium text-gray-700 mb-2">Date prévue</label>
                                <input type="date" name="date_prevue" id="date_prevue" min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Photos (optionnel)</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">Maximum 5MB par image, formats : JPEG, PNG, GIF</p>
                        </div>

                        <div class="flex justify-end space-x-3 mt-8">
                            <a href="{{ route('interventions.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                                Annuler
                            </a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                Créer l'intervention
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
