<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajouter un Véhicule</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-xl sm:rounded-xl border border-gray-100">
                <form action="{{ route('vehicles.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm font-medium">
                        <div class="font-bold mb-1">Attention, l'enregistrement a échoué :</div>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <x-label for="immatriculation" value="Numéro d’immatriculation"
                                class="font-bold text-gray-700" />

                            <x-input id="immatriculation" name="immatriculation" type="text"
                                value="{{ old('immatriculation') }}"
                                class="mt-1 block w-full uppercase {{ $errors->has('immatriculation') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                placeholder="AA-123-BB" required />

                            @error('immatriculation')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="marque" value="Marque" class="font-bold text-gray-700" />
                            <x-input id="marque" name="marque" type="text" class="mt-1 block w-full"
                                value="{{ old('marque') }}" placeholder="ex: Peugeot" required />
                        </div>

                        <div>
                            <x-label for="modele" value="Modèle" class="font-bold text-gray-700" />
                            <x-input id="modele" name="modele" type="text" class="mt-1 block w-full"
                                value="{{ old('modele') }}" placeholder="ex: 308" required />
                        </div>

                        <div>
                            <x-label for="annee" value="Année" class="font-bold text-gray-700" />
                            <x-input id="annee" name="annee" type="number" class="mt-1 block w-full"
                                value="{{ old('annee') }}" placeholder="ex: 2024" />
                        </div>

                        <div>
                            <x-label for="numero_chassis" value="Numéro de châssis" class="font-bold text-gray-700" />

                            <x-input id="numero_chassis" name="numero_chassis" type="text"
                                value="{{ old('numero_chassis') }}"
                                class="mt-1 block w-full {{ $errors->has('numero_chassis') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                placeholder="VIN / Châssis" />

                            @error('numero_chassis')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="numero_moteur" value="Numéro moteur" class="font-bold text-gray-700" />
                            <x-input id="numero_moteur" name="numero_moteur" type="text" class="mt-1 block w-full"
                                value="{{ old('numero_moteur') }}" placeholder="Numéro moteur" />
                        </div>

                        <div>
                            <x-label for="type_carburant" value="Type de carburant" class="font-bold text-gray-700" />
                            <select name="type_carburant" id="type_carburant"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                <option value="">-- Choisir --</option>
                                <option value="diesel" {{ old('type_carburant')=='diesel' ? 'selected' : '' }}>Diesel
                                </option>
                                <option value="essence" {{ old('type_carburant')=='essence' ? 'selected' : '' }}>Essence
                                </option>
                                <option value="electrique" {{ old('type_carburant')=='electrique' ? 'selected' : '' }}>
                                    Electrique</option>
                            </select>
                        </div>

                        <div>
                            <x-label for="capacite_reservoir" value="Capacité du réservoir (L)"
                                class="font-bold text-gray-700" />
                            <x-input id="capacite_reservoir" name="capacite_reservoir" type="number" step="0.01"
                                value="{{ old('capacite_reservoir') }}" class="mt-1 block w-full"
                                placeholder="ex: 60" />
                        </div>

                        <div>
                            <x-label for="kilometrage_initial" value="Kilométrage initial"
                                class="font-bold text-gray-700" />
                            <x-input id="kilometrage_initial" name="kilometrage_initial" type="number"
                                value="{{ old('kilometrage_initial', '0') }}" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <x-label for="kilometrage_actuel" value="Kilométrage actuel"
                                class="font-bold text-gray-700" />
                            <x-input id="kilometrage_actuel" name="kilometrage_actuel" type="number"
                                value="{{ old('kilometrage_actuel', '0') }}" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <x-label for="zone_affectation" value="Zone d’affectation"
                                class="font-bold text-gray-700" />
                            <select name="zone_affectation" id="zone_affectation"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                <option value="">-- Choisir --</option>
                                <option value="urbaine" {{ old('zone_affectation')=='urbaine' ? 'selected' : '' }}>
                                    Urbaine</option>
                                <option value="regionale" {{ old('zone_affectation')=='regionale' ? 'selected' : '' }}>
                                    Régionale</option>
                                <option value="nationale" {{ old('zone_affectation')=='nationale' ? 'selected' : '' }}>
                                    Nationale</option>
                            </select>
                        </div>

                        <div>
                            <x-label for="driver_id" value="Chauffeur titulaire (Optionnel)"
                                class="font-bold text-indigo-600" />
                            <select name="driver_id" id="driver_id"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                <option value="">-- Aucun chauffeur assigné --</option>
                                @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver_id')==$driver->id ? 'selected' : '' }}>
                                    {{ $driver->full_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-label for="statut" value="Statut du véhicule" class="font-bold text-gray-700" />
                            <select name="statut" id="statut"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                <option value="actif" {{ old('statut', 'actif' )=='actif' ? 'selected' : '' }}>✅ Actif
                                </option>
                                <option value="en_maintenance" {{ old('statut')=='en_maintenance' ? 'selected' : '' }}>
                                    🛠️ En maintenance</option>
                                <option value="hors_service" {{ old('statut')=='hors_service' ? 'selected' : '' }}>⛔
                                    Hors service</option>

                                <option value="en_mission" {{ old('statut')=='en_mission' ? 'selected' : '' }}>🚗 En
                                    mission</option>
                                <option value="en_reservation" {{ old('statut')=='en_reservation' ? 'selected' : '' }}>
                                    📅 En réservation</option>
                                <option value="immobilise" {{ old('statut')=='immobilise' ? 'selected' : '' }}>🔒
                                    Immobilisé</option>
                            </select>
                        </div>

                    </div>

                    <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('vehicles.index') }}"
                            class="text-sm text-gray-600 hover:text-gray-900 mr-4 font-medium">
                            Annuler
                        </a>
                        <x-button class="bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100">
                            Créer le véhicule
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>