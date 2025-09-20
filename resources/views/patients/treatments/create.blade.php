<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Yeni Tedavi Ekle
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('patients.treatments.store', $patient) }}" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <x-input-label for="treatment_id" :value="__('Tedavi')" />
                       <select id="treatment_id" name="treatment_id" required autofocus class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring">
                            <option value="">Lütfen bir tedavi seçin</option>
                            @foreach($treatments as $treatment)
                                <option value="{{ $treatment->id }}">{{ $treatment->name }}</option>
                            @endforeach
                        </select>

                        <x-input-error class="mt-2" :messages="$errors->get('treatment_id')" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="dentist_id" :value="__('Uygulayan Hekim')" />
                        <select id="dentist_id" name="dentist_id" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">Lütfen bir hekim seçin</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('dentist_id')" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="performed_at" :value="__('Uygulama Tarihi')" />
                        <x-text-input id="performed_at" class="block mt-1 w-full" type="date" name="performed_at" :value="old('performed_at', now()->format('Y-m-d'))" required />
                        <x-input-error class="mt-2" :messages="$errors->get('performed_at')" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="invoice_amount" :value="__('Fatura Miktarı (TL)')" />
                        <x-text-input required id="invoice_amount" class="block mt-1 w-full" type="number" step="0.01" name="invoice_amount" :value="old('invoice_amount')" />
                        <x-input-error class="mt-2" :messages="$errors->get('invoice_amount')" />
                    </div>

                    <div>
                        <x-input-label for="vat" value="KDV (%)" />
                        <x-text-input type="number" step="0.01" name="vat" id="vat" value="20" required class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('vat')" />
                    </div>


                    <div class="mt-4">
                        <x-input-label for="xray_image" :value="__('Röntgen Görseli Yükle')" />
                        <input id="xray_image" type="file" name="xray_image" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <x-input-error class="mt-2" :messages="$errors->get('xray_image')" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="notes" :value="__('Notlar')" />
                        <textarea id="notes" name="notes" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>
                            {{ __('Tedavi ve İşlemleri Ekle') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>