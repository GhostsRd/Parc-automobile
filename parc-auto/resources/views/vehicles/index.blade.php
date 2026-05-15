<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion du Parc Automobile') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput"
                        placeholder="Rechercher une immatriculation, une marque ou un modèle..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out">
                </div>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Liste des véhicules</h3>

                    <a href="{{ route('vehicles.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Ajouter un véhicule') }}
                    </a>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Véhicule & Infos
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Statut
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="vehicleTable" class="divide-y divide-gray-100 bg-white">
                            @foreach($vehicles as $vehicle)
                            <tr class="hover:bg-gray-50/80 transition-all duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 flex-shrink-0 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $vehicle->immatriculation }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $vehicle->marque }} {{
                                                $vehicle->modele }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    // On définit les classes complètes ici pour que Tailwind puisse les "voir"
                                    $statusMap = [
                                    'disponible' => [
                                    'bg' => 'bg-green-50',
                                    'text' => 'text-green-700',
                                    'border' => 'border-green-200',
                                    'dot' => 'bg-green-500'
                                    ],
                                    'en_reparation' => [
                                    'bg' => 'bg-red-50',
                                    'text' => 'text-red-700',
                                    'border' => 'border-red-200',
                                    'dot' => 'bg-red-500'
                                    ],
                                    'en_mission' => [
                                    'bg' => 'bg-blue-50',
                                    'text' => 'text-blue-700',
                                    'border' => 'border-blue-200',
                                    'dot' => 'bg-blue-500'
                                    ],
                                    ];

                                    $currentStatus = $statusMap[$vehicle->statut] ?? [
                                    'bg' => 'bg-gray-50',
                                    'text' => 'text-gray-700',
                                    'border' => 'border-gray-200',
                                    'dot' => 'bg-gray-500'
                                    ];
                                    @endphp

                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border {{ $currentStatus['bg'] }} {{ $currentStatus['text'] }} {{ $currentStatus['border'] }}">
                                        <span class="h-1.5 w-1.5 rounded-full mr-2 {{ $currentStatus['dot'] }}"></span>
                                        {{ ucfirst(str_replace('_', ' ', $vehicle->statut)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('vehicles.show', $vehicle) }}"
                                            class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                            Détails
                                        </a>
                                        <a href="{{ route('vehicles.edit', $vehicle) }}"
                                            class="p-1 text-gray-400 hover:text-indigo-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Supprimer ce véhicule ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1 text-gray-400 hover:text-red-600 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#vehicleTable tr');

        rows.forEach(row => {
            // On récupère tout le texte de la ligne (Immatriculation + Marque + Modèle)
            let text = row.textContent.toLowerCase();
            
            // Si le texte contient la recherche, on affiche, sinon on cache
            if (text.includes(filter)) {
                row.style.display = "";
                row.classList.add('fadeIn'); // Optionnel : petit effet visuel
            } else {
                row.style.display = "none";
            }
        });
    });
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .fadeIn {
        animation: fadeIn 0.3s;
    }
</style>