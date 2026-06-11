<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-2xl sm:rounded-2xl border border-gray-100">
                <h3 class="text-xl font-black text-gray-900 mb-8 uppercase tracking-tighter">Initialiser une mission</h3>
                
                {{-- Bloc d'erreurs général (optionnel mais recommandé) --}}
                @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
                    <div class="font-bold mb-1">Veuillez corriger les erreurs de saisie.</div>
                </div>
                @endif

                <form action="{{ route('bookings.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Véhicule Disponible</label>
                        <select name="vehicle_id" class="mt-1 block w-full rounded-lg text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('vehicle_id') ? 'border-red-500 ring-1 ring-red-500' : '' }}">
                            <option value="">-- Sélectionner un véhicule --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->immatriculation }} - {{ $vehicle->marque }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Chauffeur</label>
                        <select name="driver_id" class="mt-1 block w-full rounded-lg text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('driver_id') ? 'border-red-500' : '' }}">
                            <option value="">-- Sélectionner un chauffeur --</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('driver_id')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Destination</label>
                        <input type="text" name="destination" value="{{ old('destination') }}" placeholder="ex: Livraison Client Lyon" 
                            class="mt-1 block w-full rounded-lg text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('destination') ? 'border-red-500' : '' }}">
                        @error('destination')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Date Départ</label>
                        <input type="datetime-local" name="date_depart" value="{{ old('date_depart') }}"
                            class="mt-1 block w-full rounded-lg text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('date_depart') ? 'border-red-500' : '' }}">
                        @error('date_depart')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Date Retour Prévue</label>
                        <input type="datetime-local" name="date_retour_prevue" value="{{ old('date_retour_prevue') }}"
                            class="mt-1 block w-full rounded-lg text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('date_retour_prevue') ? 'border-red-500' : '' }}">
                        @error('date_retour_prevue')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">KM Départ</label>
                        <input type="number" name="km_depart" value="{{ old('km_depart') }}" placeholder="Ex: 45200"
                            class="mt-1 block w-full rounded-lg text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('km_depart') ? 'border-red-500' : '' }}">
                        @error('km_depart')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 pt-6 flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                            Lancer la mission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>