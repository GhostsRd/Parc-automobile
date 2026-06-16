<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-tight">
                    Portefeuille <span class="text-indigo-600">Administratif</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1 font-medium italic">Suivi de validité des papiers, assurances et taxes des véhicules</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if($vehicles->count() > 0)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-sm font-black text-gray-700 uppercase tracking-wider">Statut des pièces par véhicule</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/30 border-b border-gray-100">
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest w-1/4">Véhicule</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Assurance & Technique</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Licences & Taxes</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Cartes Administratives</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-right">Action rapide</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($vehicles as $vehicle)
                                <tr class="hover:bg-indigo-50/10 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-mono font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded border border-indigo-100 self-start">
                                                {{ $vehicle->matricule }}
                                            </span>
                                            <span class="text-xs font-bold text-gray-800 mt-1">{{ $vehicle->brand }} {{ $vehicle->model }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 space-y-3">
                                        @include('documents.partials.doc-badge', ['vehicle' => $vehicle, 'type' => 'assurance', 'label' => 'Assurance'])
                                        @include('documents.partials.doc-badge', ['vehicle' => $vehicle, 'type' => 'visite_technique', 'label' => 'Visite Tech.'])
                                    </td>

                                    <td class="px-6 py-4 space-y-3">
                                        @include('documents.partials.doc-badge', ['vehicle' => $vehicle, 'type' => 'licence', 'label' => 'Licence'])
                                        @include('documents.partials.doc-badge', ['vehicle' => $vehicle, 'type' => 'patente', 'label' => 'Patente'])
                                    </td>

                                    <td class="px-6 py-4 space-y-3">
                                        @include('documents.partials.doc-badge', ['vehicle' => $vehicle, 'type' => 'carte_grise', 'label' => 'Carte Grise'])
                                        @include('documents.partials.doc-badge', ['vehicle' => $vehicle, 'type' => 'carte_automobile', 'label' => 'Carte Auto'])
                                    </td>

                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <button onclick="openDocModal({{ $vehicle->id }}, '{{ $vehicle->matricule }}')"
                                            class="text-xs font-bold bg-white hover:bg-indigo-600 hover:text-white text-indigo-600 border border-indigo-200 px-3 py-1.5 rounded-lg shadow-sm transition">
                                            Mettre à jour un papier
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white shadow-xl sm:rounded-xl border border-gray-100 p-12 text-center max-w-2xl mx-auto">
                    <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-indigo-100">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Aucun véhicule trouvé</h3>
                    <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">
                        Le portefeuille administratif est vide car aucun véhicule n'est encore enregistré dans votre parc automobile.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('vehicles.create') }}" 
                            class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-bold text-sm shadow-lg shadow-indigo-200 transition duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Créer un premier véhicule
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <div id="documentModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-100 transform transition-all">
            <div class="bg-indigo-600 px-6 py-4 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Mettre à jour un document</h3>
                <button onclick="closeDocModal()" class="text-white hover:text-indigo-200 text-xl font-bold">&times;</button>
            </div>
            
            <form action="{{ route('documents.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="vehicle_id" id="modal_vehicle_id">

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Véhicule ciblé</label>
                    <input type="text" id="modal_vehicle_name" readonly class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-lg font-mono font-bold text-gray-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Type de document</label>
                    <select name="type" required class="mt-1 block w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="assurance">Assurance</option>
                        <option value="visite_technique">Visite Technique</option>
                        <option value="licence">Licence</option>
                        <option value="carte_grise">Carte Grise</option>
                        <option value="patente">Patente</option>
                        <option value="carte_automobile">Carte Automobile</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Numéro du document</label>
                    <input type="text" name="document_number" required placeholder="Ex: AX-457-895" class="mt-1 block w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Délivré le</label>
                        <input type="date" name="issued_at" required class="mt-1 block w-full border-gray-300 rounded-lg text-xs focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Expire le (Optionnel)</label>
                        <input type="date" name="expires_at" class="mt-1 block w-full border-gray-300 rounded-lg text-xs focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end space-x-2">
                    <button type="button" onclick="closeDocModal()" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700">Annuler</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-xs font-bold transition shadow-md shadow-indigo-100">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDocModal(vehicleId, vehicleMatricule) {
            document.getElementById('modal_vehicle_id').value = vehicleId;
            document.getElementById('modal_vehicle_name').value = vehicleMatricule;
            document.getElementById('documentModal').classList.remove('hidden');
        }
        function closeDocModal() {
            document.getElementById('documentModal').classList.add('hidden');
        }
    </script>
</x-app-layout>