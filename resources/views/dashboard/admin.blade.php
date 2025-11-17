<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tableau de bord - Administrateur
            </h2>
            <a href="{{ route('interventions.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                + Nouvelle intervention
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total interventions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Total interventions</div>
                    <div class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</div>
                </div>

                <!-- Nouvelles demandes -->
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-yellow-700 text-sm font-medium">Nouvelles demandes</div>
                    <div class="text-3xl font-bold text-yellow-900 mt-2">{{ $stats['nouvelle_demande'] }}</div>
                </div>

                <!-- En cours -->
                <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-blue-700 text-sm font-medium">En cours</div>
                    <div class="text-3xl font-bold text-blue-900 mt-2">{{ $stats['en_cours'] }}</div>
                </div>

                <!-- Terminées -->
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-green-700 text-sm font-medium">Terminées</div>
                    <div class="text-3xl font-bold text-green-900 mt-2">{{ $stats['termine'] }}</div>
                </div>
            </div>

            <!-- Liste des interventions récentes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Interventions récentes</h3>
                        <a href="{{ route('interventions.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            Voir tout →
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Client
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Appareil
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Technicien
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($interventions as $intervention)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $intervention->client->nom }}</div>
                                            <div class="text-sm text-gray-500">{{ $intervention->client->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $intervention->type_appareil }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($intervention->statut == 'nouvelle_demande')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ $intervention->statut_label }}
                                                </span>
                                            @elseif($intervention->statut == 'diagnostic')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $intervention->statut_label }}
                                                </span>
                                            @elseif($intervention->statut == 'en_reparation')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    {{ $intervention->statut_label }}
                                                </span>
                                            @elseif($intervention->statut == 'termine')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $intervention->statut_label }}
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ $intervention->statut_label }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $intervention->technicien?->name ?? 'Non assigné' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $intervention->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('interventions.show', $intervention) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                Voir
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            <div class="text-lg">Aucune intervention</div>
                                            <div class="text-sm mt-1">Les nouvelles demandes apparaîtront ici</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $interventions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
