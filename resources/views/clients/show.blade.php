<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Client : {{ $client->nom }}
            </h2>
            <div class="space-x-2">
                @can('update', $client)
                    <a href="{{ route('clients.edit', $client) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Modifier
                    </a>
                @endcan
                <a href="{{ route('clients.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Informations du client</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nom</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->nom }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->telephone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->adresse ?? 'Non renseignée' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Historique des interventions ({{ $client->interventions->count() }})</h3>
                </div>
                <div class="p-6">
                    @forelse($client->interventions as $intervention)
                        <div class="border-b border-gray-200 pb-4 mb-4 last:border-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $intervention->type_appareil }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $intervention->description }}</p>
                                    <div class="flex space-x-4 mt-2">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($intervention->statut == 'nouvelle_demande') bg-yellow-100 text-yellow-800
                                            @elseif($intervention->statut == 'diagnostic') bg-blue-100 text-blue-800
                                            @elseif($intervention->statut == 'en_reparation') bg-purple-100 text-purple-800
                                            @elseif($intervention->statut == 'termine') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $intervention->statut_label }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $intervention->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('interventions.show', $intervention) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                    Voir détails →
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucune intervention</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
