<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        // On charge vehicle et driver en une seule fois
        $bookings = Booking::with(['vehicle', 'driver'])
                    ->latest()
                    ->paginate(15);
                    
        return view('bookings.index', compact('bookings'));
    }
    public function edit(Booking $booking)
    {
       // On vérifie que la mission est bien "en cours" avant de permettre la clôture
    if ($booking->statut !== 'en_cours') {
        return redirect()->route('bookings.index')
            ->with('error', 'Cette mission est déjà clôturée ou annulée.');
    }

    return view('bookings.edit', compact('booking'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('statut', 'disponible')->get();
        $drivers = Driver::where('is_active', true)->get();
        return view('bookings.create', compact('vehicles', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'destination' => 'required|string|max:255',
            'date_depart' => 'required|date|after_or_equal:today',
            'date_retour_prevue' => 'required|date|after:date_depart',
            'km_depart' => 'required|integer|min:0',
            'motif' => 'nullable|string',
        ]);

        $booking = Booking::create($validated + ['statut' => 'en_cours']);

        // Logique Métier : On passe le véhicule en mission immédiatement
        Vehicle::where('id', $validated['vehicle_id'])->update(['statut' => 'en_mission']);

        return redirect()->route('bookings.index')->with('success', 'Mission lancée avec succès.');
    }

        public function show(Booking $booking)
    {
        // On s'assure que les relations sont chargées
        $booking->load(['vehicle', 'driver']);
        
        return view('bookings.show', compact('booking'));
    }

    // Méthode pour clôturer la mission
    public function update(Request $request, Booking $booking)
{
    $validated = $request->validate([
        'km_retour' => 'required|integer|gt:' . $booking->km_depart,
        'date_retour_reelle' => 'required|date',
    ]);

    // 1. On met à jour la mission (le trajet)
    $booking->update([
        'km_retour' => $validated['km_retour'],
        'date_retour_reelle' => $validated['date_retour_reelle'],
        'statut' => 'terminee'
    ]);

    // 2. On récupère le véhicule lié et on met à jour ses infos
    // On utilise la relation $booking->vehicle
    $vehicle = $booking->vehicle;
    
    $vehicle->update([
        'kilometrage_actuel' => $validated['km_retour'],
        'statut' => 'disponible' // Le véhicule redevient libre
    ]);

    return redirect()->route('bookings.index')
        ->with('success', 'Mission clôturée. Le compteur du véhicule a été mis à jour à ' . $validated['km_retour'] . ' km.');
}

    
}