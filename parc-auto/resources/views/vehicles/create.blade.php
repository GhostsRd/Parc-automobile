<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajouter un Véhicule</h2>
    </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-8 shadow-xl sm:rounded-xl border border-gray-100">
            <form action="{{ route('vehicles.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-label for="immatriculation" value="Immatriculation" class="font-bold text-gray-700" />
                        <x-input id="immatriculation" name="immatriculation" type="text" class="mt-1 block w-full uppercase" placeholder="AA-123-BB" required />
                    </div>

                    <div>
                        <x-label for="marque" value="Marque" class="font-bold text-gray-700" />
                        <x-input id="marque" name="marque" type="text" class="mt-1 block w-full" placeholder="ex: Peugeot" required />
                    </div>

                    <div>
                        <x-label for="modele" value="Modèle" class="font-bold text-gray-700" />
                        <x-input id="modele" name="modele" type="text" class="mt-1 block w-full" placeholder="ex: 308" required />
                    </div>

                    <div>
                        <x-label for="kilometrage_actuel" value="Kilométrage Actuel" class="font-bold text-gray-700" />
                        <x-input id="kilometrage_actuel" name="kilometrage_actuel" type="number" class="mt-1 block w-full" value="0" required />
                    </div>

                    <div>
                        <x-label for="driver_id" value="Chauffeur Titulaire (Optionnel)" class="font-bold text-indigo-600" />
                        <select name="driver_id" id="driver_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                            <option value="">-- Aucun chauffeur assigné --</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="statut" value="Statut du véhicule" class="font-bold text-gray-700" />
                        <select name="statut" id="statut" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                            <option value="disponible">✅ Disponible</option>
                            <option value="en_reparation">🛠️ En réparation</option>
                            <option value="en_mission">🚀 En mission</option>
                            <option value="immobilise">⛔ Immobilisé</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('vehicles.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4 font-medium">Annuler</a>
                    <x-button class="bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100">
                        Créer le véhicule
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>