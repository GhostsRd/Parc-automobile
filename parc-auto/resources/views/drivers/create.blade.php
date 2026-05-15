<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Ajouter un <span class="text-indigo-600">Nouveau Chauffeur</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-xl p-8 border border-gray-100">
                <form action="{{ route('drivers.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Nom Complet</label>
                        <input type="text" name="full_name" required 
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Téléphone</label>
                            <input type="text" name="phone" 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">N° de Permis</label>
                            <input type="text" name="license_number" 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" checked 
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-5 w-5">
                        <span class="ml-3 text-sm font-bold text-gray-700">Chauffeur actif</span>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3">
                        <a href="{{ route('drivers.index') }}" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition">Annuler</a>
                        <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-lg shadow-indigo-200 transition">
                            Créer le profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>