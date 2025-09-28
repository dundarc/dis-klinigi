<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bugünün Randevuları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                 @if (session('success'))
                    <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Saat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hasta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hekim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($todaysAppointments as $appointment)
                            <tr>
                                <td class="px-6 py-4">{{ $appointment->start_at->format('H:i') }}</td>
                                <td class="px-6 py-4">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                                <td class="px-6 py-4">{{ $appointment->dentist->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($appointment->status === \App\Enums\AppointmentStatus::CHECKED_IN) bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200
                                        @elseif($appointment->status === \App\Enums\AppointmentStatus::COMPLETED) bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200
                                        @elseif($appointment->status === \App\Enums\AppointmentStatus::NO_SHOW) bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200
                                        @elseif($appointment->status === \App\Enums\AppointmentStatus::CANCELLED) bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200
                                        @endif">
                                        {{ $appointment->status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($appointment->status === \App\Enums\AppointmentStatus::SCHEDULED || $appointment->status === \App\Enums\AppointmentStatus::CONFIRMED)
                                        <div class="flex space-x-2">
                                            <form action="{{ route('appointments.checkin', $appointment) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">
                                                    Check-in Yap
                                                </button>
                                            </form>
                                            <form action="{{ route('appointments.no-show', $appointment) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }} için \'Gelmedi\' olarak işaretlemek istediğinizden emin misiniz?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors">
                                                    Gelmedi
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($appointment->status === \App\Enums\AppointmentStatus::CHECKED_IN && $appointment->encounter)
                                        <a href="{{ route('waiting-room.action', $appointment->encounter) }}" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded transition-colors">
                                            İşleme Başla
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-500">İşlem Yapıldı</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">Bugün için randevu bulunmamaktadır.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>
        </div>
    </div>
</x-app-layout>