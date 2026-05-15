<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Détails du véhicule : <span class="text-indigo-600">{{ $vehicle->immatriculation }}</span>
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('vehicles.index') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-50 transition shadow-sm">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retour
                </a>

                <a href="{{ route('vehicles.edit', $vehicle) }}"
                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-lg text-xs font-medium text-white hover:bg-indigo-700 transition shadow-sm shadow-indigo-100">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Modifier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl p-6 border border-gray-100">
                        <div
                            class="flex items-center justify-center h-32 w-full bg-gray-50 rounded-lg mb-4 border-2 border-dashed border-gray-200">
                            <svg class="h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            </svg>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Informations</h3>

                        <div class="space-y-4 text-sm">
                            <div>
                                <p class="text-gray-500 uppercase text-xs font-bold tracking-wider">Marque & Modèle</p>
                                <p class="font-bold text-gray-800 text-base">{{ $vehicle->marque }} {{ $vehicle->modele
                                    }}</p>
                            </div>

                            <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100">
                                <p class="text-indigo-600 uppercase text-[10px] font-black tracking-widest">Kilométrage
                                    au compteur</p>
                                <div class="flex items-baseline">
                                    <p class="text-xl font-black text-indigo-900 font-mono">{{
                                        number_format($vehicle->kilometrage_actuel, 0, ',', ' ') }}</p>
                                    <span class="ml-1 text-xs font-bold text-indigo-400 uppercase">km</span>
                                </div>
                            </div>

                            <div>
                                <p class="text-gray-500 uppercase text-xs font-bold tracking-wider">Statut Actuel</p>
                                @php
                                $statusClasses = [
                                'disponible' => 'bg-emerald-100 text-emerald-800',
                                'en_mission' => 'bg-amber-100 text-amber-800',
                                'en_reparation' => 'bg-red-100 text-red-800'
                                ][$vehicle->statut] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black uppercase tracking-tighter {{ $statusClasses }}">
                                    {{ ucfirst(str_replace('_', ' ', $vehicle->statut)) }}
                                </span>
                            </div>

                            <div
                                class="flex items-center p-4 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-10 w-10 rounded-full bg-white flex items-center justify-center shadow-sm">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                stroke-width="2" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Chauffeur
                                        assigné</h4>
                                    <p
                                        class="text-sm font-bold {{ $vehicle->driver ? 'text-indigo-600' : 'text-gray-500 italic' }}">
                                        {{ $vehicle->driver->full_name ?? 'Aucun titulaire' }}
                                    </p>
                                </div>
                            </div>

                            <div class="pt-2">
                                <p class="text-gray-400 uppercase text-[10px] font-bold">Date d'ajout au parc</p>
                                <p class="text-gray-600 font-medium">{{ $vehicle->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2" x-data="{ showForm: false }">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800">Historique des Maintenances</h3>

                            <button @click="showForm = true" x-show="!showForm"
                                class="inline-flex items-center px-3 py-1.5 border border-indigo-200 text-xs font-bold rounded-lg text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                                AJOUTER
                            </button>
                        </div>

                        <div class="p-6">
                            <div x-show="showForm" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 -translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-200">

                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-md font-bold text-gray-800 uppercase tracking-wider">Nouvelle
                                        intervention</h3>
                                    <button @click="showForm = false"
                                        class="text-gray-400 hover:text-gray-600 text-xs font-bold">
                                        FERMER
                                    </button>
                                </div>

                                <form action="{{ route('maintenances.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Type
                                                d'acte</label>
                                            <select name="type"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-fuchsia-500 focus:border-fuchsia-500">
                                                <option value="vidange">Vidange</option>
                                                <option value="pneus">Pneumatiques</option>
                                                <option value="freins">Système de freinage</option>
                                                <option value="courroie">Courroie de distribution</option>
                                                <option value="autre">Autre intervention</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Date
                                                d'intervention</label>
                                            <input type="date" name="date_intervention" value="{{ date('Y-m-d') }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-fuchsia-500 focus:border-fuchsia-500">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase">KM au moment
                                                de l'acte</label>
                                            <input type="number" name="kilometrage_au_moment_de_l_acte"
                                                placeholder="Ex: 125000"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-fuchsia-500 focus:border-fuchsia-500">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-bold text-gray-500 uppercase text-fuchsia-600">Prochain
                                                rappel (KM)</label>
                                            <input type="number" name="prochain_kilometrage_rappel"
                                                placeholder="Ex: 140000"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border-fuchsia-200 focus:ring-fuchsia-500 focus:border-fuchsia-500">
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Coût total
                                                (€)</label>
                                            <input type="number" step="0.01" name="cout"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-fuchsia-500 focus:border-fuchsia-500"
                                                placeholder="0.00">
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Notes
                                                détaillées</label>
                                            <textarea name="notes" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-fuchsia-500 focus:border-fuchsia-500"
                                                placeholder="Détails des pièces changées..."></textarea>
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <button type="submit"
                                            class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-blue-500 hover:bg-fuchsia-700 transition">
                                            Enregistrer l'intervention
                                        </button>
                                    </div>
                                </form>
                            </div>

                            @if(isset($vehicle->maintenances) && $vehicle->maintenances->count() > 0)
                            <div class="flow-root">
                                <ul role="list" class="-mb-8">
                                    @foreach($vehicle->maintenances->sortByDesc('date_intervention') as $maintenance)
                                    <li>
                                        <div class="relative pb-8">
                                            @if (!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span
                                                        class="h-8 w-8 rounded-full {{ $maintenance->type == 'vidange' ? 'bg-fuchsia-500' : 'bg-indigo-500' }} flex items-center justify-center ring-8 ring-white text-white">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm font-bold text-gray-900">
                                                            {{ ucfirst($maintenance->type) }}
                                                            @if($maintenance->cout)
                                                            <span class="ml-2 text-gray-500 font-normal">({{
                                                                number_format($maintenance->cout, 2) }} €)</span>
                                                            @endif
                                                        </p>
                                                        <p class="text-sm text-gray-600 mt-1">{{ $maintenance->notes ??
                                                            'Aucune note.' }}</p>
                                                        <p class="text-xs text-gray-400 mt-1">{{
                                                            number_format($maintenance->kilometrage_au_moment_de_l_acte,
                                                            0, ',', ' ') }} km</p>
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-400">
                                                        <time>{{
                                                            \Carbon\Carbon::parse($maintenance->date_intervention)->format('d
                                                            M Y') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @else
                            <div x-show="!showForm" class="text-center py-12">
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 italic">Aucun historique de maintenance.</p>
                                <button @click="showForm = true"
                                    class="mt-4 text-fuchsia-600 font-bold hover:underline text-sm">+ Ajouter une
                                    première intervention</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>