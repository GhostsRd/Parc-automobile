<?php
namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::withCount('maintenances')->orderBy('full_name')->paginate(10);
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'license_number' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        Driver::create($validated);

        return redirect()->route('drivers.index')
            ->with('success', 'Chauffeur ajouté avec succès.');
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'license_number' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
        ]);

        $driver->update($validated);

        return redirect()->route('drivers.index')
            ->with('success', 'Fiche chauffeur mise à jour.');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('drivers.index')
            ->with('success', 'Chauffeur supprimé.');
    }
}