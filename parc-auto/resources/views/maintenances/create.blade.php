<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Enregistrer une intervention</h3>
    
    <form action="{{ route('maintenances.store') }}" method="POST">
        @csrf
        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Type d'acte</label>
                <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-fuchsia-500 focus:border-fuchsia-500">
                    <option value="vidange">Vidange</option>
                    <option value="pneus">Pneumatiques</option>
                    <option value="freins">Système de freinage</option>
                    <option value="courroie">Courroie de distribution</option>
                    <option value="autre">Autre intervention</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Date d'intervention</label>
                <input type="date" name="date_intervention" value="{{ date('Y-m-d') }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-fuchsia-500 focus:border-fuchsia-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">KM au moment de l'acte</label>
                <input type="number" name="kilometrage_au_moment_de_l_acte" placeholder="Ex: 125000"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-fuchsia-500 focus:border-fuchsia-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase text-fuchsia-600">Prochain rappel (KM)</label>
                <input type="number" name="prochain_kilometrage_rappel" placeholder="Ex: 140000"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border-fuchsia-200 focus:ring-fuchsia-500 focus:border-fuchsia-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Coût total (HT/TTC)</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="number" step="0.01" name="cout" 
                        class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:ring-fuchsia-500 focus:border-fuchsia-500" placeholder="0.00">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">€</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-xs font-bold text-gray-500 uppercase">Notes détaillées</label>
            <textarea name="notes" rows="3" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-fuchsia-500 focus:border-fuchsia-500" 
                placeholder="Détails des pièces changées..."></textarea>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-fuchsia-600 hover:bg-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-fuchsia-500 transition">
                Enregistrer l'intervention
            </button>
        </div>
    </form>
</div>