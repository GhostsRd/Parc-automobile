<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Suivi du Carburant & Consommation') }}
            </h2>
            <a href="{{ route('fuel.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Enregistrer un plein') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Messages Flash d'Alerte ou Succès -->
            @if(session('success'))
            <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-md shadow-sm">
                <div class="flex items-center">
                    <span class="text-xl me-2">✅</span>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('warning'))
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-md shadow-sm animate-pulse">
                <div class="flex items-start">
                    <span class="text-xl me-2">⚠️</span>
                    <div>
                        <p class="text-sm font-bold">{{ __('Anomalie de consommation détectée') }}</p>
                        <p class="text-xs mt-0.5">{{ session('warning') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Section Filtres -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                <form method="GET" action="{{ route('fuel.index') }}"
                    class="grid grid-cols-1 md:grid-cols-3 items-end gap-4">

                    <div class="w-full">
                        <label for="vehicle_id"
                            class="block text-xs font-black uppercase text-gray-400 tracking-wider mb-2">
                            {{ __('Filtrer par Véhicule') }}
                        </label>
                        <select id="vehicle_id" name="vehicle_id"
                            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-medium h-10"
                            onchange="this.form.submit()">
                            <option value="">{{ __('Tous les véhicules') }}</option>
                            @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ request('vehicle_id')==$vehicle->id ? 'selected' : ''
                                }}>
                                🚗 {{ $vehicle->marque }} {{ $vehicle->modele }} ({{ $vehicle->immatriculation }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full">
                        <label for="search"
                            class="block text-xs font-black uppercase text-gray-400 tracking-wider mb-2">
                            {{ __('Destination, Voyage ou Nom') }}
                        </label>
                        <div class="relative">
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Ex: Tamatave, Entretien, Camion..."
                                class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm h-10 pr-10">
                            <button type="submit"
                                class="absolute right-2.5 top-2.5 text-gray-400 hover:text-indigo-600">
                                🔍
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 h-10">
                        <button type="submit"
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm transition">
                            {{ __('Rechercher') }}
                        </button>

                        @if(request('vehicle_id') || request('search'))
                        <a href="{{ route('fuel.index') }}"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase tracking-wider rounded-xl transition text-center shadow-sm">
                            ✖️ {{ __('Effacer') }}
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Rapports Mensuels & Statistiques de l'année -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-xs font-bold uppercase text-gray-500 tracking-wider">{{ __('Rapport de Consommation
                        Mensuel') }} ({{ date('Y') }})</h3>
                </div>
                <div class="p-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @php
                    $monthsLabels = [1 => 'Janv', 2 => 'Févr', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 =>
                    'Juil', 8 => 'Août', 9 => 'Sept', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'];
                    @endphp

                    @foreach($monthsLabels as $num => $label)
                    @php $report = $monthlyReports->get($num); @endphp
                    <div
                        class="p-3 rounded-lg border {{ $report ? 'bg-indigo-50/40 border-indigo-100' : 'bg-gray-50/50 border-gray-100' }}">
                        <p class="text-xs font-bold text-gray-400 uppercase">{{ $label }}</p>
                        @if($report)
                        <p class="text-lg font-bold text-indigo-900 mt-1">{{ number_format($report->total_litres, 0,
                            ',', ' ') }} <span class="text-xs font-normal text-gray-500">L</span></p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">{{ number_format($report->total_cost, 0,
                            ',', ' ') }} €</p>
                        <p
                            class="text-[10px] text-indigo-600 font-semibold mt-1 bg-indigo-100/50 px-1.5 py-0.5 rounded inline-block">
                            {{ $report->total_refuels }} {{ trans_choice('plein|pleins', $report->total_refuels) }}
                        </p>
                        @else
                        <p class="text-sm font-semibold text-gray-300 mt-2">—</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tableau d'historique principal -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800">{{ __('Historique des Ravitaillements') }}</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr
                                class="bg-gray-50 text-xs font-bold uppercase text-gray-500 tracking-wider border-b border-gray-100">
                                <th class="px-6 py-3">{{ __('Date') }}</th>
                                <th class="px-6 py-3">{{ __('Véhicule') }}</th>
                                <th class="px-6 py-3">{{ __('Kilométrage') }}</th>
                                <th class="px-6 py-3 text-right">{{ __('Volume (L)') }}</th>
                                <th class="px-6 py-3 text-right">{{ __('Prix Unitaire') }}</th>
                                <th class="px-6 py-3 text-right">{{ __('Coût Total') }}</th>
                                <th class="px-6 py-3 text-center">{{ __('Consommation') }}</th>
                                <th class="px-6 py-3">{{ __('Station-Service') }}</th>
                                <th class="px-6 py-3 text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($fuelEntries as $entry)
                            <tr
                                class="{{ $entry->anomalie_detectee ? 'bg-red-50/70 hover:bg-red-50 text-red-950' : 'hover:bg-gray-50/80 text-gray-700' }}">
                                <td class="px-6 py-4 font-medium">
                                    {{ \Carbon\Carbon::parse($entry->date_ravitaillement)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $entry->vehicle->marque }} {{
                                        $entry->vehicle->modele }}</div>
                                    <div class="text-xs text-gray-400">{{ $entry->vehicle->immatriculation }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono font-medium">
                                    {{ number_format($entry->kilometrage, 0, ',', ' ') }} km
                                </td>
                                <td class="px-6 py-4 text-right font-semibold">
                                    {{ number_format($entry->quantite_litres, 2, ',', ' ') }} L
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-xs text-gray-500">
                                    {{ number_format($entry->prix_unitaire, 3, ',', ' ') }} €/L
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900">
                                    {{ number_format($entry->cout_total, 2, ',', ' ') }} €
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($entry->consommation_calculee)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $entry->anomalie_detectee ? 'bg-red-200 text-red-800 animate-pulse' : 'bg-green-100 text-green-800' }}">
                                        {{ number_format($entry->consommation_calculee, 2, ',', ' ') }} L/100
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-400 italic">{{ __('Calcul au prochain plein')
                                        }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-gray-600 truncate max-w-[150px]">
                                    📍 {{ $entry->station_service }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('fuel.edit', $entry) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs uppercase tracking-wide">
                                            {{ __('Modifier') }}
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form method="POST" action="{{ route('fuel.destroy', $entry) }}"
                                            onsubmit="return confirm('Confirmer la suppression de ce ravitaillement ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 font-semibold text-xs uppercase tracking-wide cursor-pointer">
                                                {{ __('Supprimer') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-400 bg-gray-50/30">
                                    <div class="text-3xl mb-2">⛽</div>
                                    <p class="text-sm font-medium">{{ __('Aucun enregistrement de carburant trouvé.') }}
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($fuelEntries->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $fuelEntries->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>