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
                        <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Retour
                </a>

                <a href="{{ route('vehicles.edit', $vehicle) }}"
                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-lg text-xs font-medium text-white hover:bg-indigo-700 transition shadow-sm shadow-indigo-100">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Modifier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- COLONNE GAUCHE : INFOS VEHICULE --}}
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl p-6 border border-gray-100">
                        <div class="flex items-center justify-center h-32 w-full bg-gray-50 rounded-lg mb-4 border-2 border-dashed border-gray-200">
                            <svg class="h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            </svg>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Informations</h3>

                        <div class="space-y-4 text-sm">
                            <div>
                                <p class="text-gray-500 uppercase text-xs font-bold tracking-wider">Marque & Modèle</p>
                                <p class="font-bold text-gray-800 text-base">{{ $vehicle->marque }} {{ $vehicle->modele }}</p>
                            </div>

                            <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100">
                                <p class="text-indigo-600 uppercase text-[10px] font-black tracking-widest">Kilométrage actuel</p>
                                <div class="flex items-baseline">
                                    <p class="text-xl font-black text-indigo-900 font-mono">
                                        {{ number_format($vehicle->kilometrage, 0, ',', ' ') }}
                                    </p>
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
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black uppercase tracking-tighter {{ $statusClasses }}">
                                    {{ ucfirst(str_replace('_', ' ', $vehicle->statut)) }}
                                </span>
                            </div>

                            <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center shadow-sm">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Chauffeur assigné</h4>
                                    <p class="text-sm font-bold {{ $vehicle->driver ? 'text-indigo-600' : 'text-gray-500 italic' }}">
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

                {{-- COLONNE DROITE : BLOCS HISTORIQUES --}}
                <div class="md:col-span-2 space-y-6">
                    
                    {{-- BLOC 1 : HISTORIQUE DES MAINTENANCES --}}
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100" x-data="{ showForm: false }">
    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">Historique des Maintenances</h3>
        <div class="flex items-center space-x-2">
            <a href="{{ route('maintenances.index', ['vehicle_id' => $vehicle->id]) }}" 
               class="inline-flex items-center px-3 py-1.5 border border-gray-200 text-xs font-bold rounded-lg text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
                VOIR TOUT
            </a>
            
            <button @click="showForm = true" x-show="!showForm"
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
                AJOUTER
            </button>
        </div>
    </div>

    <div class="p-6">
        {{-- Formulaire Ajout Maintenance --}}
        <div x-show="showForm" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-200">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-md font-bold text-gray-800 uppercase tracking-wider">Nouvelle intervention</h3>
                <button @click="showForm = false" class="text-gray-400 hover:text-gray-600 text-xs font-bold">FERMER</button>
            </div>

            <form action="{{ route('maintenances.store') }}" method="POST">
                @csrf
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm font-medium">
                    <div class="font-bold mb-1">Veuillez corriger les erreurs suivantes :</div>
                    <ul class="list-disc list-inside text-xs">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Type d'acte</label>
                        <select name="type_entretien" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('type_entretien') ? 'border-red-500' : '' }}">
                            <option value="vidange" {{ old('type_entretien') == 'vidange' ? 'selected' : '' }}>Vidange</option>
                            <option value="pneus" {{ old('type_entretien') == 'pneus' ? 'selected' : '' }}>Pneumatiques</option>
                            <option value="freins" {{ old('type_entretien') == 'freins' ? 'selected' : '' }}>Système de freinage</option>
                            <option value="courroie" {{ old('type_entretien') == 'courroie' ? 'selected' : '' }}>Courroie de distribution</option>
                            <option value="autre" {{ old('type_entretien') == 'autre' ? 'selected' : '' }}>Autre intervention</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Date d'intervention</label>
                        <input type="date" name="date_maintenance" value="{{ old('date_maintenance', date('Y-m-d')) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('date_maintenance') ? 'border-red-500' : '' }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">KM au moment de l'acte</label>
                        <input type="number" name="kilometrage" value="{{ old('kilometrage') }}" placeholder="Ex: 125000"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('kilometrage') ? 'border-red-500' : '' }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Coût total (€)</label>
                        <input type="number" step="0.01" name="cout" value="{{ old('cout') }}" placeholder="0.00"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('cout') ? 'border-red-500' : '' }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Notes détaillées</label>
                        <textarea name="description" rows="3" placeholder="Détails des pièces changées..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('description') ? 'border-red-500' : '' }}">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-md text-sm font-bold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Enregistrer l'intervention
                    </button>
                </div>
            </form>
        </div>

        {{-- Liste des Maintenances limitée aux 3 dernières --}}
        @if(isset($vehicle->maintenances) && $vehicle->maintenances->count() > 0)
        <div class="flow-root">
            <ul role="list" class="-mb-8">
                {{-- L'ajout de ->take(3) permet de ne récupérer que les 3 éléments les plus récents --}}
                @foreach($vehicle->maintenances->sortByDesc('date_intervention')->take(3) as $maintenance)
                <li>
                    <div class="relative pb-8">
                        @if (!$loop->last)
                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        @if ($loop->iteration === 3 && $vehicle->maintenances->count() > 3)
                            <span class="absolute bottom-0 left-4 -ml-px h-8 w-0.5 bg-gradient-to-b from-gray-200 to-transparent" aria-hidden="true"></span>
                        @endif
                        @endif
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full {{ $maintenance->type == 'vidange' ? 'bg-indigo-500' : 'bg-fuchsia-500' }} flex items-center justify-center ring-8 ring-white text-white">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ ucfirst($maintenance->type) }}
                                        @if($maintenance->cout)
                                        <span class="ml-2 text-gray-500 font-normal">({{ number_format($maintenance->cout, 2, ',', ' ') }} €)</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $maintenance->notes ?? 'Aucune note.' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ number_format($maintenance->kilometrage_au_moment_de_l_acte, 0, ',', ' ') }} km</p>
                                </div>
                                <div class="whitespace-nowrap text-right text-sm text-gray-400">
                                    <time>{{ \Carbon\Carbon::parse($maintenance->date_intervention)->format('d M Y') }}</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        
        {{-- Lien discret en bas si le nombre total dépasse la limite --}}
        @if($vehicle->maintenances->count() > 3)
            <div class="mt-6 text-center">
                <a href="{{ route('maintenances.index', ['vehicle_id' => $vehicle->id]) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-500 transition">
                    Afficher les {{ $vehicle->maintenances->count() - 3 }} autres interventions →
                </a>
            </div>
        @endif

        @else
        <div x-show="!showForm" class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <p class="text-gray-500 italic">Aucun historique de maintenance.</p>
            <button @click="showForm = true" class="mt-4 text-indigo-600 font-bold hover:underline text-sm">+ Ajouter une première intervention</button>
        </div>
        @endif
    </div>
</div>

                    {{-- BLOC 2 : HISTORIQUE DES MOUVEMENTS --}}
                   <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100" x-data="{ showForm: false }">
    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">Historique des mouvements</h3>
        <div class="flex items-center space-x-2">
            <a href="{{ route('bookings.index', ['vehicle_id' => $vehicle->id]) }}" 
               class="inline-flex items-center px-3 py-1.5 border border-gray-200 text-xs font-bold rounded-lg text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
                VOIR TOUT
            </a>

            <button @click="showForm = true" x-show="!showForm"
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
                AJOUTER
            </button>
        </div>
    </div>

    <div class="p-6">
        {{-- Formulaire Ajout Déplacement --}}
        <div x-show="showForm" x-transition class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
            <div class="flex justify-between mb-6">
                <h3 class="font-bold text-gray-800 uppercase">Nouveau déplacement</h3>
                <button @click="showForm = false" class="text-gray-400 text-xs font-bold">FERMER</button>
            </div>

            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Date départ</label>
                        <input type="date" name="date_depart" value="{{ old('date_depart', date('Y-m-d')) }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Date retour</label>
                        <input type="date" name="date_retour" value="{{ old('date_retour') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Chauffeur</label>
                        <select name="driver_id" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Sélectionner un chauffeur --</option>
                            @if(isset($drivers))
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->full_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Destination</label>
                        <input type="text" name="destination" value="{{ old('destination') }}" placeholder="Ex: Antananarivo - Toamasina" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Kilométrage départ</label>
                        <input type="number" name="kilometrage_depart" value="{{ old('kilometrage_depart', $vehicle->kilometrage) }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Kilométrage arrivée</label>
                        <input type="number" name="kilometrage_arrivee" value="{{ old('kilometrage_arrivee') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Carburant consommé (L)</label>
                        <input type="number" step="0.01" name="carburant" value="{{ old('carburant') }}" placeholder="Ex: 45" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Durée (heure)</label>
                        <input type="number" step="0.1" name="duree" value="{{ old('duree') }}" placeholder="Ex: 5.5" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Observation</label>
                        <textarea name="notes" rows="3" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Détails du trajet...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="mt-6 w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-bold transition shadow-md">
                    Enregistrer le mouvement
                </button>
            </form>
        </div>

        {{-- Liste des Mouvements limitée aux 3 derniers --}}
        @if(isset($vehicle->bookings) && $vehicle->bookings->count())
        <ul class="space-y-6">
            {{-- Utilisation de ->take(3) pour n'afficher que les 3 déplacements les plus récents --}}
            @foreach($vehicle->bookings->sortByDesc('date_depart')->take(3) as $movement)
            <li class="border-l-4 border-indigo-500 pl-5">
                <div class="flex justify-between">
                    <div>
                        <p class="font-bold text-gray-900">🚗 {{ $movement->destination }}</p>
                        <p class="text-sm text-gray-600">👤 {{ $movement->driver->full_name ?? '-' }}</p>
                        <p class="text-sm text-gray-600">
                            📏 {{ number_format($movement->kilometrage_depart, 0, ',', ' ') }} km → {{ number_format($movement->kilometrage_arrivee, 0, ',', ' ') }} km
                            @if($movement->kilometrage_arrivee && $movement->kilometrage_depart)
                                <span class="font-semibold text-indigo-600">({{ number_format($movement->kilometrage_arrivee - $movement->kilometrage_depart, 0, ',', ' ') }} km)</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-600">
                            ⛽ {{ $movement->carburant ?? '0' }} L | ⏱ {{ $movement->duree ?? '0' }} h
                        </p>
                        @if($movement->notes)
                            <p class="text-sm text-gray-500 italic mt-1">« {{ $movement->notes }} »</p>
                        @endif
                    </div>
                    <div class="text-xs text-gray-400 font-medium">
                        {{ \Carbon\Carbon::parse($movement->date_depart)->format('d M Y') }}
                    </div>
                </div>
            </li>
            @endforeach
        </ul>

        {{-- Lien vers la page complète s'il y a plus de 3 éléments --}}
        @if($vehicle->bookings->count() > 3)
            <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                <a href="{{ route('bookings.index', ['vehicle_id' => $vehicle->id]) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-500 transition">
                    Afficher les {{ $vehicle->bookings->count() - 3 }} autres déplacements →
                </a>
            </div>
        @endif

        @else
        <p class="text-center text-gray-400 py-10 italic">Aucun mouvement enregistré.</p>
        @endif
    </div>
</div>

                </div>{{-- FIN COLONNE DROITE --}}

            </div>
        </div>
    </div>
</x-app-layout>