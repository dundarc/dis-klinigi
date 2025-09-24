<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- Sol Kısım --}}
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('calendar')" :active="request()->routeIs('calendar')">
                        {{ __('Takvim') }}
                    </x-nav-link>

                    @can('viewAny', App\Models\Patient::class)
                        <x-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')">
                            {{ __('Hastalar') }}
                        </x-nav-link>
                    @endcan

                    @can('accessReceptionistFeatures')
                        <x-nav-link :href="route('waiting-room.index')" :active="request()->routeIs('waiting-room.*')">
                            {{ __('Bekleme Odası') }}
                        </x-nav-link>
                    @endcan

                    @can('accessStockManagement')
                        <x-nav-link :href="route('stock.dashboard')" :active="request()->routeIs('stock.*')">
                            {{ __('Stok') }}
                        </x-nav-link>
                    @endcan

                    @can('accessAccountingFeatures')
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                            {{ __('Raporlar') }}
                        </x-nav-link>
                        <x-nav-link :href="route('accounting.index')" :active="request()->routeIs('accounting.*')">
                            {{ __('Muhasebe') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            {{-- Sağ Kısım --}}
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <!-- Bildirimler -->
                <div class="relative" x-data="{ open: false, notifications: [], unreadCount: 0, loading: true }"
                     x-init="
                        fetch('/api/v1/notifications', { headers: { 'Accept': 'application/json' } })
                            .then(res => res.json())
                            .then(data => {
                                notifications = data.data;
                                unreadCount = notifications.filter(n => !n.read_at).length;
                                loading = false;
                            });
                     ">
                    <button @click="open = ! open"
                            class="relative inline-flex items-center p-2 text-sm font-medium text-center 
                                   text-gray-500 bg-white dark:bg-gray-800 rounded-lg 
                                   hover:text-gray-900 dark:hover:text-white 
                                   focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                        </svg>
                        <div x-show="unreadCount > 0"
                             class="absolute -top-1 -right-1 inline-flex items-center justify-center 
                                    w-5 h-5 text-xs font-bold text-white bg-red-500 border-2 
                                    border-white dark:border-gray-900 rounded-full"
                             x-text="unreadCount"></div>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-700 
                                divide-y divide-gray-100 dark:divide-gray-600 
                                rounded-lg shadow z-50"
                         x-transition>
                        <div class="px-4 py-2 font-medium text-center text-gray-700 dark:text-white bg-gray-50 dark:bg-gray-700">
                            Bildirimler
                        </div>
                        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-600">
                            <template x-if="loading">
                                <p class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">Yükleniyor...</p>
                            </template>
                            <template x-if="!loading && notifications.length === 0">
                                <p class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">Yeni bildirim yok.</p>
                            </template>
                            <template x-for="n in notifications" :key="n.id">
                                <a :href="n.link_url || '#'" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <div>
                                        <div class="text-sm text-gray-600 dark:text-gray-300" x-text="n.title"></div>
                                        <div class="text-xs text-gray-900 dark:text-white" x-text="n.body"></div>
                                    </div>
                                </a>
                            </template>
                        </div>
                        <a href="{{ route('notifications.index') }}"
                           class="block py-2 text-sm font-medium text-center 
                                  text-gray-900 dark:text-white 
                                  bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-b-lg">
                            Tüm Bildirimleri Görüntüle
                        </a>
                    </div>
                </div>

                <!-- Dark Mode Toggle -->
                <div x-data="{ darkMode: localStorage.getItem('dark-mode') === 'true' }"
                     x-init="$watch('darkMode', val => localStorage.setItem('dark-mode', val))">
                    <button @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark', darkMode)"
                            class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg x-show="!darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md
                                       text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 
                                             111.414 1.414l-4 4a1 1 0 
                                             01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(auth()->user()->role === \App\Enums\UserRole::ADMIN)
                            <x-dropdown-link :href="route('system.index')">
                                {{ __('Sistem Ayarları') }}
                            </x-dropdown-link>
                        @endif

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 
                               hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('calendar')" :active="request()->routeIs('calendar')">
                {{ __('Takvim') }}
            </x-responsive-nav-link>
            @can('viewAny', App\Models\Patient::class)
                <x-responsive-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')">
                    {{ __('Hastalar') }}
                </x-responsive-nav-link>
            @endcan
            @can('accessReceptionistFeatures')
                <x-responsive-nav-link :href="route('waiting-room.index')" :active="request()->routeIs('waiting-room.*')">
                    {{ __('Bekleme Odası') }}
                </x-responsive-nav-link>
            @endcan
            @can('accessStockManagement')
                <x-responsive-nav-link :href="route('stock.dashboard')" :active="request()->routeIs('stock.*')">
                    {{ __('Stok') }}
                </x-responsive-nav-link>
            @endcan
            @can('accessAccountingFeatures')
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                    {{ __('Raporlar') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('accounting.index')" :active="request()->routeIs('accounting.*')">
                    {{ __('Muhasebe') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if(auth()->user()->role === \App\Enums\UserRole::ADMIN)
                    <x-responsive-nav-link :href="route('system.index')">
                        {{ __('Sistem Ayarları') }}
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
