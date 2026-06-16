<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
            Suivi du <span class="text-indigo-600">Kilométrage & Trajets</span>
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @php $alertes = $vehicles->filter(fn($v) => $v->aBesoinMaintenance()); @endphp
            @if($alertes->count() > 0)
            <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl shadow-sm">
                <div class="flex items-center space-x-2">
                    <span class="text-amber-600 font-bold text-lg">⚠️</span>
                    <h4 class="text-sm font-black text-amber-800 uppercase tracking-wide">Seuil de révision atteint ({{
                        $alertes->count() }} véhicule(s))</h4>
                </div>
                <p class="text-xs text-amber-700 mt-1 font-medium">Les véhicules suivants ont parcouru plus de 10 000 km
                    et doivent passer à la vidange ou au contrôle technique :</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($alertes as $vAlert)
                    <span
                        class="bg-amber-100 text-amber-900 px-2 py-0.5 rounded text-xs font-mono font-bold border border-amber-200">
                        {{ $vAlert->immatriculation }} (Total: {{ number_format($vAlert->kilometrage_actuel, 0, ',', '
                        ') }} km)
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-black text-gray-700 uppercase tracking-wider">État des odomètres du parc
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/30 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Véhicule
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Compteur
                                    Général</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Dernier
                                    Trajet (Journalier)</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Dernier
                                    Relevé</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($vehicles as $vehicle)
                            {{-- On prend la mission la plus récente qui possède une destination --}}
                            @php
                            $derniereMission = $vehicle->bookings->sortByDesc('date_retour_reelle')->first();
                            @endphp

                            <tr class="hover:bg-indigo-50/10 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-mono font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded border border-indigo-100 self-start">
                                            {{ $vehicle->immatriculation }}
                                        </span>
                                        <span class="text-xs font-bold text-gray-800 mt-1">{{ $vehicle->marque }} {{
                                            $vehicle->modele }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 min-w-[200px]">
                                    <div class="flex justify-between items-baseline">
                                        <span class="text-base font-black text-gray-900 font-mono">
                                            {{ number_format($vehicle->kilometrage_actuel ?? 0, 0, ',', ' ') }}
                                        </span>
                                        <span class="text-xs text-gray-400 font-bold">KM</span>
                                    </div>

                                    {{-- Calcul du pourcentage d'usure avant vidange --}}
                                    @php
                                    $kmRoules = max(0, ($vehicle->kilometrage_actuel ?? 0) -
                                    ($vehicle->kilometrage_initial ?? 0));
                                    $pourcentage = min(100, round(($kmRoules / 10000) * 100));
                                    $barColor = $pourcentage >= 100 ? 'bg-red-500' : ($pourcentage >= 80 ?
                                    'bg-amber-500' : 'bg-emerald-500');
                                    @endphp

                                    <div
                                        class="w-full bg-gray-100 rounded-full h-1.5 mt-2 overflow-hidden border border-gray-200">
                                        <div class="{{ $barColor }} h-1.5 rounded-full transition-all duration-500"
                                            style="width: {{ $pourcentage }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[10px] text-gray-400 mt-1 font-bold">
                                        <span>Roulé: {{ number_format($kmRoules, 0, ',', ' ') }} km</span>
                                        <span
                                            class="{{ $pourcentage >= 100 ? 'text-red-600 font-black' : 'font-medium' }}">{{
                                            $pourcentage }}% / Vidange</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($derniereMission)
                                    @php
                                    // Calcul de la distance parcourue (km_retour - km_depart)
                                    $distance = ($derniereMission->km_retour && $derniereMission->km_depart)
                                    ? ($derniereMission->km_retour - $derniereMission->km_depart)
                                    : 0;
                                    @endphp
                                    <span class="text-sm font-bold text-emerald-600 font-mono">+{{
                                        number_format($distance, 0, ',', ' ') }} km</span>
                                    <span class="block text-[10px] text-gray-500">Destination : <strong
                                            class="text-indigo-600">{{ $derniereMission->destination }}</strong></span>
                                    @else
                                    <span class="text-xs text-gray-400 italic">Aucun trajet en mission</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-xs text-gray-500">
                                    @if($derniereMission)
                                    @php
                                    // Si la date réelle est vide, on prend la date de retour prévue ou la date système
                                    $dateReleve = $derniereMission->date_retour_reelle ??
                                    $derniereMission->date_retour_prevue ?? $derniereMission->updated_at;
                                    @endphp

                                    Le {{ \Carbon\Carbon::parse($dateReleve)->format('d/m/Y') }}

                                    <span class="block text-[10px] text-gray-400 font-bold">
                                        Par : {{ $derniereMission->driver ? $derniereMission->driver->full_name :
                                        'Chauffeur' }}
                                    </span>
                                    @else
                                    ---
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                    <button onclick="toggleHistory('history_{{ $vehicle->id }}')"
                                        class="text-xs font-bold border border-gray-200 hover:bg-gray-50 px-3 py-1.5 rounded-lg shadow-sm transition">
                                        📜 Historique ({{ $vehicle->bookings->count() }})
                                    </button>
                                    <button
                                        onclick="openMileageModal({{ $vehicle->id }}, '{{ $vehicle->immatriculation }}')"
                                        class="text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg shadow-sm transition">
                                        ✏️ Ajuster
                                    </button>
                                </td>
                            </tr>

                            <tr id="history_{{ $vehicle->id }}" class="hidden bg-gray-50/50">
                                <td colspan="5" class="px-8 py-4">
                                    <div class="border-l-2 border-indigo-200 pl-4 space-y-2">
                                        <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
                                            Historique des trajets réels (Missions)</h4>

                                        @if($vehicle->bookings->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            @foreach($vehicle->bookings->sortByDesc('id') as $booking)
                                            @php $distanceTrajet = max(0, $booking->km_retour - $booking->km_depart);
                                            @endphp
                                            <div
                                                class="bg-white p-2.5 rounded-lg border border-gray-100 flex justify-between text-xs shadow-sm">
                                                <div>
                                                    <span class="font-bold text-gray-800">📍 {{ $booking->destination ??
                                                        'Destination non renseignée' }}</span>
                                                    <span class="text-[10px] text-gray-400 block">Motif : {{
                                                        $booking->motif }}</span>
                                                    <span class="text-[9px] font-mono text-gray-500 block">Index : {{
                                                        number_format($booking->km_depart ?? 0, 0, ',', ' ') }} km ➔ {{
                                                        number_format($booking->km_retour ?? 0, 0, ',', ' ') }}
                                                        km</span>
                                                </div>
                                                <div class="text-right flex flex-col justify-between">
                                                    <span class="text-emerald-600 font-black">+{{
                                                        number_format($distanceTrajet, 0, ',', ' ') }} km</span>
                                                    <span class="text-[9px] text-gray-400 font-medium">Le {{
                                                        \Carbon\Carbon::parse($booking->date_retour_reelle ??
                                                        $booking->updated_at)->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <p class="text-xs text-gray-400 italic">Aucune mission trouvée pour ce véhicule.
                                        </p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400 italic">Aucun
                                    véhicule disponible.</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="mileageModal"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-100">
            <div class="bg-indigo-600 px-6 py-4 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Mise à jour manuelle du compteur</h3>
                <button onclick="closeMileageModal()" class="text-white text-xl font-bold">&times;</button>
            </div>
            <form action="{{ route('mileage.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="vehicle_id" id="modal_vehicle_id">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Véhicule</label>
                    <input type="text" id="modal_vehicle_name" readonly
                        class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-lg font-mono font-bold text-gray-700 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-50 uppercase tracking-widest">Conducteur
                        responsable du relevé</label>
                    <select name="driver_id" required class="mt-1 block w-full border-gray-300 rounded-lg text-sm">
                        <option value="" disabled selected>Choisir un conducteur...</option>
                        @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-gray-50 uppercase tracking-widest">Index Compteur
                            (KM)</label>
                        <input type="number" name="kilometrage_arrivee" required
                            class="mt-1 block w-full border-gray-300 rounded-lg text-sm font-mono font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-50 uppercase tracking-widest">Date de
                            relevé</label>
                        <input type="date" name="date_releve" required value="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full border-gray-300 rounded-lg text-xs">
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 flex justify-end space-x-2">
                    <button type="button" onclick="closeMileageModal()"
                        class="px-4 py-2 text-xs font-bold text-gray-500">Annuler</button>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-xs font-bold shadow-md">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleHistory(id) {
            const row = document.getElementById(id);
            row.classList.toggle('hidden');
        }
        function openMileageModal(id, immatriculation) {
            document.getElementById('modal_vehicle_id').value = id;
            document.getElementById('modal_vehicle_name').value = immatriculation;
            document.getElementById('mileageModal').classList.remove('hidden');
        }
        function closeMileageModal() {
            document.getElementById('mileageModal').classList.add('hidden');
        }
    </script>
</x-app-layout>