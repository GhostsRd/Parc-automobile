<x-app-layout>
     <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            Creer <span class="text-indigo-600"> une mission</span>
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-2xl sm:rounded-2xl border border-gray-100">
                <h3 class="text-xl font-black text-gray-900 mb-8 uppercase tracking-tighter">Initialiser une mission
                </h3>

                {{-- Bloc d'erreurs général --}}
                @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
                    <div class="font-bold mb-1">Veuillez corriger les erreurs de saisie.</div>
                </div>
                @endif

                <form action="{{ route('bookings.store') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Véhicule Disponible</label>
                        <select id="vehicleSelect" name="vehicle_id"
                            class="mt-1 block w-full rounded-lg text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('vehicle_id') ? 'border-red-500 ring-1 ring-red-500' : '' }}">
                            <option value="" data-km="">-- Sélectionner un véhicule --</option>
                            @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}"
                                data-km="{{ $vehicle->kilometrage_actuel ?? $vehicle->kilometrage ?? 0 }}" {{
                                old('vehicle_id')==$vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->immatriculation }} - {{ $vehicle->marque }} ({{
                                number_format($vehicle->kilometrage_actuel ?? $vehicle->kilometrage ?? 0, 0, ',', ' ')
                                }} km)
                            </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                        <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Chauffeur</label>
                        <select name="driver_id"
                            class="mt-1 block w-full rounded-lg text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('driver_id') ? 'border-red-500' : '' }}">
                            <option value="">-- Sélectionner un chauffeur --</option>
                            @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id')==$driver->id ? 'selected' : '' }}>
                                {{ $driver->full_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('driver_id')
                        <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 space-y-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Type de
                            Destination</label>

                        <div class="flex bg-gray-100 p-1 rounded-xl w-fit border border-gray-200/60">
                            <button type="button" id="btnModeCircuit"
                                class="px-4 py-2 text-xs font-black uppercase rounded-lg transition-all shadow-sm bg-white text-indigo-600">
                                🔄 Trajet / Circuit
                            </button>
                            <button type="button" id="btnModeSimple"
                                class="px-4 py-2 text-xs font-bold uppercase rounded-lg transition-all text-gray-500 hover:text-gray-700">
                                📍 Saisie Simple (Livraison, etc.)
                            </button>
                        </div>

                        <div id="wrapperCircuit" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Lieu
                                    de départ</label>
                                <input type="text" id="routeDepart" list="villesSuggestions" placeholder="Ex: Manakara"
                                    class="mt-1 block w-full rounded-lg text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Lieu
                                    d'arrivée</label>
                                <input type="text" id="routeArrivee" list="villesSuggestions"
                                    placeholder="Ex: Fianarantsoa"
                                    class="mt-1 block w-full rounded-lg text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm">
                            </div>
                        </div>

                        <datalist id="villesSuggestions">
                            <option value="Manakara">
                            <option value="Fianarantsoa">
                            <option value="Mananjary">
                            <option value="Antananarivo">
                            <option value="Antsirabe">
                            <option value="Ambositra">
                            <option value="Toliara">
                            <option value="Toamasina">
                            <option value="Mahajanga">
                            <option value="Farafangana">
                        </datalist>

                        <div id="wrapperSimple" class="hidden">
                            <label
                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Description
                                de la destination</label>
                            <input type="text" id="destinationSimple"
                                placeholder="Ex: Livraison Client local ou Course administrative"
                                class="mt-1 block w-full rounded-lg text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm">
                        </div>

                        <input type="hidden" id="finalDestination" name="destination" value="{{ old('destination') }}">

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
                        <input type="number" id="kmDepartInput" name="km_depart" value="{{ old('km_depart') }}"
                            placeholder="Sélectionnez un véhicule..."
                            class="mt-1 block w-full rounded-lg text-sm border-gray-300 bg-gray-50 font-mono font-bold focus:ring-indigo-500 focus:border-indigo-500 {{ $errors->has('km_depart') ? 'border-red-500' : '' }}">
                        @error('km_depart')
                        <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 pt-6 flex justify-end">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                            Lancer la mission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const vehicleSelect = document.getElementById('vehicleSelect');
            const kmDepartInput = document.getElementById('kmDepartInput');

            function updateKm() {
                // Récupère l'option actuellement sélectionnée
                const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
                // Extrait la valeur de l'attribut data-km
                const currentKm = selectedOption.getAttribute('data-km');
                
                if (currentKm) {
                    kmDepartInput.value = currentKm;
                } else {
                    kmDepartInput.value = '';
                }
            }

            // Écoute les changements de sélection sur le véhicule
            vehicleSelect.addEventListener('change', updateKm);

            // Exécute la fonction au chargement initial au cas où il y a un retour d'erreur de formulaire (old)
            if(vehicleSelect.value) {
                updateKm();
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const btnCircuit = document.getElementById('btnModeCircuit');
        const btnSimple = document.getElementById('btnModeSimple');
        
        const wrapperCircuit = document.getElementById('wrapperCircuit');
        const wrapperSimple = document.getElementById('wrapperSimple');
        
        const routeDepart = document.getElementById('routeDepart');
        const routeArrivee = document.getElementById('routeArrivee');
        const destinationSimple = document.getElementById('destinationSimple');
        
        const finalDestination = document.getElementById('finalDestination');
        let currentMode = 'circuit'; // Mode par défaut

        // Fonction principale d'uniformisation du texte final
        function syncDestination() {
            if (currentMode === 'circuit') {
                const dep = routeDepart.value.trim();
                const arr = routeArrivee.value.trim();
                
                if (dep && arr) {
                    // Met la première lettre en majuscule et applique le séparateur strict " - "
                    const formatDep = dep.charAt(0).toUpperCase() + dep.slice(1);
                    const formatArr = arr.charAt(0).toUpperCase() + arr.slice(1);
                    finalDestination.value = `${formatDep} - ${formatArr}`;
                } else {
                    finalDestination.value = dep || arr || ''; 
                }
            } else {
                finalDestination.value = destinationSimple.value.trim();
            }
        }

        // Basculer vers l'onglet Circuit
        btnCircuit.addEventListener('click', () => {
            currentMode = 'circuit';
            // Gestion visuelle des onglets
            btnCircuit.className = "px-4 py-2 text-xs font-black uppercase rounded-lg transition-all shadow-sm bg-white text-indigo-600";
            btnSimple.className = "px-4 py-2 text-xs font-bold uppercase rounded-lg transition-all text-gray-500 hover:text-gray-700";
            
            // Affichage des blocs
            wrapperCircuit.classList.remove('hidden');
            wrapperSimple.classList.add('hidden');
            syncDestination();
        });

        // Basculer vers l'onglet Simple
        btnSimple.addEventListener('click', () => {
            currentMode = 'simple';
            // Gestion visuelle des onglets
            btnSimple.className = "px-4 py-2 text-xs font-black uppercase rounded-lg transition-all shadow-sm bg-white text-indigo-600";
            btnCircuit.className = "px-4 py-2 text-xs font-bold uppercase rounded-lg transition-all text-gray-500 hover:text-gray-700";
            
            // Affichage des blocs
            wrapperSimple.classList.remove('hidden');
            wrapperCircuit.classList.add('hidden');
            syncDestination();
        });

        // Écouter les saisies en temps réel pour fabriquer la chaîne propre
        routeDepart.addEventListener('input', syncDestination);
        routeArrivee.addEventListener('input', syncDestination);
        destinationSimple.addEventListener('input', syncDestination);

        // Au cas où il y a un retour d'erreur (old value), on pré-remplit la vue
        if (finalDestination.value) {
            if (finalDestination.value.includes(' - ')) {
                const parts = finalDestination.value.split(' - ');
                routeDepart.value = parts[0] || '';
                routeArrivee.value = parts[1] || '';
                btnCircuit.click();
            } else {
                destinationSimple.value = finalDestination.value;
                btnSimple.click();
            }
        }
    });
    </script>
</x-app-layout>