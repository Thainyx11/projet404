<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes interventions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Interventions qui me sont assignées</h3>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Appareil</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priorité</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date prévue</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($interventions as $intervention)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $intervention->client->nom }}</div>
                                            <div class="text-sm text-gray-500">{{ $intervention->client->telephone }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $intervention->type_appareil }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($intervention->description, 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($intervention->statut == 'nouvelle_demande') bg-yellow-100 text-yellow-800
                                                @elseif($intervention->statut == 'diagnostic') bg-blue-100 text-blue-800
                                                @elseif($intervention->statut == 'en_reparation') bg-purple-100 text-purple-800
                                                @elseif($intervention->statut == 'termine') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $intervention->statut_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($intervention->priorite == 'haute') bg-red-100 text-red-800
                                                @elseif($intervention->priorite == 'normale') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $intervention->priorite_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $intervention->date_prevue?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('interventions.show', $intervention) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Voir
                                            </a>
                                            <a href="{{ route('interventions.edit', $intervention) }}" class="text-green-600 hover:text-green-900">
                                                Modifier
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Aucune intervention assignée pour le moment
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
