<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Hasta Detay: {{ $patient->first_name }} {{ $patient->last_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Hasta Bilgileri, Tedaviler, Faturalar, Dosyalar için sekmeli bir yapı (tabs) --}}
            {{-- Örnek olarak sadece Tedaviler bölümünü detaylı yapalım --}}
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Uygulanan Tedaviler</h3>
                @can('create', App\Models\PatientTreatment::class)
                    {{-- Yeni Tedavi Ekleme Modalı ve Butonu --}}
                @endcan
                
                <ul>
                @forelse($patient->treatments as $treatment)
                    <li>{{ $treatment->treatment->name }} - {{ $treatment->performed_at?->format('d.m.Y') }} (Hekim: {{ $treatment->dentist->name }})</li>
                @empty
                    <li>Hastaya uygulanmış bir tedavi bulunmamaktadır.</li>
                @endforelse
                </ul>
            </x-card>
        </div>
    </div>
</x-app-layout>