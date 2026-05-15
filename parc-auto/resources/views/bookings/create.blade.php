<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-2xl sm:rounded-2xl border border-gray-100">
                <h3 class="text-xl font-black text-gray-900 mb-8 uppercase tracking-tighter">Initialiser une mission</h3>
                
                <form action="{{ route('bookings.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Véhicule Disponible</label>
                        <select name="vehicle_id" class="mt-1 block w-full rounded-lg border-gray-300 text-sm">
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->immatriculation }} - {{ $vehicle->marque }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Chauffeur</label>
                        <select name="driver_id" class="mt-1 block w-full rounded-lg border-gray-300 text-sm">
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Destination</label>
                        <input type="text" name="destination" placeholder="ex: Livraison Client Lyon" class="mt-1 block w-full rounded-lg border-gray-300 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Date Départ</label>
                        <input type="datetime-local" name="date_depart" class="mt-1 block w-full rounded-lg border-gray-300 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Date Retour Prévue</label>
                        <input type="datetime-local" name="date_retour_prevue" class="mt-1 block w-full rounded-lg border-gray-300 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">KM Départ</label>
                        <input type="number" name="km_depart" class="mt-1 block w-full rounded-lg border-gray-300 text-sm">
                    </div>

                    <div class="md:col-span-2 pt-6 flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition">
                            Lancer la mission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>