<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Interventions
            </h2>
            <div class="space-x-2">
                @can('create', App\Models\Intervention::class)
                    <a href="{{ route('interventions.export', request()->all()) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        📥 Export CSV
                    </a>
                    <a href="{{ route('interventions.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        + Nouvelle intervention
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtres -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('interventions.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Client, appareil..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <select name="statut" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <option value="">Tous</option>
                                <option value="nouvelle_demande" {{ request('statut') == 'nouvelle_demande' ? 'selected' : '' }}>Nouvelle demande</option>
                                <option value="diagnostic" {{ request('statut') == 'diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                                <option value="en_reparation" {{ request('statut') == 'en_reparation' ? 'selected' : '' }}>En réparation</option>
                                <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                                <option value="non_reparable" {{ request('statut') == 'non_reparable' ? 'selected' : '' }}>Non réparable</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                            <select name="priorite" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <option value="">Toutes</option>
                                <option value="basse" {{ request('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                                <option value="normale" {{ request('priorite') == 'normale' ? 'selected' : '' }}>Normale</option>
                                <option value="haute" {{ request('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                            </select>
                        </div>

                        @if(Auth::user()->isAdmin())
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Technicien</label>
                            <select name="technicien_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <option value="">Tous</option>
                                @foreach($techniciens as $tech)
                                    <option value="{{ $tech->id }}" {{ request('technicien_id') == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Appareil</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priorité</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Technicien</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($interventions as $intervention)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            #{{ $intervention->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $intervention->client->nom }}</div>
                                            <div class="text-sm text-gray-500">{{ $intervention->client->telephone }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $intervention->type_appareil }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($intervention->description, 40) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($intervention->priorite == 'haute') bg-red-100 text-red-800
                                                @elseif($intervention->priorite == 'normale') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $intervention->priorite_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($intervention->statut == 'nouvelle_demande') bg-yellow-100 text-yellow-800
                                                @elseif($intervention->statut == 'diagnostic') bg-blue-100 text-blue-800
                                                @elseif($intervention->statut == 'en_reparation') bg-purple-100 text-purple-800
                                                @elseif($intervention->statut == 'termine') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $intervention->statut_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $intervention->technicien?->name ?? 'Non assigné' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $intervention->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('interventions.show', $intervention) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Voir
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            Aucune intervention trouvée
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $interventions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
