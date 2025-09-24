<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Randevu Analiz Raporu
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-report-filter-form :action="route('reports.appointment-analysis')" :dentists="$dentists" />

            <!-- Özet Kartları -->
            @isset($summary)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <x-summary-card title="Toplam Randevu" value="{{ $summary['total'] }}" />
                <x-summary-card title="Gerçekleşen" value="{{ $summary['completed'] }}" />
                <x-summary-card title="İptal Edilen" value="{{ $summary['cancelled'] }}" />
                <x-summary-card title="Gelmedi (No-Show)" value="{{ $summary['no_show'] }}" />
                <x-summary-card title="'Gelmedi' Oranı" value="{{ $summary['no_show_rate'] }}%" />
            </div>
            @endisset

            <!-- Gelinmeyen Randevular Listesi -->
            @isset($noShowAppointments)
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Gelinmeyen Randevular (No-Show)</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hasta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hekim</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Randevu Tarihi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($noShowAppointments as $appointment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        <a href="{{ route('patients.show', $appointment->patient) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $appointment->dentist->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $appointment->start_at->format('d.m.Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Seçilen aralıkta gelinmeyen randevu bulunamadı.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
            @endisset
        </div>
    </div>
</x-app-layout>
