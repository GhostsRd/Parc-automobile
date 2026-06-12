<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-tight">
                    Détails de la <span class="text-indigo-600">Maintenance</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1 font-medium italic">Fiche d'intervention et suivi d'atelier</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('maintenances.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg font-bold text-xs text-gray-600 uppercase tracking-widest hover:bg-gray-50 shadow-sm transition">
                    Retour
                </a>
                <a href="{{ route('maintenances.edit', $maintenance) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-100 transition">
                    Modifier l'acte
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-gray-100 bg-white">
                    
                    <div class="p-6 border-b md:border-b-0 md:border-r border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Type d'entretien</p>
                        <p class="text-2xl font-black text-indigo-600 mt-2 uppercase tracking-tight">
                            {{ str_replace('_', ' ', $maintenance->type ?? $maintenance->type_acte ?? 'Non défini') }}
                        </p>
                    </div>

                    <div class="p-6 border-b md:border-b-0 md:border-r border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Kilométrage (Acte)</p>
                        <p class="text-2xl font-black text-gray-900 mt-2 font-mono">
                            {{ number_format($maintenance->kilometrage_au_moment_de_l_acte ?? 0, 0, ',', ' ') }} <span class="text-xs font-medium text-gray-400">km</span>
                        </p>
                    </div>

                    <div class="p-6">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Coût Facturé</p>
                        <p class="text-2xl font-black text-emerald-600 mt-2 font-mono">
                            {{ number_format($maintenance->cout ?? $maintenance->prix ?? 0, 2, ',', ' ') }} <span class="text-xs font-medium text-gray-400">€ TTC</span>
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Véhicule Assigné</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Date de l'acte</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Compteur Actuel</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Statut Acte</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50/50 transition duration-150">
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($maintenance->vehicle)
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900 uppercase tracking-tight">
                                                {{ $maintenance->vehicle->marque }}
                                            </span>
                                            <span class="text-xs text-indigo-500 font-black">
                                                {{ $maintenance->vehicle->immatriculation }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs text-red-500 italic">Aucun véhicule lié</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-700">
                                        @php
                                            $dateBrute = $maintenance->date_maintenance ?? $maintenance->date_entretien ?? null;
                                        @endphp
                                        {{ $dateBrute ? \Carbon\Carbon::parse($dateBrute)->format('d/m/Y') : 'Date inconnue' }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 font-medium uppercase tracking-tighter">Enregistré</div>
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs font-mono font-bold text-gray-600 border border-gray-200">
                                        {{ $maintenance->vehicle ? number_format($maintenance->vehicle->kilometrage_actuel, 0, ',', ' ') . ' km' : 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border bg-emerald-100 text-emerald-700 border-emerald-200 shadow-sm shadow-emerald-50">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full"></span>
                                        Effectué
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white shadow-xl sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Notes & Rapport d'intervention</h3>
                </div>
                <div class="p-6">
                    @php
                        // On teste les 3 variantes de noms de colonnes les plus courantes pour le texte
                        $rapportText = $maintenance->description ?? $maintenance->notes ?? $maintenance->rapport ?? null;
                    @endphp

                    @if($rapportText)
                        <p class="text-sm font-bold text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg border border-gray-200/60 font-medium">
                            {{ $rapportText }}
                        </p>
                    @else
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest italic text-center py-4">Aucun détail consigné dans la base de données.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>