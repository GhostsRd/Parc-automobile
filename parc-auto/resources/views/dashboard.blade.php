<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-black text-gray-400 uppercase">Distance Flotte</p>
                    <p class="text-2xl font-black text-indigo-600">{{ number_format($totalDistance, 0, ',', ' ') }} km
                    </p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-black text-gray-400 uppercase">Budget Entretien</p>
                    <p class="text-2xl font-black text-red-600">{{ number_format($totalMaintenanceCost, 2, ',', ' ') }}
                        €</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-black text-gray-400 uppercase">Missions Actives</p>
                    <p class="text-2xl font-black text-emerald-600">{{ $activeMissions }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-black text-gray-400 uppercase">Coût Moyen Acte</p>
                    <p class="text-2xl font-black text-gray-800">{{ number_format($avgMaintenanceCost, 2) }} €</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-6 uppercase text-sm tracking-widest">Dépenses Entretien
                        Mensuelles</h3>
                    <canvas id="maintenanceChart" height="120"></canvas>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-6 uppercase text-sm tracking-widest">Rentabilité / Usage</h3>
                    <div class="space-y-6">
                        @foreach($vehicleStats as $stat)
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-2">
                                <span>{{ $stat->immatriculation }}</span>
                                <span class="text-gray-500">{{ number_format($stat->distance_parcourue) }} km</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 shadow-inner">
                                @php
                                // Calcul du pourcentage par rapport au budget total pour la barre
                                $percent = $totalMaintenanceCost > 0 ? ($stat->maintenances_sum_cout /
                                $totalMaintenanceCost) * 100 : 0;
                                @endphp
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                            <p class="text-[10px] mt-1 text-red-600 font-bold">Total Entretien : {{
                                number_format($stat->maintenances_sum_cout, 2) }} €</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">

                <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-6 uppercase text-sm tracking-widest text-emerald-600">
                        Fréquence des Sorties</h3>
                    <canvas id="activityChart" height="120"></canvas>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 text-center">
                    <h3 class="font-bold text-gray-800 mb-6 uppercase text-sm tracking-widest text-indigo-600">Motifs de
                        Déplacement</h3>
                    <div class="h-64">
                        <canvas id="motifsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>






    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique d'Activité (Courbe Emerald)
    const ctxActivity = document.getElementById('activityChart').getContext('2d');
    new Chart(ctxActivity, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Nombre de missions',
                data: @json($missionsChartData),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 4
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Graphique Motifs (Doughnut)
    const ctxMotifs = document.getElementById('motifsChart').getContext('2d');
    new Chart(ctxMotifs, {
        type: 'doughnut',
        data: {
            labels: @json($motifsData->pluck('motif')),
            datasets: [{
                data: @json($motifsData->pluck('total')),
                backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            }
        }
    });
    </script>
    <script>
        const ctx = document.getElementById('maintenanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                datasets: [{
                    label: 'Coût Entretien (€)',
                    data: @json($chartData),
                    backgroundColor: '#6366f1',
                    borderRadius: 8,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, grid: { display: false } } }
            }
        });
    </script>
</x-app-layout>