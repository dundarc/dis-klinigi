<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Klinik Detayları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('system.details.update') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="clinic_name" :value="__('Klinik Adı')" />
                                <x-text-input id="clinic_name" name="clinic_name" type="text" class="mt-1 block w-full" :value="old('clinic_name', $settings['clinic_name'] ?? '')" required autofocus />
                            </div>
                            <div>
                                <x-input-label for="clinic_phone" :value="__('Klinik Telefon Numarası')" />
                                <x-text-input id="clinic_phone" name="clinic_phone" type="text" class="mt-1 block w-full" :value="old('clinic_phone', $settings['clinic_phone'] ?? '')" />
                            </div>
                            <div>
                                <x-input-label for="clinic_email" :value="__('Klinik E-posta Adresi')" />
                                <x-text-input id="clinic_email" name="clinic_email" type="email" class="mt-1 block w-full" :value="old('clinic_email', $settings['clinic_email'] ?? '')" />
                            </div>
                            <div>
                                <x-input-label for="clinic_web" :value="__('Klinik Web Adresi')" />
                                <x-text-input id="clinic_web" name="clinic_web" type="text" class="mt-1 block w-full" :value="old('clinic_web', $settings['clinic_web'] ?? '')" />
                            </div>
                            <div>
                                <x-input-label for="clinic_tax_office" :value="__('Vergi Dairesi')" />
                                <x-text-input id="clinic_tax_office" name="clinic_tax_office" type="text" class="mt-1 block w-full" :value="old('clinic_tax_office', $settings['clinic_tax_office'] ?? '')" />
                            </div>
                            <div>
                                <x-input-label for="clinic_tax_id" :value="__('Vergi Kimlik Numarası')" />
                                <x-text-input id="clinic_tax_id" name="clinic_tax_id" type="text" class="mt-1 block w-full" :value="old('clinic_tax_id', $settings['clinic_tax_id'] ?? '')" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="clinic_address" :value="__('Adres')" />
                                <x-text-input id="clinic_address" name="clinic_address" type="text" class="mt-1 block w-full" :value="old('clinic_address', $settings['clinic_address'] ?? '')" />
                            </div>
                             <div>
                                <x-input-label for="clinic_city" :value="__('Şehir')" />
                                <x-text-input id="clinic_city" name="clinic_city" type="text" class="mt-1 block w-full" :value="old('clinic_city', $settings['clinic_city'] ?? '')" />
                            </div>
                             <div>
                                <x-input-label for="clinic_district" :value="__('İlçe')" />
                                <x-text-input id="clinic_district" name="clinic_district" type="text" class="mt-1 block w-full" :value="old('clinic_district', $settings['clinic_district'] ?? '')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Kaydet') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>