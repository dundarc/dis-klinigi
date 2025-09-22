<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Klinik Detaylarını Düzenle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form method="POST" action="{{ route('system.details.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" value="Klinik Adı" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $clinicDetails['name'] ?? '')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="address" value="Adres" />
                            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $clinicDetails['address'] ?? '')" required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="city" value="Şehir" />
                                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $clinicDetails['city'] ?? '')" required />
                                <x-input-error :messages="$errors->get('city')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="district" value="İlçe" />
                                <x-text-input id="district" name="district" type="text" class="mt-1 block w-full" :value="old('district', $clinicDetails['district'] ?? '')" required />
                                <x-input-error :messages="$errors->get('district')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="tax_office" value="Vergi Dairesi" />
                                <x-text-input id="tax_office" name="tax_office" type="text" class="mt-1 block w-full" :value="old('tax_office', $clinicDetails['tax_office'] ?? '')" required />
                                <x-input-error :messages="$errors->get('tax_office')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tax_id" value="Vergi Kimlik Numarası" />
                                <x-text-input id="tax_id" name="tax_id" type="text" class="mt-1 block w-full" :value="old('tax_id', $clinicDetails['tax_id'] ?? '')" required />
                                <x-input-error :messages="$errors->get('tax_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="phone" value="Klinik Telefon Numarası" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $clinicDetails['phone'] ?? '')" required />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="email" value="Klinik E-posta Adresi" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $clinicDetails['email'] ?? '')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="website" value="Klinik Web Adresi" />
                            <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" :value="old('website', $clinicDetails['website'] ?? '')" />
                            <x-input-error :messages="$errors->get('website')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('system.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
                                İptal
                            </a>
                            <x-primary-button class="ms-4">
                                Bilgileri Kaydet
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
