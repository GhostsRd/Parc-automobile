<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 tracking-tight">
                    {{ __('Historique des Trajets') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Analyse des activités : <span class="font-bold text-indigo-600">{{ $vehicle->marque }} {{ $vehicle->modele }} ({{ $vehicle->immatriculation }})</span></p>
            </div>
            <a href="{{ route('gps.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                ⬅️ Retour au Temps Réel
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-center">
                    <form method="GET" action="{{ route('gps.history', $vehicle->id) }}" id="dateForm">
                        <label for="date" class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-2">Choisir une date</label>
                        <input type="date" id="date" name="date" value="{{ $date }}" 
                               onchange="document.getElementById('dateForm').submit();"
                               class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium">
                    </form>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-blue-50 rounded-xl text-xl">⚡</div>
                    <div>
                        <span class="block text-xs font-bold uppercase text-gray-400 tracking-wider">Vitesse Moyenne</span>
                        <span class="text-xl font-black text-gray-800">{{ round($vitesseMoyenne) }} <span class="text-xs font-normal text-gray-500">km/h</span></span>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-green-50 rounded-xl text-xl">📍</div>
                    <div>
                        <span class="block text-xs font-bold uppercase text-gray-400 tracking-wider">Points Traités</span>
                        <span class="text-xl font-black text-gray-800">{{ $positions->count() }} <span class="text-xs font-normal text-gray-500">pings</span></span>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-amber-50 rounded-xl text-xl">🛑</div>
                    <div>
                        <span class="block text-xs font-bold uppercase text-gray-400 tracking-wider">Arrêts détectés</span>
                        <span class="text-xl font-black text-gray-800">{{ $positionsArret->count() }} <span class="text-xs font-normal text-gray-500">zones</span></span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-stretch">
                
                <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden lg:col-span-1 flex flex-col h-[600px]">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center flex-shrink-0">
                        <span class="text-xs font-bold uppercase text-gray-400 tracking-wider">Résumé de la journée</span>
                        <span class="text-[10px] bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded font-bold">Synthèse</span>
                    </div>
                    
                    <div id="timelineEvents" class="divide-y divide-gray-100 overflow-y-auto flex-1 p-2 space-y-2">
                        @if($positions->isEmpty())
                            <div class="p-8 text-center text-gray-400 text-xs italic">🗺️ Aucun déplacement pour cette date.</div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-3 bg-white rounded-2xl shadow-md border border-gray-200 p-2 h-[600px]">
                    <div style="width: 100%; height: 100%; position: relative; background-color: #f3f4f6;" class="rounded-xl overflow-hidden shadow-inner">
                        <div id="map" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: block; z-index: 10;"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Extraction des données PHP vers le JavaScript
            const rawPositions = [
                @foreach($positions as $pos)
                    {
                        lat: {{ $pos->latitude }},
                        lng: {{ $pos->longitude }},
                        vitesse: {{ $pos->vitesse }},
                        heure: '{{ \Carbon\Carbon::parse($pos->timestamp_gps)->format("H:i:s") }}'
                    },
                @endforeach
            ];

            try {
                // Initialisation carte
                const planClassique = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { maxZoom: 19 });
                const vueSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 });

                const map = L.map('map', { center: [-18.8792, 47.5079], zoom: 12, layers: [planClassique] });
                L.control.layers({ "Plan": planClassique, "Satellite": vueSatellite }, null, { position: 'topright' }).addTo(map);

                if (rawPositions.length === 0) return;

                const routeCoordinates = [];
                const segments = [];
                let currentSegment = null;

                // Algorithme de regroupement des points en Trajets / Arrêts
                rawPositions.forEach((pos, index) => {
                    routeCoordinates.push([pos.lat, pos.lng]);
                    const isMoving = pos.vitesse > 2;
                    const status = isMoving ? 'En déplacement' : 'À l\'arrêt';

                    if (!currentSegment) {
                        currentSegment = { status: status, start: pos.heure, end: pos.heure, pts: [[pos.lat, pos.lng]], maxVit: pos.vitesse };
                    } else if (currentSegment.status === status) {
                        currentSegment.end = pos.heure;
                        currentSegment.pts.push([pos.lat, pos.lng]);
                        if (pos.vitesse > currentSegment.maxVit) currentSegment.maxVit = pos.vitesse;
                    } else {
                        segments.push(currentSegment);
                        currentSegment = { status: status, start: pos.heure, end: pos.heure, pts: [[pos.lat, pos.lng]], maxVit: pos.vitesse };
                    }
                    if (index === rawPositions.length - 1) segments.push(currentSegment);
                });

                // Dessiner la ligne globale du parcours
                const polyline = L.polyline(routeCoordinates, { color: '#6366f1', weight: 5, opacity: 0.85, lineJoin: 'round' }).addTo(map);
                map.fitBounds(polyline.getBounds(), { padding: [50, 50] });

                // Génération de la liste condensée sans scroll infini
                const timelineContainer = document.getElementById('timelineEvents');
                
                segments.forEach((seg, idx) => {
                    const isMov = seg.status === 'En déplacement';
                    const div = document.createElement('div');
                    div.className = `p-3 rounded-xl border transition cursor-pointer flex items-center justify-between ${isMov ? 'bg-green-50/50 border-green-100 hover:bg-green-100/50' : 'bg-amber-50/40 border-amber-100 hover:bg-amber-100/40'}`;
                    
                    div.innerHTML = `
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 font-bold text-xs text-gray-400 uppercase tracking-wider">
                                <span class="h-2 w-2 rounded-full ${isMov ? 'bg-green-500' : 'bg-amber-500'}"></span>
                                ${seg.status}
                            </div>
                            <div class="text-sm font-black text-gray-800 mt-0.5">
                                ${seg.start.substring(0,5)} à ${seg.end.substring(0,5)}
                            </div>
                        </div>
                        <div class="text-right text-xs">
                            <span class="font-bold text-gray-600 block">${seg.pts.length} repères</span>
                            <span class="text-[10px] text-gray-400">${isMov ? 'Max : ' + round(seg.maxVit) + ' km/h' : 'Moteur coupé'}</span>
                        </div>
                    `;

                    // Clic sur un bloc complet : Zoom et isole ce moment précis de la journée
                    div.onclick = () => {
                        const segmentPoly = L.polyline(seg.pts);
                        map.fitBounds(segmentPoly.getBounds(), { padding: [40, 40], maxZoom: 16 });
                    };

                    timelineContainer.appendChild(div);
                });

                // Marqueurs Départ et Arrivée de journée
                const startPt = rawPositions[0];
                const endPt = rawPositions[rawPositions.length - 1];
                
                L.marker([startPt.lat, startPt.lng], { icon: createIcon('green') }).bindPopup(`🏁 <b>Départ de la journée</b> à ${startPt.heure}`).addTo(map);
                L.marker([endPt.lat, endPt.lng], { icon: createIcon('red') }).bindPopup(`🏁 <b>Fin de journée / Position actuelle</b> à ${endPt.heure}`).addTo(map);

                function createIcon(color) {
                    return L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-' + color + '.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
                    });
                }

                function round(v) { return Math.round(v); }
                setTimeout(() => map.invalidateSize(), 250);

            } catch (e) {
                console.error("Erreur historique:", e);
            }
        });
    </script>
</x-app-layout>