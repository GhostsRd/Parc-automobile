<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Tableau de bord') }}
                    </x-nav-link> --}}
                    <x-nav-link href="{{ route('gps.index') }}" :active="request()->routeIs('gps.*')">
                        {{ __('Cartographie GPS') }}
                    </x-nav-link>

                    <div class="hidden sm:flex sm:items-center sm:ms-3">
                        @php
                        $allVehicles = \App\Models\Vehicle::all();

                        $urgentMaintenances = $allVehicles->filter(function($v) {
                        return $v->aBesoinMaintenance();
                        });
                        $maintenancesCount = $urgentMaintenances->count();

                        $upcomingMaintenances = $allVehicles->filter(function($v) {
                        $kmRoules = ($v->kilometrage_actuel ?? 0) - ($v->kilometrage_initial ?? 0);
                        return !$v->aBesoinMaintenance() && ($kmRoules >= 9000 && $kmRoules < 10000); });
                            $allDrivers=\App\Models\Driver::all(); $expiredLicenses=$allDrivers->filter(function($d) {
                            return $d->isLicenseExpired();
                            });

                            $expiringSoonLicenses = $allDrivers->filter(function($d) {
                            return !$d->isLicenseExpired() && $d->isLicenseExpiringSoon(30);
                            });

                            $totalNotifications = $urgentMaintenances->count() + $upcomingMaintenances->count() +
                            $expiredLicenses->count() + $expiringSoonLicenses->count();
                            @endphp

                            <x-dropdown align="left" width="48">

                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-1 py-2 border-b-2 {{ request()->routeIs('vehicles.*', 'mileage.*', 'documents.*') ? 'border-indigo-500 text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none cursor-pointer relative">
                                        <span>{{ __('Véhicules') }}</span>

                                        @if($maintenancesCount > 0)
                                        <span
                                            class="ms-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white animate-pulse">
                                            {{ $maintenancesCount }}
                                        </span>
                                        @endif

                                        <svg class="ms-2 -me-0.5 h-4 w-4 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link href="{{ route('vehicles.index') }}"
                                        :active="request()->routeIs('vehicles.index')">
                                        {{ __('Liste des Véhicules') }}
                                    </x-dropdown-link>

                                    <div class="border-t border-gray-100"></div>

                                    <x-dropdown-link href="{{ route('mileage.index') }}"
                                        :active="request()->routeIs('mileage.*')"
                                        class="flex justify-between items-center">
                                        <span>{{ __('Suivi Kilométrage') }}</span>
                                        @if($maintenancesCount > 0)
                                        <span
                                            class="px-1.5 py-0.5 rounded-md bg-red-100 text-[10px] font-black text-red-700 uppercase tracking-wide">
                                            {{ $maintenancesCount }}
                                        </span>
                                        @endif
                                    </x-dropdown-link>

                                    <x-dropdown-link href="{{ route('documents.index') }}"
                                        :active="request()->routeIs('documents.*')">
                                        {{ __('Documents & Alertes') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                    </div>

                    <x-nav-link href="{{ route('maintenances.index') }}" :active="request()->routeIs('maintenances.*')">
                        {{ __('Entretiens') }}
                    </x-nav-link>

                    <x-nav-link href="{{ route('fuel.index') }}" :active="request()->routeIs('fuel.*')">
                        {{ __('Suivi Carburant') }}
                    </x-nav-link>

                    <x-nav-link :href="route('drivers.index')" :active="request()->routeIs('drivers.*')">
                        {{ __('Chauffeurs') }}
                    </x-nav-link>

                    <x-nav-link href="{{ route('bookings.index') }}" :active="request()->routeIs('bookings.*')">
                        {{ __('Missions') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <div class="ms-4 relative">
                    <x-dropdown align="right" width="auto">
                        <x-slot name="trigger">
                            <button
                                class="p-2 text-gray-400 hover:text-gray-500 rounded-full hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 relative transition cursor-pointer me-2">
                                <span class="sr-only">Voir les notifications</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>

                                @if($totalNotifications > 0)
                                <span
                                    class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full ring-2 ring-white {{ $urgentMaintenances->count() > 0 || $expiredLicenses->count() > 0 ? 'animate-pulse bg-red-500' : 'animate-bounce bg-orange-500' }}"></span>
                                @endif
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="w-80 max-h-96 overflow-y-auto rounded-md shadow-lg bg-white">

                                <div
                                    class="block px-5 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 bg-gray-50 border-b border-gray-100">
                                    {{ __('Centre de Notifications') }}
                                    <span
                                        class="ms-1 px-2 py-0.5 rounded-full bg-gray-200 text-gray-700 font-black text-[10px]">
                                        {{ $totalNotifications }}
                                    </span>
                                </div>

                                @if($totalNotifications === 0)
                                <div class="px-5 py-8 text-sm text-center text-gray-400">
                                    <div class="text-2xl mb-1">✨</div>
                                    Tout est en ordre, aucune alerte en cours.
                                </div>
                                @endif

                                <div class="divide-y divide-gray-100">

                                    @foreach($expiredLicenses as $driver)
                                    <a href="{{ route('drivers.index') }}"
                                        class="block px-5 py-4 hover:bg-red-50/50 transition">
                                        <div class="flex items-start space-x-3">
                                            <span class="flex-shrink-0 text-xl pt-0.5">🪪</span>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-xs font-bold text-red-700 uppercase tracking-wide mb-0.5">
                                                    Permis Expiré</p>
                                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $driver->nom
                                                    }} {{ $driver->prenom }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5 font-medium">Action immédiate
                                                    obligatoire</p>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach

                                    @foreach($expiringSoonLicenses as $driver)
                                    <a href="{{ route('drivers.index') }}"
                                        class="block px-5 py-4 hover:bg-amber-50/50 transition">
                                        <div class="flex items-start space-x-3">
                                            <span class="flex-shrink-0 text-xl pt-0.5">⏳</span>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-xs font-bold text-amber-700 uppercase tracking-wide mb-0.5">
                                                    Échéance Permis</p>
                                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $driver->nom
                                                    }} {{ $driver->prenom }}</p>
                                                <p class="text-xs text-amber-600 font-semibold mt-1">⚠️ Expire sous 30
                                                    jours</p>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach

                                    @foreach($urgentMaintenances as $vehicle)
                                    <a href="{{ route('mileage.index') }}"
                                        class="block px-5 py-4 hover:bg-red-50/50 transition">
                                        <div class="flex items-start space-x-3">
                                            <span class="flex-shrink-0 text-xl pt-0.5">🚗</span>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-xs font-bold text-red-700 uppercase tracking-wide mb-0.5">
                                                    Vidange requise</p>
                                                <p class="text-sm font-semibold text-gray-800 truncate">{{
                                                    $vehicle->marque }} {{ $vehicle->modele }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5 font-medium">{{
                                                    $vehicle->immatriculation }}</p>
                                                <div
                                                    class="mt-2 text-[11px] font-medium text-red-600 bg-red-100/60 px-2 py-1 rounded inline-block">
                                                    Compteur : {{ number_format($vehicle->kilometrage_actuel, 0, ',', '
                                                    ') }} km
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach

                                    @foreach($upcomingMaintenances as $vehicle)
                                    @php
                                    $reste = 10000 - (($vehicle->kilometrage_actuel ?? 0) -
                                    ($vehicle->kilometrage_initial ?? 0));
                                    @endphp
                                    <a href="{{ route('mileage.index') }}"
                                        class="block px-5 py-4 hover:bg-orange-50/50 transition">
                                        <div class="flex items-start space-x-3">
                                            <span class="flex-shrink-0 text-xl pt-0.5">🔧</span>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-xs font-bold text-orange-700 uppercase tracking-wide mb-0.5">
                                                    À planifier</p>
                                                <p class="text-sm font-semibold text-gray-800 truncate">{{
                                                    $vehicle->marque }} {{ $vehicle->modele }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5 font-medium">{{
                                                    $vehicle->immatriculation }}</p>
                                                <div
                                                    class="mt-2 text-[11px] font-semibold text-orange-700 bg-orange-100/60 px-2 py-1 rounded inline-block">
                                                    Dans moins de : ~{{ number_format($reste, 0, ',', ' ') }} km
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach

                                </div>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <button
                                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                <img class="size-8 rounded-full object-cover"
                                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </button>
                            @else
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                    {{ Auth::user()->name }}
                                    <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </span>
                            @endif
                        </x-slot>
                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">{{ __('Manage Account') }}</div>
                            <x-dropdown-link href="{{ route('profile.show') }}">{{ __('Profile') }}</x-dropdown-link>
                            <div class="border-t border-gray-200"></div>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">{{
                                    __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Tableau de bord') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('fuel.index') }}" :active="request()->routeIs('fuel.*')">
                {{ __('Suivi Carburant') }}
            </x-responsive-nav-link>
        </div>
    </div>
</nav>