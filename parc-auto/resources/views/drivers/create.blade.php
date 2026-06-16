<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-tight">
            Ajouter un <span class="text-indigo-600">Nouveau Chauffeur</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-xl p-8 border border-gray-100">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                        <div class="text-sm font-bold text-red-800">Veuillez corriger les erreurs suivantes :</div>
                        <ul class="mt-2 list-disc list-inside text-xs text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('drivers.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-4">
                        <h3 class="text-xs font-black text-indigo-600 uppercase tracking-wider">Informations d'identité</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Matricule Chauffeur</label>
                                <input type="text" name="matricule" value="{{ old('matricule') }}" required placeholder="Ex: CH-2026-001"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Nom et Prénom</label>
                                <input type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="Ex: Jean Dupont"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Numéro CIN</label>
                                <input type="text" name="cin" value="{{ old('cin') }}" required placeholder="Ex: 101234567"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Téléphone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Ex: +261 34 00 000 00"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Adresse de résidence</label>
                            <input type="text" name="address" value="{{ old('address') }}" placeholder="Ex: Lot IV M 25 Rue Pasteur"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        </div>
                    </div>

                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-4">
                        <h3 class="text-xs font-black text-indigo-600 uppercase tracking-wider">Suivi du Permis & Documents</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">N° de Permis de Conduire</label>
                                <input type="text" name="license_number" value="{{ old('license_number') }}" required placeholder="Ex: 85/A/4587"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Catégorie</label>
                                <select name="license_category" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="" disabled selected>Sélectionner...</option>
                                    <option value="B" {{ old('license_category') == 'B' ? 'selected' : '' }}>Catégorie B (Léger)</option>
                                    <option value="C" {{ old('license_category') == 'C' ? 'selected' : '' }}>Catégorie C (Poids Lourd)</option>
                                    <option value="D" {{ old('license_category') == 'D' ? 'selected' : '' }}>Catégorie D (Transport)</option>
                                    <option value="E" {{ old('license_category') == 'E' ? 'selected' : '' }}>Catégorie E (Remorque)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Date de délivrance</label>
                                <input type="date" name="license_issued_at" value="{{ old('license_issued_at') }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Date d'expiration (Alerte)</label>
                                <input type="date" name="license_expires_at" value="{{ old('license_expires_at') }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-3">
                        <h3 class="text-xs font-black text-indigo-600 uppercase tracking-wider">Affectation à un ou plusieurs véhicules</h3>
                        <p class="text-xs text-gray-400 font-medium italic">Sélectionnez les véhicules utilisables par ce chauffeur :</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-2">
                            @forelse($vehicles as $vehicle)
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 bg-white cursor-pointer hover:bg-indigo-50/20 transition group">
                                    <input type="checkbox" name="vehicles[]" value="{{ $vehicle->id }}"
                                        {{ is_array(old('vehicles')) && in_array($vehicle->id, old('vehicles')) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4">
                                    <span class="ml-3 flex flex-col">
                                        <span class="text-xs font-bold text-gray-900 group-hover:text-indigo-600 font-mono">{{ $vehicle->matricule }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $vehicle->brand }} {{ $vehicle->model }}</span>
                                    </span>
                                </label>
                            @empty
                                <div class="col-span-full p-2 text-center text-xs text-gray-400 italic">
                                    Aucun véhicule disponible dans le parc. Créez d'abord un véhicule.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex items-center px-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-5 w-5">
                        <span class="ml-3 text-sm font-bold text-gray-700">Chauffeur actif et disponible pour le service</span>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3">
                        <a href="{{ route('drivers.index') }}" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition align-middle mt-1">Annuler</a>
                        <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-lg shadow-indigo-200 transition">
                            Enregistrer le chauffeur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>