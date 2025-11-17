<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier l'intervention #{{ $intervention->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('interventions.update', $intervention) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                                <select name="client_id" id="client_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('client_id') border-red-500 @enderror">
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $intervention->client_id) == $client->id ? 'selected' : '' }}>
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
                                    <option value="Ordinateur portable" {{ old('type_appareil', $intervention->type_appareil) == 'Ordinateur portable' ? 'selected' : '' }}>Ordinateur portable</option>
                                    <option value="PC fixe" {{ old('type_appareil', $intervention->type_appareil) == 'PC fixe' ? 'selected' : '' }}>PC fixe</option>
                                    <option value="Smartphone" {{ old('type_appareil', $intervention->type_appareil) == 'Smartphone' ? 'selected' : '' }}>Smartphone</option>
                                    <option value="Tablette" {{ old('type_appareil', $intervention->type_appareil) == 'Tablette' ? 'selected' : '' }}>Tablette</option>
                                    <option value="Imprimante" {{ old('type_appareil', $intervention->type_appareil) == 'Imprimante' ? 'selected' : '' }}>Imprimante</option>
                                    <option value="Autre" {{ old('type_appareil', $intervention->type_appareil) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('type_appareil')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description du problème *</label>
                            <textarea name="description" id="description" rows="5" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $intervention->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <div>
                                <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                                <select name="statut" id="statut" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="nouvelle_demande" {{ old('statut', $intervention->statut) == 'nouvelle_demande' ? 'selected' : '' }}>Nouvelle demande</option>
                                    <option value="diagnostic" {{ old('statut', $intervention->statut) == 'diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                                    <option value="en_reparation" {{ old('statut', $intervention->statut) == 'en_reparation' ? 'selected' : '' }}>En réparation</option>
                                    <option value="termine" {{ old('statut', $intervention->statut) == 'termine' ? 'selected' : '' }}>Terminé</option>
                                    <option value="non_reparable" {{ old('statut', $intervention->statut) == 'non_reparable' ? 'selected' : '' }}>Non réparable</option>
                                </select>
                            </div>

                            <div>
                                <label for="priorite" class="block text-sm font-medium text-gray-700 mb-2">Priorité *</label>
                                <select name="priorite" id="priorite" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="basse" {{ old('priorite', $intervention->priorite) == 'basse' ? 'selected' : '' }}>Basse</option>
                                    <option value="normale" {{ old('priorite', $intervention->priorite) == 'normale' ? 'selected' : '' }}>Normale</option>
                                    <option value="haute" {{ old('priorite', $intervention->priorite) == 'haute' ? 'selected' : '' }}>Haute</option>
                                </select>
                            </div>

                            <div>
                                <label for="date_prevue" class="block text-sm font-medium text-gray-700 mb-2">Date prévue</label>
                                <input type="date" name="date_prevue" id="date_prevue" value="{{ old('date_prevue', $intervention->date_prevue?->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        @if(Auth::user()->isAdmin())
                        <div class="mt-6">
                            <label for="technicien_id" class="block text-sm font-medium text-gray-700 mb-2">Technicien assigné</label>
                            <select name="technicien_id" id="technicien_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Non assigné</option>
                                @foreach($techniciens as $technicien)
                                    <option value="{{ $technicien->id }}" {{ old('technicien_id', $intervention->technicien_id) == $technicien->id ? 'selected' : '' }}>
                                        {{ $technicien->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mt-6">
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Ajouter des photos</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">Maximum 5MB par image, formats : JPEG, PNG, GIF</p>
                        </div>

                        <div class="flex justify-end space-x-3 mt-8">
                            <a href="{{ route('interventions.show', $intervention) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                                Annuler
                            </a>
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
