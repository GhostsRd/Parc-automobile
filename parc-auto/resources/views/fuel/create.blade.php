<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('fuel.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Enregistrer un Ravitaillement') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-md shadow-sm">
                    <p class="font-bold text-sm">{{ __('Veuillez corriger les erreurs suivantes :') }}</p>
                    <ul class="mt-2 list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100"
                 x-data="{ 
                    quantite: '{{ old('quantite_litres') }}', 
                    cout: '{{ old('cout_total') }}',
                    prixUnitaire: '{{ old('prix_unitaire', 0) }}',
                    kilometrage: '{{ old('kilometrage', 0) }}',
                    
                    calculerPrix() {
                        if (this.quantite > 0 && this.cout > 0) {
                            this.prixUnitaire = (this.cout / this.quantite).toFixed(3);
                        } else {
                            this.prixUnitaire = 0;
                        }
                    },
                    
                    chargerKilometrage(event) {
                        // On récupère l'option sélectionnée pour lire son attribut data-km
                        const selectedOption = event.target.options[event.target.selectedIndex];
                        this.kilometrage = selectedOption.getAttribute('data-km') || 0;
                    }
                 }">
                
                <form method="POST" action="{{ route('fuel.store') }}" class="p-8 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label for="vehicle_id" class="block text-xs font-bold uppercase text-gray-500 tracking-wider mb-2">{{ __('Véhicule ciblé') }} <span class="text-red-500">*</span></label>
                            <select id="vehicle_id" name="vehicle_id" required 
                                    @change="chargerKilometrage($event)" 
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                <option value="" disabled selected>{{ __('Choisir un véhicule...') }}</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" 
                                            data-km="{{ $vehicle->kilometrage_actuel ?? 0 }}"
                                            {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->marque }} {{ $vehicle->modele }} ({{ $vehicle->immatriculation }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="date_ravitaillement" class="block text-xs font-bold uppercase text-gray-500 tracking-wider mb-2">{{ __('Date du plein') }} <span class="text-red-500">*</span></label>
                            <input type="date" id="date_ravitaillement" name="date_ravitaillement" value="{{ old('date_ravitaillement', date('Y-m-d')) }}" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                        </div>

                        <div>
                            <label households for="kilometrage" class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-2">{{ __('Kilométrage actuel du véhicule (Auto)') }}</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" id="kilometrage" name="kilometrage" x-model="kilometrage" readonly required class="w-full bg-gray-100 border-gray-300 text-gray-500 rounded-md text-sm pe-12 font-mono select-none focus:ring-0 focus:border-gray-300">
                                <div class="absolute inset-y-0 right-0 flex items-center pe-3 pointer-events-none">
                                    <span class="text-gray-400 text-xs font-semibold">km</span>
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1">Basé sur le dernier compteur connu en base de données.</p>
                        </div>

                        <div>
                            <label for="station_service" class="block text-xs font-bold uppercase text-gray-500 tracking-wider mb-2">{{ __('Station-Service') }} <span class="text-red-500">*</span></label>
                            <input type="text" id="station_service" name="station_service" value="{{ old('station_service') }}" required placeholder="Ex: TotalEnergies Relais" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                        </div>

                    </div>

                    <hr class="border-gray-100">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                        
                        <div>
                            <label for="quantite_litres" class="block text-xs font-bold uppercase text-gray-500 tracking-wider mb-2">{{ __('Quantité (Litres)') }} <span class="text-red-500">*</span></label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" step="0.01" id="quantite_litres" name="quantite_litres" x-model="quantite" @input="calculerPrix()" required min="1" placeholder="0.00" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm pe-8 font-semibold">
                                <div class="absolute inset-y-0 right-0 flex items-center pe-3 pointer-events-none">
                                    <span class="text-gray-400 text-xs font-bold">L</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="cout_total" class="block text-xs font-bold uppercase text-gray-500 tracking-wider mb-2">{{ __('Coût Total (€)') }} <span class="text-red-500">*</span></label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" step="0.01" id="cout_total" name="cout_total" x-model="cout" @input="calculerPrix()" required min="1" placeholder="0.00" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm pe-8 font-bold text-gray-900">
                                <div class="absolute inset-y-0 right-0 flex items-center pe-3 pointer-events-none">
                                    <span class="text-gray-400 text-xs font-bold">€</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label households for="prix_unitaire" class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-2">{{ __('Prix indicatif au Litre') }}</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" step="0.001" id="prix_unitaire" name="prix_unitaire" x-model="prixUnitaire" required placeholder="0.000" class="w-full bg-gray-100 border-gray-300 text-gray-500 rounded-md text-sm pe-14 font-mono">
                                <div class="absolute inset-y-0 right-0 flex items-center pe-3 pointer-events-none">
                                    <span class="text-gray-400 text-[10px] font-bold">€/L</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold uppercase text-gray-500 tracking-wider mb-2">{{ __('Notes / Observations (Optionnel)') }}</label>
                        <textarea id="description" name="description" rows="3" placeholder="{{ __('Ajouter des détails...') }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('fuel.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Annuler') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wider shadow-sm hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Enregistrer le reçu') }}
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>