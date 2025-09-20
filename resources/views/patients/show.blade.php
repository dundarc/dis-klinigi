<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Hasta Detay: {{ $patient->first_name }} {{ $patient->last_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Genel Hasta Bilgileri Bölümü --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Hasta Bilgileri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Ad Soyad:</p>
                        <p class="mt-1">{{ $patient->first_name }} {{ $patient->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400">T.C. Kimlik No:</p>
                        <p class="mt-1">{{ $patient->national_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Telefon:</p>
                        <p class="mt-1">{{ $patient->phone_primary }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400">E-posta:</p>
                        <p class="mt-1">{{ $patient->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Doğum Tarihi:</p>
                        <p class="mt-1">{{ $patient->birth_date?->format('d.m.Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Adres:</p>
                        <p class="mt-1">{{ $patient->address_text }}</p>
                    </div>
                </div>
            </div>

            {{-- Uygulanan Tedaviler Bölümü --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Uygulanan Tedaviler</h3>
                    @can('create', App\Models\PatientTreatment::class)
                        <a href="{{ route('patients.treatments.create', $patient) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Yeni Tedavi Ekle
                        </a>
                    @endcan
                </div>
                <ul>
                    @forelse($patient->treatments as $treatment)
                        <li>{{ $treatment->treatment->name }} - {{ $treatment->performed_at?->format('d.m.Y') }} (Hekim: {{ $treatment->dentist->name }})</li>
                    @empty
                        <li>Hastaya uygulanmış bir tedavi bulunmamaktadır.</li>
                    @endforelse
                </ul>
            </div>
            
            {{-- Röntgen Görselleri Bölümü --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Röntgen Görselleri</h3>
                
                {{-- Yeni Görsel Ekleme Formu --}}
                <form method="POST" action="{{ route('patients.x-rays.store', $patient) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <input type="file" name="image" id="image" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Yükle
                        </button>
                    </div>
                    @error('image')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </form>
                
                <div class="mt-4 border-t pt-4">
                    <p>Mevcut Görseller:</p>
                    <ul class="list-disc ml-6 mt-2">
                          @forelse ($patient->xrays as $x_ray)
                                <li>
                                    <a href="{{ Storage::url($x_ray->path) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $x_ray->name }}
                                    </a>
                                </li>
                            @empty
                                <li>Henüz eklenmiş bir röntgen görseli yok.</li>
                            @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>