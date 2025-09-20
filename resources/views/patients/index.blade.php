<x-app-layout>
    {{-- HEADER --}}
    <section id="header" class="py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Hastalar') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Hasta kartlarını filtreleyerek iletişim bilgilerine, randevu geçmişine ve KVKK durumuna hızlıca ulaşın.
                    </p>
                </div>
                @can('create', App\Models\Patient::class)
                    <a href="{{ route('patients.create') }}"
                       class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        Yeni Hasta Ekle
                    </a>
                @endcan
            </div>
        </div>
    </section>

    <main class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- İSTATİSTİKLER --}}
            <section id="stats" class="space-y-0">
                <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 sm:p-6 shadow-sm">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Hasta</p>
                        <p class="mt-2 text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format($stats['total']) }}
                        </p>
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Sisteme kayıtlı tüm aktif hasta sayısı.</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 sm:p-6 shadow-sm">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bu Ay Yeni Kayıt</p>
                        <p class="mt-2 text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format($stats['newThisMonth']) }}
                        </p>
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Ay başından bugüne kadar eklenen hastalar.</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 sm:p-6 shadow-sm">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">7 Gün İçinde Randevusu Olan</p>
                        <p class="mt-2 text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format($stats['upcomingAppointments']) }}
                        </p>
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Yaklaşan randevuları bulunan hasta sayısı.</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 sm:p-6 shadow-sm">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">KVKK Onayı Bekleyen</p>
                        <p class="mt-2 text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format($stats['missingKvkk']) }}
                        </p>
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Aydınlatma ve onay süreci tamamlanmamış hastalar.</p>
                    </div>
                </div>
            </section>

            {{-- FİLTRELER --}}
            <section id="filters"
                     x-data="{ open: true }"
                     class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                <div class="px-4 sm:px-6 py-4 flex items-start sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100">Filtreler</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                            Toplam <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $patients->total() }}</span> hasta bulundu.
                        </p>
                    </div>
                    <button type="button"
                            class="sm:hidden inline-flex items-center rounded-md border border-gray-300 dark:border-gray-700 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-900/30"
                            @click="open = !open"
                            :aria-expanded="open.toString()"
                            aria-controls="filters-body">
                        Filtreleri Aç/Kapat
                    </button>
                </div>

                <div id="filters-body"
                     class="px-4 sm:px-6 pb-4"
                     x-bind:class="{'hidden': !open, 'block': open}">
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                        <div class="lg:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta Ara</label>
                            <input id="search" name="search" type="text"
                                   value="{{ $filters['search'] }}"
                                   placeholder="İsim, TCKN veya telefon"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>

                        <div>
                            <label for="insurance" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sigorta Durumu</label>
                            <select id="insurance" name="insurance"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Tümü</option>
                                <option value="private" @selected($filters['insurance'] === 'private')>Özel Sigortalı</option>
                                <option value="none" @selected($filters['insurance'] === 'none')>Sigorta Yok</option>
                            </select>
                        </div>

                        <div>
                            <label for="kvkk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">KVKK Durumu</label>
                            <select id="kvkk" name="kvkk"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Tümü</option>
                                <option value="signed" @selected($filters['kvkk'] === 'signed')>Onaylı</option>
                                <option value="missing" @selected($filters['kvkk'] === 'missing')>Eksik</option>
                            </select>
                        </div>

                        <div>
                            <label for="upcoming" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Yaklaşan Randevu</label>
                            <select id="upcoming" name="upcoming"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Tümü</option>
                                <option value="next7" @selected($filters['upcoming'] === 'next7')>7 Gün İçinde Var</option>
                                <option value="none" @selected($filters['upcoming'] === 'none')>Yaklaşan Randevu Yok</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sırala</label>
                            <select id="sort" name="sort"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="recent" @selected($filters['sort'] === 'recent')>En Yeni Kayıtlar</option>
                                <option value="oldest" @selected($filters['sort'] === 'oldest')>En Eski Kayıtlar</option>
                                <option value="name" @selected($filters['sort'] === 'name')>Alfabetik</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2 lg:col-span-5 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between pt-1">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Filtreler, sıralama ve arama birlikte çalışır.</p>
                            <div class="flex items-center justify-end gap-3">
                                @if ($filters['search'] || $filters['insurance'] || $filters['kvkk'] || $filters['upcoming'] || $filters['sort'] !== 'recent')
                                    <a href="{{ route('patients.index') }}"
                                       class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                                        Filtreleri Temizle
                                    </a>
                                @endif
                                <button class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                                    Filtreleri Uygula
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            {{-- LİSTE BAŞLIĞI --}}
            <section id="list-header" class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                <div class="px-4 sm:px-6 py-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Hasta Listesi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">İletişim bilgileri, randevu geçmişi ve KVKK bilgileriyle beraber.</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                        {{ $patients->total() }} kayıt
                    </span>
                </div>
            </section>

            {{-- MASAÜSTÜ TABLO (md ve üzeri) --}}
            <section id="table-desktop" class="hidden md:block rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/60">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-300">Hasta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-300">İletişim</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-300">KVKK &amp; Sigorta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-300">Son Randevu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-300">Yaklaşan Randevu</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-300">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @forelse ($patients as $patient)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/70 align-top">
                                    {{-- Hasta --}}
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="font-semibold truncate max-w-[240px]">
                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                        </div>
                                        <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500 dark:text-gray-400">
                                            <span>Kayıt: {{ $patient->created_at->format('d.m.Y') }}</span>
                                            <span>Yaş: {{ $patient->birth_date?->age ?? '-' }}</span>
                                            <span>Toplam Randevu: {{ $patient->appointments_count ?? 0 }}</span>
                                        </div>
                                    </td>

                                    {{-- İletişim --}}
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div>{{ $patient->phone_primary }}</div>
                                        @if ($patient->phone_secondary)
                                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">İkincil: {{ $patient->phone_secondary }}</div>
                                        @endif
                                        @if ($patient->email)
                                            <div class="mt-2 text-sm font-medium text-indigo-600 dark:text-indigo-300 break-words max-w-[260px]">
                                                {{ $patient->email }}
                                            </div>
                                        @endif
                                        @if ($patient->address_text)
                                            <div class="mt-2 max-w-xs text-xs text-gray-500 dark:text-gray-400 break-words">
                                                {{ \Illuminate\Support\Str::limit($patient->address_text, 80) }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- KVKK & Sigorta --}}
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="flex flex-wrap gap-2">
                                            @if ($patient->has_private_insurance)
                                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-200">Özel Sigorta</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700 dark:bg-slate-500/10 dark:text-slate-300">Sigorta Yok</span>
                                            @endif

                                            @if ($patient->consent_kvkk_at)
                                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">KVKK Onaylı</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">KVKK Bekleniyor</span>
                                            @endif
                                        </div>

                                        @if ($patient->consent_kvkk_at)
                                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Onay: {{ $patient->consent_kvkk_at->format('d.m.Y H:i') }}</p>
                                        @endif
                                        @if ($patient->tax_office)
                                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Vergi Dairesi: {{ $patient->tax_office }}</p>
                                        @endif
                                    </td>

                                    {{-- Son Randevu --}}
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        @php $lastAppointment = $patient->latestAppointment; @endphp
                                        @if ($lastAppointment)
                                            <div class="font-medium">{{ $lastAppointment->start_at?->format('d.m.Y H:i') }}</div>
                                            @if ($lastAppointment->status)
                                                <span class="mt-2 inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                                    {{ $lastAppointment->status->label() }}
                                                </span>
                                            @endif
                                            @if ($lastAppointment->dentist)
                                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Hekim: {{ $lastAppointment->dentist->name }}</p>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Henüz randevu oluşturulmamış.</span>
                                        @endif
                                    </td>

                                    {{-- Yaklaşan Randevu --}}
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        @php $upcomingAppointment = $patient->upcomingAppointment; @endphp
                                        @if ($upcomingAppointment)
                                            <div class="font-medium">{{ $upcomingAppointment->start_at?->format('d.m.Y H:i') }}</div>
                                            @if ($upcomingAppointment->status)
                                                <span class="mt-2 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700 dark:bg-green-500/10 dark:text-green-300">
                                                    {{ $upcomingAppointment->status->label() }}
                                                </span>
                                            @endif
                                            @if ($upcomingAppointment->dentist)
                                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Hekim: {{ $upcomingAppointment->dentist->name }}</p>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Yaklaşan randevu bulunmuyor.</span>
                                        @endif
                                    </td>

                                    {{-- İşlemler --}}
                                    <td class="px-6 py-4 text-right text-sm">
                                        @can('view', $patient)
                                            <a href="{{ route('patients.show', $patient) }}"
                                               class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                                                Detayları Gör
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Kriterlerinize uygun hasta bulunamadı. Aramanızı genişletmeyi deneyin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- MOBİL KART LİSTE (md altı) --}}
            <section id="list-mobile" class="md:hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($patients as $patient)
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900 dark:text-gray-100 truncate">
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Kayıt: {{ $patient->created_at->format('d.m.Y') }} • Yaş: {{ $patient->birth_date?->age ?? '-' }}
                                    </div>
                                </div>
                                @can('view', $patient)
                                    <a href="{{ route('patients.show', $patient) }}"
                                       class="shrink-0 inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                        Detaylar
                                    </a>
                                @endcan
                            </div>

                            <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                <div>{{ $patient->phone_primary }}</div>
                                @if ($patient->email)
                                    <div class="mt-1 font-medium text-indigo-600 dark:text-indigo-300 break-words">
                                        {{ $patient->email }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2 text-[11px]">
                                @if ($patient->has_private_insurance)
                                    <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 font-semibold text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-200">Özel Sigorta</span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-2.5 py-0.5 font-semibold text-slate-700 dark:bg-slate-500/10 dark:text-slate-300">Sigorta Yok</span>
                                @endif

                                @if ($patient->consent_kvkk_at)
                                    <span class="rounded-full bg-blue-100 px-2.5 py-0.5 font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">KVKK Onaylı</span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-2.5 py-0.5 font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">KVKK Bekleniyor</span>
                                @endif
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-4 text-xs text-gray-500 dark:text-gray-400">
                                <div>
                                    <span class="font-medium text-gray-800 dark:text-gray-200">Son Randevu</span><br>
                                    @if ($patient->latestAppointment)
                                        {{ $patient->latestAppointment->start_at?->format('d.m.Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </div>
                                <div>
                                    <span class="font-medium text-gray-800 dark:text-gray-200">Yaklaşan Randevu</span><br>
                                    @if ($patient->upcomingAppointment)
                                        {{ $patient->upcomingAppointment->start_at?->format('d.m.Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                            Kriterlerinize uygun hasta bulunamadı.
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- SAYFALAMA --}}
            <section id="pagination" class="px-0 sm:px-6">
                {{ $patients->withQueryString()->links() }}
            </section>

        </div>
    </main>
</x-app-layout>
