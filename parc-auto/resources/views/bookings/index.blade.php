<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-tight">
                    Suivi des <span class="text-indigo-600">Missions</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1 font-medium italic">Journal de bord et mouvements de la flotte</p>
            </div>

            <a href="{{ route('bookings.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-100 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Nouvelle Mission
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-gray-100 bg-white">
                    <div class="p-6 border-b md:border-b-0 md:border-r border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Volume Global</p>
                        <p class="text-2xl font-black text-gray-900 mt-2">
                            {{ $bookings->count() }} {{ $bookings->count() > 1 ? 'Missions' : 'Mission' }}
                        </p>
                    </div>

                    <div class="p-6 border-b md:border-b-0 md:border-r border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Trajets Actifs</p>
                        <p class="text-2xl font-black text-emerald-600 mt-2">
                            {{ $bookings->where('statut', 'en_cours')->count() }} <span class="text-xs font-medium text-gray-400">sur la route</span>
                        </p>
                    </div>

                    <div class="p-6">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Flotte Sollicitée</p>
                        <p class="text-2xl font-black text-indigo-600 mt-2">
                            {{ $bookings->unique('vehicle_id')->count() }} <span class="text-xs font-medium text-gray-400">{{ $bookings->unique('vehicle_id')->count() > 1 ? 'Véhicules actifs' : 'Véhicule actif' }}</span>
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Chauffeur / Véhicule</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Destination & Motif</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">
                                    Dates (Prévues)</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">
                                    KM Départ</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">
                                    Statut</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($bookings as $booking)
                            <tr class="hover:bg-gray-50/50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 uppercase tracking-tight">{{
                                            $booking->driver->full_name }}</span>
                                        <span class="text-xs text-indigo-500 font-black">
                                            {{ $booking->vehicle->immatriculation }} <span
                                                class="text-gray-400 font-normal">({{ $booking->vehicle->marque
                                                }})</span>
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-700">{{ $booking->destination }}</div>
                                    <div class="text-xs text-gray-400 truncate w-48">{{ $booking->motif ?? 'Aucun motif
                                        précisé' }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Départ</span>
                                        <span class="text-xs font-bold text-gray-700">
                                            {{ $booking->date_depart instanceof \Carbon\Carbon ?
                                            $booking->date_depart->format('d/m/Y H:i') :
                                            \Carbon\Carbon::parse($booking->date_depart)->format('d/m/Y H:i') }}
                                        </span>

                                        <span
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mt-1 italic">Retour
                                            estimé</span>
                                        <span class="text-[10px] font-medium text-gray-500">
                                            {{ $booking->date_retour_prevue instanceof \Carbon\Carbon ?
                                            $booking->date_retour_prevue->format('d/m/Y H:i') :
                                            \Carbon\Carbon::parse($booking->date_retour_prevue)->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2 py-1 bg-gray-100 rounded text-xs font-mono font-bold text-gray-600 border border-gray-200">
                                        {{ number_format($booking->km_depart, 0, ',', ' ') }} km
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                    $statusClasses = [
                                    'en_attente' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'en_cours' => 'bg-emerald-100 text-emerald-700 border-emerald-200 shadow-sm
                                    shadow-emerald-50',
                                    'terminee' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'annulee' => 'bg-red-100 text-red-700 border-red-200',
                                    ][$booking->statut] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusClasses }}">
                                        @if($booking->statut === 'en_cours')
                                        <span
                                            class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full animate-ping"></span>
                                        @endif
                                        {{ str_replace('_', ' ', $booking->statut) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium">
                                    @if($booking->statut === 'en_cours')
                                    <a href="{{ route('bookings.edit', $booking) }}"
                                        class="inline-flex items-center px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase rounded shadow-sm transition">
                                        Clôturer le trajet
                                    </a>
                                    @else
                                    <a href="{{ route('bookings.show', $booking) }}"
                                        class="text-gray-400 hover:text-indigo-600 transition">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" />
                                            <path
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                stroke-width="2" />
                                        </svg>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <p class="text-gray-400 italic font-medium">Aucune mission enregistrée pour le
                                        moment.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($bookings->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $bookings->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>