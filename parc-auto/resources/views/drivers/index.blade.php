<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-tight">
                    Annuaire des <span class="text-indigo-600">Chauffeurs</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1 font-medium italic">Gestion des effectifs et suivi des
                    interventions</p>
            </div>

            <a href="{{ route('drivers.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition ease-in-out duration-150 shadow-md shadow-indigo-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Nouveau Chauffeur
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">

                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-gray-100 bg-white">
                    <div class="p-6 border-b md:border-b-0 md:border-r border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Effectif Global</p>
                        <p class="text-2xl font-black text-gray-900 mt-2">
                            {{ $drivers->count() }} {{ $drivers->count() > 1 ? 'Chauffeurs' : 'Chauffeur' }}
                        </p>
                    </div>

                    <div class="p-6 border-b md:border-b-0 md:border-r border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Actifs & Opérationnels</p>
                        <p class="text-2xl font-black text-emerald-600 mt-2">
                            {{ $drivers->where('is_active', true)->count() }} <span
                                class="text-xs font-medium text-gray-400">en service</span>
                        </p>
                    </div>

                    <div class="p-6">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Volume d'Interventions</p>
                        <p class="text-2xl font-black text-indigo-600 mt-2">
                            {{ $drivers->sum('maintenances_count') }} <span
                                class="text-xs font-medium text-gray-400">Actes suivis</span>
                        </p>
                    </div>
                </div>

                <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="driverSearch"
                            placeholder="Rechercher un chauffeur, matricule ou permis..."
                            class="block w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl text-base bg-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all shadow-sm">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Identité
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Contact
                                    & Permis</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Statut
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">
                                    Interventions</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($drivers as $driver)
                            <tr class="hover:bg-indigo-50/30 transition-colors duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 flex-shrink-0 rounded-full bg-gradient-to-tr from-indigo-600 to-indigo-400 flex items-center justify-center text-white shadow-inner font-black text-sm">
                                            {{ strtoupper(substr($driver->full_name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">
                                                {{ $driver->full_name }}</div>
                                            <div class="text-xs font-mono text-gray-400 italic">ID-{{
                                                str_pad($driver->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        <div class="flex items-center text-sm text-gray-600 font-medium">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                                    stroke-width="2" />
                                            </svg>
                                            {{ $driver->phone ?? '---' }}
                                        </div>
                                        <div class="flex items-center text-xs text-gray-400 uppercase tracking-tighter">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"
                                                    stroke-width="2" />
                                            </svg>
                                            {{ $driver->license_number ?? 'Non renseigné' }}
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($driver->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 uppercase border border-emerald-200 tracking-widest">
                                        <span
                                            class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                        Opérationnel
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-gray-100 text-gray-500 uppercase border border-gray-200 tracking-widest">
                                        Hors-service
                                    </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div
                                        class="inline-flex flex-col items-center justify-center bg-gray-50 px-3 py-1 rounded-lg border border-gray-100">
                                        <span class="text-sm font-black text-gray-900 leading-none">{{
                                            $driver->maintenances_count }}</span>
                                        <span
                                            class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Actes</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex justify-end space-x-1">
                                        <a href="{{ route('drivers.edit', $driver) }}"
                                            class="p-2 bg-white border border-gray-200 rounded-md text-gray-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition shadow-sm"
                                            title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('drivers.destroy', $driver) }}" method="POST"
                                            onsubmit="return confirm('Supprimer ce chauffeur ?');" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-white border border-gray-200 rounded-md text-gray-400 hover:text-red-600 hover:border-red-100 hover:bg-red-50 transition shadow-sm"
                                                title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-3 bg-gray-50 rounded-full">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                                    stroke-width="1.5" />
                                            </svg>
                                        </div>
                                        <p class="mt-4 text-gray-400 text-sm italic font-medium">Aucun chauffeur dans le
                                            système.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($drivers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $drivers->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>