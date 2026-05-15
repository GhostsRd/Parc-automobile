<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
                Détails de la Mission <span class="text-indigo-600">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT)
                    }}</span>
            </h2>
            <a href="{{ route('bookings.index') }}"
                class="text-sm font-bold text-gray-500 hover:text-gray-700 transition">
                &larr; Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl sm:rounded-3xl overflow-hidden border border-gray-100">

                <div
                    class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">Destination</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ $booking->destination }}</h3>
                    </div>
                    <div class="text-right">
                        @php
                        $statusColors = [
                        'en_cours' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'terminee' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'annulee' => 'bg-red-100 text-red-700 border-red-200'
                        ][$booking->statut] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span
                            class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest border {{ $statusColors }}">
                            {{ str_replace('_', ' ', $booking->statut) }}
                        </span>
                    </div>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">

                    <div class="space-y-6">
                        <div>
                            <h4 class="text-xs font-black text-gray-400 uppercase mb-3 italic tracking-wider">Chauffeur
                            </h4>
                            <div class="flex items-center p-3 bg-gray-50 rounded-2xl border border-gray-100">
                                <div
                                    class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($booking->driver->full_name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-900">{{ $booking->driver->full_name }}</p>
                                    <p class="text-[10px] text-gray-500 font-medium">{{ $booking->driver->phone }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-black text-gray-400 uppercase mb-3 italic tracking-wider">Véhicule
                            </h4>
                            <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-sm font-bold text-gray-900">{{ $booking->vehicle->marque }} {{
                                    $booking->vehicle->modele }}</p>
                                <p class="text-xs font-mono text-indigo-600 font-black">{{
                                    $booking->vehicle->immatriculation }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 bg-gray-50/50 rounded-3xl p-6 border border-gray-100">
                        <h4 class="text-xs font-black text-gray-400 uppercase mb-6 italic tracking-wider text-center">
                            Journal de bord</h4>

                        <div class="relative">
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                            <div class="relative pl-10 mb-8">
                                <div
                                    class="absolute left-2.5 w-3.5 h-3.5 bg-indigo-500 rounded-full border-4 border-white ring-1 ring-indigo-500">
                                </div>
                                <p class="text-[10px] font-black text-gray-400 uppercase">Départ enregistré</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $booking->date_depart instanceof \Carbon\Carbon ?
                                    $booking->date_depart->format('d F Y \à H:i') :
                                    \Carbon\Carbon::parse($booking->date_depart)->format('d F Y \à H:i') }}
                                </p>
                                <p class="text-xs text-indigo-600 font-mono">Compteur : {{
                                    number_format($booking->km_depart, 0, ',', ' ') }} km</p>
                            </div>

                            <div class="relative pl-10">
                                <div
                                    class="absolute left-2.5 w-3.5 h-3.5 {{ $booking->date_retour_reelle ? 'bg-emerald-500 ring-emerald-500' : 'bg-gray-300 ring-gray-300' }} rounded-full border-4 border-white ring-1">
                                </div>
                                <p class="text-[10px] font-black text-gray-400 uppercase">Retour effectif</p>
                                @if($booking->date_retour_reelle)
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $booking->date_retour_reelle instanceof \Carbon\Carbon ?
                                    $booking->date_retour_reelle->format('d F Y \à H:i') :
                                    \Carbon\Carbon::parse($booking->date_retour_reelle)->format('d F Y \à H:i') }}
                                </p>
                                @else
                                <p class="text-sm font-bold text-gray-400 italic">En attente de retour...</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-3 pt-6 border-t border-gray-100">
                        <h4 class="text-xs font-black text-gray-400 uppercase mb-2 italic tracking-wider">Motif de la
                            mission</h4>
                        <div
                            class="p-4 bg-white rounded-xl border border-gray-200 text-sm text-gray-700 leading-relaxed shadow-inner">
                            {{ $booking->motif ?? 'Aucune description détaillée n\'a été fournie pour cette mission.' }}
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-4 flex justify-between">
                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                        onsubmit="return confirm('Supprimer définitivement cette mission ?');">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="text-xs font-bold text-red-400 hover:text-red-600 transition uppercase">Supprimer le
                            log</button>
                    </form>

                    @if($booking->statut === 'en_cours')
                    <a href="{{ route('bookings.edit', $booking) }}"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">
                        Finaliser la mission
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>