<div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 mt-6">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">Historique des interventions</h3>
        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold">
            {{ $vehicle->maintenances->count() }} Acte(s)
        </span>
    </div>

    <div class="p-6">
        @if($vehicle->maintenances->count() > 0)
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($vehicle->maintenances->sortByDesc('date_intervention') as $maintenance)
                    <li>
                        <div class="relative pb-8">
                            @if (!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ $maintenance->type == 'reparation' ? 'bg-red-500' : 'bg-fuchsia-500' }} text-white">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        </svg>
                                    </span>
                                </div>
                                
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">
                                            {{ ucfirst($maintenance->type) }} 
                                            <span class="font-normal text-gray-500">— {{ number_format($maintenance->cout, 2) }} €</span>
                                        </p>
                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ $maintenance->notes ?? 'Aucune note descriptive.' }}
                                        </p>
                                        <div class="mt-2 flex space-x-4 text-xs text-gray-400">
                                            <span class="flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                                {{ $maintenance->kilometrage_au_moment_de_l_acte }} km
                                            </span>
                                            <span class="flex items-center text-fuchsia-600 font-semibold">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3" /></svg>
                                                Prochain : {{ $maintenance->prochain_kilometrage_rappel }} km
                                            </span>
                                        </div>
                                    </div>
                                    <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                        <time datetime="{{ $maintenance->date_intervention }}">
                                            {{ \Carbon\Carbon::parse($maintenance->date_intervention)->format('d M Y') }}
                                        </time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-400 italic text-sm">Aucun historique disponible pour le moment.</p>
            </div>
        @endif
    </div>
</div>