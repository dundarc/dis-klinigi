<nav x-data="{
    open: false,
    darkMode: false,
    init() {
        console.log('ThemeManager init called');
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            this.darkMode = savedTheme === 'dark';
            console.log('Loaded theme from localStorage:', savedTheme);
        } else {
            this.darkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            console.log('Using system preference:', this.darkMode);
        }
        this.applyTheme();
    },
    toggleTheme() {
        console.log('toggleTheme called, current:', this.darkMode);
        this.darkMode = !this.darkMode;
        this.applyTheme();
        this.saveTheme();
        console.log('Theme toggled to:', this.darkMode);
    },
    applyTheme() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },
    saveTheme() {
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    }
}" class="bg-gradient-to-r from-white via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600 shadow-sm backdrop-blur-sm bg-opacity-95 dark:bg-opacity-95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-18">
            {{-- Sol Kısım --}}
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group flex items-center space-x-3 p-2 rounded-xl hover:bg-white/50 dark:hover:bg-gray-800/50 transition-all duration-300">
                        <div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg group-hover:scale-110 transition-transform duration-300">
                            <x-application-logo class="block h-6 w-6 fill-current text-white" />
                        </div>
                        <span class="hidden lg:block text-lg font-bold bg-gradient-to-r from-gray-800 to-gray-600 dark:from-gray-200 dark:to-gray-400 bg-clip-text text-transparent">
                            KYS
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="group">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                            </svg>
                            <span>{{ __('Dashboard') }}</span>
                        </span>
                    </x-nav-link>

                    <x-nav-link :href="route('calendar')" :active="request()->routeIs('calendar')" class="group">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ __('Takvim') }}</span>
                        </span>
                    </x-nav-link>

                    @can('viewAny', App\Models\Patient::class)
                        <x-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')" class="group">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>{{ __('Hastalar') }}</span>
                            </span>
                        </x-nav-link>
                    @endcan

                    @can('accessReceptionistFeatures')
                        <x-nav-link :href="route('waiting-room.index')" :active="request()->routeIs('waiting-room.*')" class="group">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>{{ __('Bekleme Odası') }}</span>
                            </span>
                        </x-nav-link>
                    @endcan

                    @can('accessStockManagement')
                        <x-nav-link :href="route('stock.dashboard')" :active="request()->routeIs('stock.*')" class="group">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>{{ __('Stok') }}</span>
                            </span>
                        </x-nav-link>
                    @endcan

                    @can('accessAccountingFeatures')
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="group">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>{{ __('Raporlar') }}</span>
                            </span>
                        </x-nav-link>
                        <x-nav-link :href="route('accounting.index')" :active="request()->routeIs('accounting.*')" class="group">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <span>{{ __('Muhasebe') }}</span>
                            </span>
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
                            class="group relative inline-flex items-center p-3 text-sm font-medium
                                   text-gray-600 dark:text-gray-300 bg-white/70 dark:bg-gray-800/70
                                   hover:bg-white dark:hover:bg-gray-700 rounded-xl
                                   hover:shadow-lg transition-all duration-200
                                   focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 012 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 017.132-8.317M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"></path>
                        </svg>
                        <div x-show="unreadCount > 0"
                             class="absolute -top-1 -right-1 inline-flex items-center justify-center
                                    w-6 h-6 text-xs font-bold text-white bg-gradient-to-r from-red-500 to-red-600
                                    border-2 border-white dark:border-gray-900 rounded-full shadow-lg
                                    animate-pulse"
                             x-text="unreadCount"></div>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                          class="absolute right-0 mt-3 w-80 bg-white dark:bg-gray-800
                                 rounded-xl shadow-2xl ring-1 ring-gray-200 dark:ring-gray-700
                                 border border-gray-100 dark:border-gray-600 z-50"
                          x-transition>
                        <div class="px-4 py-3 font-semibold text-center text-gray-800 dark:text-white bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 012 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 017.132-8.317M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"></path>
                                </svg>
                                <span>Bildirimler</span>
                            </span>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            <template x-if="loading">
                                <div class="p-6 text-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Yükleniyor...</p>
                                </div>
                            </template>
                            <template x-if="!loading && notifications.length === 0">
                                <div class="p-6 text-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 012 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 017.132-8.317M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Yeni bildirim yok.</p>
                                </div>
                            </template>
                            <template x-for="n in notifications" :key="n.id">
                                <a :href="n.link_url || '#'" class="flex px-4 py-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200 border-b border-gray-50 dark:border-gray-700 last:border-b-0">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200" x-text="n.title"></div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1" x-text="n.body"></div>
                                    </div>
                                    <div class="ml-3 flex-shrink-0">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full" x-show="!n.read_at"></div>
                                    </div>
                                </a>
                            </template>
                        </div>
                        <a href="{{ route('notifications.index') }}"
                           class="block py-3 text-sm font-medium text-center text-blue-600 dark:text-blue-400
                                  hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-b-xl transition-colors duration-200">
                            Tüm Bildirimleri Görüntüle
                        </a>
                    </div>
                </div>

                <!-- Dark Mode Toggle -->
                <button @click="toggleTheme()"
                        class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200"
                        aria-label="Tema değiştir"
                        title="Tema değiştir">
                    <!-- Güneş ikonu (Light mode) -->
                    <svg x-show="!darkMode" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <!-- Ay ikonu (Dark mode) -->
                    <svg x-show="darkMode" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="group inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl
                                       text-gray-600 dark:text-gray-300 bg-white/70 dark:bg-gray-800/70
                                       hover:bg-white dark:hover:bg-gray-700 hover:shadow-lg
                                       focus:ring-2 focus:ring-blue-500 focus:outline-none
                                       transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                                <div class="hidden md:block">
                                    <div class="text-left">
                                        <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->role?->name ?? 'User' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-3">
                                <svg class="h-4 w-4 transition-transform duration-200 group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
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
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-semibold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->role === \App\Enums\UserRole::ADMIN)
                            <x-dropdown-link :href="route('system.index')">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ __('Sistem Ayarları') }}</span>
                                </span>
                            </x-dropdown-link>
                        @endif

                        <x-dropdown-link :href="route('profile.edit')">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>{{ __('Profile') }}</span>
                            </span>
                        </x-dropdown-link>

                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>{{ __('Log Out') }}</span>
                                </span>
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
            <div class="px-4 flex items-center justify-between">
                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <!-- Mobile Dark Mode Toggle -->
                <button @click="toggleTheme()"
                        class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200"
                        aria-label="Tema değiştir"
                        title="Tema değiştir">
                    <!-- Güneş ikonu (Light mode) -->
                    <svg x-show="!darkMode" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <!-- Ay ikonu (Dark mode) -->
                    <svg x-show="darkMode" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
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
