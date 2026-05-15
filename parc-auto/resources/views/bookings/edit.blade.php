<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            Clôturer la Mission : <span class="text-indigo-600">{{ $booking->destination }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-xl sm:rounded-2xl border border-emerald-100">
                
                <div class="grid grid-cols-2 gap-4 mb-8 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase">Véhicule</p>
                        <p class="text-sm font-bold">{{ $booking->vehicle->immatriculation }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase">KM au départ</p>
                        <p class="text-sm font-bold font-mono">{{ number_format($booking->km_depart, 0, ',', ' ') }} km</p>
                    </div>
                </div>

                <form action="{{ route('bookings.update', $booking) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="finish" value="1">

                    <div>
                        <x-label for="km_retour" value="Kilométrage au retour" class="font-bold text-gray-700" />
                        <x-input id="km_retour" name="km_retour" type="number" 
                                 class="mt-1 block w-full text-lg font-bold" 
                                 min="{{ $booking->km_depart }}" 
                                 value="{{ $booking->km_depart }}" required />
                        <p class="text-xs text-gray-500 mt-1 italic">Doit être supérieur à {{ $booking->km_depart }} km</p>
                    </div>

                    <div>
                        <x-label for="date_retour_reelle" value="Date et Heure de retour effective" class="font-bold text-gray-700" />
                        <x-input id="date_retour_reelle" name="date_retour_reelle" type="datetime-local" 
                                 class="mt-1 block w-full" 
                                 value="{{ now()->format('Y-m-d\TH:i') }}" required />
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end items-center space-x-4">
                        <a href="{{ route('bookings.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600">Annuler</a>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-xl font-black text-sm uppercase tracking-widest shadow-lg shadow-emerald-100 transition">
                            Confirmer la fin de trajet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>