<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Gestion du Parc Automobile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl p-6">
                
                <div class="mb-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput"
                        placeholder="Rechercher une immatriculation, une marque ou un modèle..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                </div>

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Liste des véhicules</h3>

                    <a href="{{ route('vehicles.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md shadow-indigo-100">
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
                                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-widest">
                                    Véhicule & Infos
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-widest">
                                    Kilométrage Actuel
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-widest">
                                    Statut
                                </th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-black text-gray-500 uppercase tracking-widest">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="vehicleTable" class="divide-y divide-gray-100 bg-white">
                            @foreach($vehicles as $vehicle)
                            <tr class="hover:bg-gray-50/80 transition-all duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 uppercase">{{ $vehicle->immatriculation }}</div>
                                            <div class="text-sm text-gray-500">{{ $vehicle->marque }} {{ $vehicle->modele }}</div>
                                            
                                            @if($vehicle->zone_affectation)
                                                <div class="text-xs text-indigo-500 font-semibold mt-0.5 inline-flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Secteur : {{ ucfirst($vehicle->zone_affectation) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                                    {{ number_format($vehicle->kilometrage_actuel, 0, ',', ' ') }} KM
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $statusMap = [
                                        'actif' => [
                                            'bg' => 'bg-green-50',
                                            'text' => 'text-green-700',
                                            'border' => 'border-green-200',
                                            'dot' => 'bg-green-500',
                                            'label' => 'Actif'
                                        ],
                                        'en_maintenance' => [
                                            'bg' => 'bg-amber-50',
                                            'text' => 'text-amber-700',
                                            'border' => 'border-amber-200',
                                            'dot' => 'bg-amber-500',
                                            'label' => 'En Maintenance'
                                        ],
                                        'hors_service' => [
                                            'bg' => 'bg-red-50',
                                            'text' => 'text-red-700',
                                            'border' => 'border-red-200',
                                            'dot' => 'bg-red-500',
                                            'label' => 'Hors Service'
                                        ],
                                    ];

                                    $currentStatus = $statusMap[$vehicle->statut] ?? [
                                        'bg' => 'bg-gray-50',
                                        'text' => 'text-gray-700',
                                        'border' => 'border-gray-200',
                                        'dot' => 'bg-gray-500',
                                        'label' => $vehicle->statut
                                    ];
                                    @endphp

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold border {{ $currentStatus['bg'] }} {{ $currentStatus['text'] }} {{ $currentStatus['border'] }}">
                                        <span class="h-1.5 w-1.5 rounded-full mr-2 {{ $currentStatus['dot'] }}"></span>
                                        {{ $currentStatus['label'] }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('vehicles.show', $vehicle) }}"
                                            class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                                            Détails
                                        </a>
                                        <a href="{{ route('vehicles.edit', $vehicle) }}"
                                            class="p-1 text-gray-400 hover:text-indigo-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Supprimer définitivement ce véhicule du parc ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1 text-gray-400 hover:text-red-600 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
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
            let text = row.textContent.toLowerCase();
            if (text.includes(filter)) {
                row.style.display = "";
                row.classList.add('fadeIn');
            } else {
                row.style.display = "none";
            }
        });
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .fadeIn { animation: fadeIn 0.3s; }
</style>