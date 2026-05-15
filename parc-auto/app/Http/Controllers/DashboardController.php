<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Maintenance;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Distance totale (Somme des différences KM retour - KM départ)
        $totalDistance = Booking::where('statut', 'terminee')
            ->select(DB::raw('SUM(km_retour - km_depart) as total'))
            ->value('total') ?? 0;

        // 2. Missions en cours
        $activeMissions = Booking::where('statut', 'en_cours')->count();

        // 3. Coût total maintenance
        $totalMaintenanceCost = Maintenance::sum('cout') ?? 0;

        // 4. Coût moyen
        $avgMaintenanceCost = Maintenance::avg('cout') ?? 0;

        // 5. Statistiques par véhicule
        $vehicleStats = Vehicle::withSum('maintenances', 'cout')
            ->get()
            ->map(function($vehicle) {
                $vehicle->distance_parcourue = Booking::where('vehicle_id', $vehicle->id)
                    ->where('statut', 'terminee')
                    ->select(DB::raw('SUM(km_retour - km_depart) as total'))
                    ->value('total') ?? 0;
                return $vehicle;
            });

        // 6. Données Graphique (Dépenses par mois)
        $monthlyData = Maintenance::selectRaw('MONTH(date_intervention) as month, SUM(cout) as total')
            ->whereYear('date_intervention', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData[$i] ?? 0;
        }

        // --- ANALYSE D'ACTIVITÉ ---
        // 1. Répartition des motifs de mission (ex: Livraison, Rendez-vous, etc.)
        $motifsData = Booking::select('motif', DB::raw('count(*) as total'))
            ->groupBy('motif')
            ->get();

        // 2. Volume de missions par mois (pour comparer à l'entretien)
        $missionsMensuelles = Booking::selectRaw('MONTH(date_depart) as month, COUNT(*) as count')
            ->whereYear('date_depart', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')->toArray();

        $missionsChartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $missionsChartData[] = $missionsMensuelles[$i] ?? 0;
        }

        // Ajouter 'motifsData' et 'missionsChartData' au compact()
      
        // BIEN VÉRIFIER LE COMPACT ICI :
        return view('dashboard', compact(
            'totalDistance', 
            'activeMissions', 
            'totalMaintenanceCost', 
            'avgMaintenanceCost',
            'vehicleStats',
            'chartData',
             'motifsData',
            'missionsChartData'

        ));
    }
}