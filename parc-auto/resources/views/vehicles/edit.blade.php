<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                 <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                Modifier <span class="text-indigo-600">le véhicule</span>
            </h2>
                
                <p class="text-xs text-gray-500 font-medium mt-0.5">
                    Mettez à jour les spécifications, le kilométrage et l'état opérationnel.
                </p>
            </div>
            
            <a href="{{ route('vehicles.show', $vehicle) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Annuler
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <form action="{{ route('vehicles.update', $vehicle) }}" method="POST" class="bg-white shadow-xl sm:rounded-xl border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')

            @if ($errors->any())
            <div class="p-6 bg-red-50 border-b border-red-100 text-sm text-red-700">
                <div class="font-bold mb-2 flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Attention, les modifications n'ont pas pu être enregistrées :
                </div>
                <ul class="list-disc pl-7 space-y-1 font-medium">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="p-6 sm:p-8 space-y-8">
                
                <div>
                    <h3 class="text-xs font-black text-indigo-600 uppercase tracking-widest border-b pb-2 mb-4">Identification de la carte grise</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="immatriculation" value="Numéro d’immatriculation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <x-input id="immatriculation" name="immatriculation" type="text"
                                value="{{ old('immatriculation', $vehicle->immatriculation) }}"
                                class="w-full rounded-xl border-gray-200 text-sm uppercase font-bold text-gray-800 {{ $errors->has('immatriculation') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                placeholder="AA-123-BB" required />
                            @error('immatriculation')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="marque" value="Marque" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <x-input id="marque" name="marque" type="text" class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-800"
                                value="{{ old('marque', $vehicle->marque) }}" placeholder="ex: Peugeot" required />
                        </div>

                        <div>
                            <x-label for="modele" value="Modèle" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <x-input id="modele" name="modele" type="text" class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-800"
                                value="{{ old('modele', $vehicle->modele) }}" placeholder="ex: 308" required />
                        </div>

                        <div>
                            <x-label for="annee" value="Année de mise en circulation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <x-input id="annee" name="annee" type="number" class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-800"
                                value="{{ old('annee', $vehicle->annee) }}" placeholder="ex: 2024" />
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-black text-indigo-600 uppercase tracking-widest border-b pb-2 mb-4">Spécifications techniques</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="numero_chassis" value="Numéro de châssis (VIN)" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <x-input id="numero_chassis" name="numero_chassis" type="text"
                                value="{{ old('numero_chassis', $vehicle->numero_chassis) }}"
                                class="w-full rounded-xl border-gray-200 text-sm font-mono text-gray-800 {{ $errors->has('numero_chassis') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                placeholder="Code VIN" />
                            @error('numero_chassis')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="numero_moteur" value="Numéro moteur" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <x-input id="numero_moteur" name="numero_moteur" type="text" class="w-full rounded-xl border-gray-200 text-sm font-mono text-gray-800"
                                value="{{ old('numero_moteur', $vehicle->numero_moteur) }}" placeholder="Numéro bloc moteur" />
                        </div>

                        <div>
                            <x-label for="type_carburant" value="Type de carburant" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <select name="type_carburant" id="type_carburant"
                                class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
                                <option value="">-- Choisir --</option>
                                <option value="diesel" {{ old('type_carburant', $vehicle->type_carburant) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="essence" {{ old('type_carburant', $vehicle->type_carburant) == 'essence' ? 'selected' : '' }}>Essence</option>
                                <option value="electrique" {{ old('type_carburant', $vehicle->type_carburant) == 'electrique' ? 'selected' : '' }}>Électrique</option>
                            </select>
                        </div>

                        <div>
                            <x-label for="capacite_reservoir" value="Capacité du réservoir (L)" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <x-input id="capacite_reservoir" name="capacite_reservoir" type="number" step="0.01"
                                value="{{ old('capacite_reservoir', $vehicle->capacite_reservoir) }}" class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-800"
                                placeholder="ex: 60" />
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-black text-indigo-600 uppercase tracking-widest border-b pb-2 mb-4">Kilométrage</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="kilometrage_initial" value="Kilométrage initial" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <x-input id="kilometrage_initial" name="kilometrage_initial" type="number"
                                value="{{ old('kilometrage_initial', $vehicle->kilometrage_initial ?? '0') }}" class="w-full rounded-xl border-gray-200 text-sm font-mono font-bold text-gray-800" required />
                        </div>

                        <div>
                            <x-label for="kilometrage_actuel" value="Kilométrage actuel" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <x-input id="kilometrage_actuel" name="kilometrage_actuel" type="number"
                                value="{{ old('kilometrage_actuel', $vehicle->kilometrage_actuel ?? '0') }}" class="w-full rounded-xl border-gray-200 text-sm font-mono font-bold text-gray-800" required />
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-black text-indigo-600 uppercase tracking-widest border-b pb-2 mb-4">Affectation & Statut</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-label for="zone_affectation" value="Zone d’affectation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <select name="zone_affectation" id="zone_affectation"
                                class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
                                <option value="">-- Choisir --</option>
                                <option value="urbaine" {{ old('zone_affectation', $vehicle->zone_affectation) == 'urbaine' ? 'selected' : '' }}>Urbaine</option>
                                <option value="regionale" {{ old('zone_affectation', $vehicle->zone_affectation) == 'regionale' ? 'selected' : '' }}>Régionale</option>
                                <option value="nationale" {{ old('zone_affectation', $vehicle->zone_affectation) == 'nationale' ? 'selected' : '' }}>Nationale</option>
                            </select>
                        </div>

                        <div>
                            <x-label for="driver_id" value="Chauffeur titulaire" class="block text-xs font-bold text-indigo-600 uppercase tracking-wider mb-2" />
                            <select name="driver_id" id="driver_id"
                                class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
                                <option value="">-- Aucun chauffeur assigné --</option>
                                @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver_id', $vehicle->driver_id) == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->full_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-label for="statut" value="Statut du véhicule" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" />
                            <select name="statut" id="statut"
                                class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
                                <option value="actif" {{ old('statut', $vehicle->statut) == 'actif' ? 'selected' : '' }}>✅ Actif</option>
                                <option value="en_maintenance" {{ old('statut', $vehicle->statut) == 'en_maintenance' ? 'selected' : '' }}>🛠️ En maintenance</option>
                                <option value="hors_service" {{ old('statut', $vehicle->statut) == 'hors_service' ? 'selected' : '' }}>⛔ Hors service</option>
                                <option value="en_mission" {{ old('statut', $vehicle->statut) == 'en_mission' ? 'selected' : '' }}>🚗 En mission</option>
                                <option value="en_reservation" {{ old('statut', $vehicle->statut) == 'en_reservation' ? 'selected' : '' }}>📅 En réservation</option>
                                <option value="immobilise" {{ old('statut', $vehicle->statut) == 'immobilise' ? 'selected' : '' }}>🔒 Immobilisé</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('vehicles.show', $vehicle) }}" class="text-sm text-gray-600 hover:text-gray-900 font-bold uppercase tracking-wider px-4 py-2 transition">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mettre à jour le véhicule
                </button>
            </div>
        </form>
    </div>
</x-app-layout>