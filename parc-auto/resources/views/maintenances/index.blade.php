<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                Journal Global des <span class="text-indigo-600">Maintenances</span>
            </h2>
            <a href="{{ route('vehicles.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                Saisir un entretien
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-gray-100">
                    <div class="p-6 border-r border-gray-100">
                        <p class="text-sm text-gray-500 font-medium uppercase">Total Dépensé</p>
                        <p class="text-2xl font-black text-gray-900">{{ number_format($maintenances->sum('cout'), 2, ',', ' ') }} €</p>
                    </div>
                    <div class="p-6 border-r border-gray-100">
                        <p class="text-sm text-gray-500 font-medium uppercase">Interventions</p>
                        <p class="text-2xl font-black text-indigo-600">{{ $maintenances->count() }}</p>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-500 font-medium uppercase">Dernière activité</p>
                        <p class="text-2xl font-black text-gray-900">
                            {{ $maintenances->max('date_intervention') ? \Carbon\Carbon::parse($maintenances->max('date_intervention'))->format('d/m/Y') : '--' }}
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Véhicule</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type / Note</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kilométrage</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Coût</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($maintenances as $maintenance)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($maintenance->date_intervention)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-indigo-600">{{ $maintenance->vehicle->brand }} {{ $maintenance->vehicle->model }}</span>
                                        <span class="text-xs font-mono text-gray-500">{{ $maintenance->vehicle->immatriculation }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="px-2 py-1 text-xs font-extrabold rounded-md {{ $maintenance->type == 'vidange' ? 'bg-fuchsia-100 text-fuchsia-700' : 'bg-blue-100 text-blue-700' }} uppercase">
                                            {{ $maintenance->type }}
                                        </span>
                                        <span class="ml-3 text-sm text-gray-600 truncate max-w-xs">{{ Str::limit($maintenance->notes, 40) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ number_format($maintenance->kilometrage_au_moment_de_l_acte, 0, ',', ' ') }} km
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-black text-gray-900">{{ number_format($maintenance->cout, 2, ',', ' ') }} €</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
    <a href="{{ route('maintenances.show', $maintenance->id) }}" class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-indigo-700 rounded-lg font-bold transition">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        Détails
    </a>
</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <p class="text-gray-400 italic text-sm">Aucun entretien enregistré dans la base de données.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($maintenances->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $maintenances->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>