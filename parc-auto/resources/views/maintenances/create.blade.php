<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Enregistrer une <span class="text-indigo-600">Maintenance</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-xl p-8 border border-gray-100">
                <form action="{{ route('maintenances.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Véhicule concerné</label>
                        <select name="vehicle_id" id="vehicleSelect" required 
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-bold text-gray-700">
                            <option value="">-- Choisir le véhicule --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" data-km="{{ $vehicle->kilometrage_actuel }}">
                                    {{ $vehicle->immatriculation }} - {{ $vehicle->marque }} ({{ number_format($vehicle->kilometrage_actuel, 0, ',', ' ') }} km)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Type d'acte</label>
                            <select name="type_entretien" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-fuchsia-500 focus:border-fuchsia-500">
                                <option value="vidange">Vidange</option>
                                <option value="pneus">Pneumatiques</option>
                                <option value="freins">Système de freinage</option>
                                <option value="courroie">Courroie de distribution</option>
                                <option value="autre">Autre intervention</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Date de l'acte</label>
                            <input type="date" name="date_maintenance" value="{{ date('Y-m-d') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Kilométrage (KM)</label>
                            <input type="number" name="kilometrage" id="kmInput" required readonly
                                placeholder="Sélectionnez un véhicule..."
                                class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500 cursor-not-allowed shadow-sm focus:ring-0 focus:border-gray-300 font-mono font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Coût TTC (€)</label>
                            <input type="number" step="0.01" name="cout" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Notes & Observations</label>
                        <textarea name="description" rows="3" placeholder="Détails de l'intervention..."
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3">
                        <a href="{{ route('maintenances.index') }}" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition">Annuler</a>
                        <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-lg shadow-indigo-200 transition">
                            Enregistrer l'intervention
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const vehicleSelect = document.getElementById('vehicleSelect');
            const kmInput = document.getElementById('kmInput');

            vehicleSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const currentKm = selectedOption.getAttribute('data-km');

                if (currentKm) {
                    kmInput.value = currentKm;
                } else {
                    kmInput.value = '';
                }
            });
        });
    </script>
</x-app-layout>