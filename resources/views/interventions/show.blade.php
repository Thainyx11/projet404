<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Intervention #{{ $intervention->id }}
            </h2>
            <div class="space-x-2">
                @can('update', $intervention)
                    <a href="{{ route('interventions.edit', $intervention) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Modifier
                    </a>
                @endcan
                <a href="{{ route('interventions.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Informations principales -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informations générales</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Client</dt>
                                    <dd class="mt-1">
                                        <a href="{{ route('clients.show', $intervention->client) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $intervention->client->nom }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Type d'appareil</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $intervention->type_appareil }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $intervention->description }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Suivi</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                    <dd class="mt-1">
                                        <span class="px-3 py-1 text-sm rounded-full
                                            @if($intervention->statut == 'nouvelle_demande') bg-yellow-100 text-yellow-800
                                            @elseif($intervention->statut == 'diagnostic') bg-blue-100 text-blue-800
                                            @elseif($intervention->statut == 'en_reparation') bg-purple-100 text-purple-800
                                            @elseif($intervention->statut == 'termine') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $intervention->statut_label }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Priorité</dt>
                                    <dd class="mt-1">
                                        <span class="px-3 py-1 text-sm rounded-full
                                            @if($intervention->priorite == 'haute') bg-red-100 text-red-800
                                            @elseif($intervention->priorite == 'normale') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $intervention->priorite_label }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Technicien assigné</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $intervention->technicien?->name ?? 'Non assigné' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date prévue</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $intervention->date_prevue?->format('d/m/Y') ?? 'Non définie' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $intervention->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images -->
            @if($intervention->images->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Photos ({{ $intervention->images->count() }})</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($intervention->images as $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image->chemin) }}" alt="Image intervention" class="w-full h-32 object-cover rounded-lg">
                                @can('update', $intervention)
                                    <form action="{{ route('intervention-images.destroy', $image) }}" method="POST" class="absolute top-2 right-2" onsubmit="return confirm('Supprimer cette image ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition">
                                            ✕
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes internes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Notes internes ({{ $intervention->notes->count() }})</h3>

                    @can('addNote', $intervention)
                    <form action="{{ route('interventions.notes.store', $intervention) }}" method="POST" class="mb-6">
                        @csrf
                        <div class="mb-3">
                            <textarea name="contenu" rows="3" placeholder="Ajouter une note interne..." required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            Ajouter une note
                        </button>
                    </form>
                    @endcan

                    <div class="space-y-4">
                        @forelse($intervention->notes()->latest()->get() as $note)
                            <div class="border-l-4 border-indigo-500 pl-4 py-2">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="font-semibold text-sm text-gray-900">{{ $note->user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $note->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $note->contenu }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">Aucune note pour cette intervention</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
