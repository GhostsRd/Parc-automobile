<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 tracking-tight">
                    {{ __('Supervision des vehicules') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Suivi en direct, imagerie satellite et état du trafic routier
                </p>
            </div>
            <div
                class="flex items-center space-x-3 bg-green-50 px-3.5 py-2 rounded-full border border-green-200 shadow-sm text-xs font-bold text-green-700">
                <span class="h-2 w-2 rounded-full bg-green-500 animate-ping"></span>
                <span class="relative flex h-2 w-2 rounded-full bg-green-600" style="margin-left: -12px;"></span>
                <span>Flux GPS Synchrone</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-stretch">

                <div
                    class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden lg:col-span-1 flex flex-col h-[650px]">
                    <div
                        class="px-5 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center flex-shrink-0">
                        <span class="text-xs font-bold uppercase text-gray-400 tracking-widest">Liste des
                            Véhicules</span>
                        <span class="px-3 py-1 text-xs bg-indigo-600 text-white font-black rounded-md">{{
                            $vehicles->count() }}</span>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden lg:col-span-1 flex flex-col h-[650px]">

                        <div
                            class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center flex-shrink-0">
                            <span class="text-xs font-bold uppercase text-gray-400 tracking-wider">Flotte</span>
                            <span class="px-2 py-0.5 text-xs bg-indigo-600 text-white font-black rounded-full">{{
                                $vehicles->count() }}</span>
                        </div>

                        <div class="p-2 bg-gray-50/50 border-b border-gray-100 flex-shrink-0">
                            <input type="text" id="searchVehicle" placeholder="Rechercher immatriculation, marque..."
                                class="w-full text-xs px-3 py-1.5 rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                onkeyup="filterVehicles()">
                        </div>

                        <div id="vehicleList" class="divide-y divide-gray-100 overflow-y-auto flex-1 select-none">
                            @forelse($vehicles as $vehicle)
                            @php
                            $pos = $vehicle->derniere_position;
                            $isMoving = $pos && $pos->vitesse > 2;
                            @endphp
                            <div class="vehicle-item p-3 hover:bg-indigo-50/40 transition cursor-pointer flex items-center justify-between gap-3 border-l-4 border-transparent hover:border-indigo-600"
                                data-search="{{ strtolower($vehicle->marque.' '.$vehicle->modele.' '.$vehicle->immatriculation) }}"
                                onclick="focusVehicle({{ $pos->latitude ?? 'null' }}, {{ $pos->longitude ?? 'null' }}, '{{ $vehicle->immatriculation }}')">

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5">
                                        <span
                                            class="h-2 w-2 rounded-full flex-shrink-0 {{ $pos ? ($isMoving ? 'bg-green-500 animate-pulse' : 'bg-amber-500') : 'bg-gray-300' }}"></span>
                                        <h4 class="text-sm font-bold text-gray-900 truncate">{{ $vehicle->marque }} {{
                                            $vehicle->modele }}</h4>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 mt-0.5 text-[11px] font-medium text-gray-400 font-mono">
                                        <span
                                            class="text-gray-600 font-bold bg-gray-100 px-1 rounded border border-gray-200">{{
                                            $vehicle->immatriculation }}</span>
                                        @if($pos)
                                        <span class="truncate">🛣️ {{ number_format($vehicle->kilometrage_actuel ?? 0,
                                            0, ',', ' ') }} km</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right flex-shrink-0">
                                    @if($pos)
                                    <div class="text-xs font-black text-gray-700">
                                        {{ $isMoving ? round($pos->vitesse).' km/h' : 'Arrêté' }}
                                    </div>
                                    <div class="text-[9px] text-gray-400 font-medium">
                                        {{ \Carbon\Carbon::parse($pos->timestamp_gps)->diffForHumans(null, true) }}
                                    </div>
                                    @else
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Offline</span>
                                    @endif
                                </div>

                            </div>
                            @empty
                            <div class="p-8 text-center text-gray-400 text-xs italic">Aucun véhicule.</div>
                            @endforelse
                        </div>
                    </div>


                </div>

                <div class="lg:col-span-3 bg-white rounded-2xl shadow-md border border-gray-200 p-3 h-[650px]">
                    <div style="width: 100%; height: 100%; position: relative; background-color: #f3f4f6;"
                        class="rounded-xl overflow-hidden shadow-inner">
                        <div id="map"
                            style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: block; z-index: 10;">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        window.onload = function () {
            try {
                // COUCHE 1 : Plan Standard Épuré (CartoDB Positron - Très lisible pour le traffic)
                const planClassique = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                });

                // COUCHE 2 : Imagerie Satellite Haute Définition (Esri)
                const vueSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 19,
                    attribution: 'Tiles © Esri'
                });

                // COUCHE OVERLAY 3 : Flux de Trafic Temps Réel Global (TomTom/OSM Open Traffic)
                const fluxTraffic = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    opacity: 0.65, // Opacité pour se superposer proprement sur la route
                    attribution: 'Traffic © OSM'
                });

                // Initialisation de la carte centrée sur Madagascar par défaut
                const map = L.map('map', {
                    center: [-18.8792, 47.5079],
                    zoom: 12,
                    layers: [planClassique] // Fond par défaut
                });

                // Contrôle des calques pour l'utilisateur (TopRight)
                const cartesDeBase = {
                    "Plan de ville": planClassique,
                    "Vue Satellite (Mada)": vueSatellite
                };

                const superpositions = {
                    "Afficher le Trafic Routier": fluxTraffic
                };

                // Ajout du bouton sélecteur sur la carte
                L.control.layers(cartesDeBase, superpositions, { 
                    position: 'topright',
                    collapsed: false // Laisse le menu déplié pour une visibilité immédiate !
                }).addTo(map);

                const vehicleMarkers = {};
                const groupBounds = [];

                // Injection dynamique des marqueurs de position
                @foreach($vehicles as $vehicle)
                    @if($vehicle->derniere_position && $vehicle->derniere_position->latitude && $vehicle->derniere_position->longitude)
                        (function() {
                            const lat = Number("{{ $vehicle->derniere_position->latitude }}");
                            const lng = Number("{{ $vehicle->derniere_position->longitude }}");
                            const immat = "{{ $vehicle->immatriculation }}";
                            
                            const content = `
                                <div style="font-family: system-ui, sans-serif; padding: 4px; min-width: 160px;">
                                    <h3 style="margin:0 0 2px 0; font-size:14px; font-weight:800; color:#111827;">{{ $vehicle->marque }} {{ $vehicle->modele }}</h3>
                                    <span style="font-family:monospace; background:#f3f4f6; padding:1px 5px; border-radius:3px; font-size:10px; font-weight:bold; color:#6b7280; display:inline-block; margin-bottom:8px;">${immat}</span>
                                    <div style="font-size:11px; color:#4b5563; line-height:1.5;">
                                        <b>Vitesse actuelle :</b> {{ round($vehicle->derniere_position->vitesse) }} km/h<br>
                                        <b>Statut :</b> {{ $vehicle->derniere_position->vitesse > 2 ? 'En mouvement 🟢' : 'À l\'arrêt 🟡' }}<br>
                                        <b>Compteur global :</b> {{ number_format($vehicle->kilometrage_actuel, 0, ",", " ") }} km
                                    </div>
                                    <div style="margin-top:10px; border-top:1px solid #e5e7eb; padding-top:8px;">
                                        <a href="{{ route('gps.history', $vehicle->id) }}" style="display:block; text-align:center; background:#4f46e5; color:#fff; padding:6px; border-radius:6px; font-size:10px; text-decoration:none; font-weight:bold; shadow:sm;">
                                            Consulter l'historique
                                        </a>
                                    </div>
                                </div>
                            `;

                            const color = {{ $vehicle->derniere_position->vitesse }} > 2 ? 'green' : 'orange';
                            const customIcon = L.icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-' + color + '.png',
                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                                shadowSize: [41, 41]
                            });

                            const marker = L.marker([lat, lng], { icon: customIcon })
                                .bindPopup(content)
                                .addTo(map);

                            vehicleMarkers[immat] = marker;
                            groupBounds.push([lat, lng]);
                        })();
                    @endif
                @endforeach

                if (groupBounds.length > 0) {
                    map.fitBounds(groupBounds, { padding: [60, 60] });
                }

                setTimeout(function() {
                    map.invalidateSize();
                }, 200);

                window.focusVehicle = function(lat, lng, immat) {
                    if (lat && lng && vehicleMarkers[immat]) {
                        map.setView([lat, lng], 16);
                        vehicleMarkers[immat].openPopup();
                    } else {
                        alert('Coordonnées indisponibles.');
                    }
                };

            } catch (error) {
                console.error("Erreur d'initialisation :", error);
            }
        };

    </script>
    <script>
        function filterVehicles() {
        const input = document.getElementById('searchVehicle').value.toLowerCase();
        const items = document.querySelectorAll('.vehicle-item');
        
        items.forEach(item => {
            const text = item.getAttribute('data-search');
            if(text.includes(input)) {
                item.style.setProperty('display', 'flex', 'important');
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });
    }
    </script>

</x-app-layout>