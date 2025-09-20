<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Randevu Takvimi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @can('viewAny', App\Models\Appointment::class)
                    <div class="mb-4">
                        <label for="dentist_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hekim Filtrele</label>
                        <select id="dentist_filter" name="dentist_filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Tüm Hekimler</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endcan

                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/calendar.js'])
    @endpush
</x-app-layout>