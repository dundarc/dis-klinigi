<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Hastalar') }}
            </h2>
            @can('create', App\Models\Patient::class)
                <x-primary-button-link href="{{ route('patients.create') }}">
                    Yeni Hasta Ekle
                </x-primary-button-link>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <!-- Arama Formu -->
                <form method="GET" action="{{ route('patients.index') }}" class="mb-6">
                    <div class="flex items-center">
                        <x-text-input id="search" name="search" type="text" class="block w-full" 
                                      placeholder="Ad, soyad, telefon veya TC ile ara..." 
                                      value="{{ $search ?? '' }}" />
                        <x-primary-button class="ms-3">
                            Ara
                        </x-primary-button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ad Soyad</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Telefon</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">KVKK Onayı</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kayıt Tarihi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($patients as $patient)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('patients.show', $patient) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $patient->phone_primary }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($patient->consent_kvkk_at)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                Onaylandı
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                Onaylanmadı
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $patient->created_at->format('d.m.Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Aradığınız kriterlere uygun hasta bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if ($patients->hasPages())
                    <div class="mt-4">
                        {{ $patients->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
