<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Yeni Hasta Ekle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form method="POST" action="{{ route('patients.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <!-- Temel Bilgiler -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="first_name" value="Ad" />
                                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name')" required autofocus />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="last_name" value="Soyad" />
                                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name')" required />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="national_id" value="T.C. Kimlik Numarası" />
                                <x-text-input id="national_id" name="national_id" type="text" class="mt-1 block w-full" :value="old('national_id')" />
                                <x-input-error :messages="$errors->get('national_id')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="birth_date" value="Doğum Tarihi" />
                                <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date')" required />
                                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="gender" value="Cinsiyet" />
                                <x-select-input id="gender" name="gender" class="mt-1 block w-full" required>
                                    <option value="">Seçiniz...</option>
                                    <option value="male" @selected(old('gender') == 'male')>Erkek</option>
                                    <option value="female" @selected(old('gender') == 'female')>Kadın</option>
                                    <option value="other" @selected(old('gender') == 'other')>Diğer</option>
                                </x-select-input>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- İletişim Bilgileri -->
                        <div class="border-t pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                <x-input-label for="phone_primary" value="Telefon (Birincil)" />
                                <x-text-input id="phone_primary" name="phone_primary" type="text" class="mt-1 block w-full" :value="old('phone_primary')" required />
                                <x-input-error :messages="$errors->get('phone_primary')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="email" value="E-posta Adresi" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="col-span-2">
                                <x-input-label for="address_text" value="Adres" />
                                <textarea id="address_text" name="address_text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address_text') }}</textarea>
                                <x-input-error :messages="$errors->get('address_text')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Diğer Bilgiler -->
                        <div class="border-t pt-6 space-y-4">
                            <div class="flex items-center">
                                <input id="has_private_insurance" name="has_private_insurance" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(old('has_private_insurance'))>
                                <label for="has_private_insurance" class="ms-2 block text-sm text-gray-900 dark:text-gray-100">Özel Sağlık Sigortası Var</label>
                            </div>
                            
                            <div class="flex items-start gap-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-amber-600 dark:text-amber-300 mt-0.5"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 100 2 1 1 0 000-2zm-1 4a1 1 0 000 2h2a1 1 0 100-2h-2z" clip-rule="evenodd" /></svg>
                                <div class="text-sm text-slate-700 dark:text-slate-200">
                                    KVKK onayı bu ekrandan yönetilmez. Onam verme/geri alma ve yazdırma işlemleri için KVKK modülünü kullanın (Menü > KVKK).
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('patients.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
                                İptal
                            </a>
                            <x-primary-button class="ms-4">
                                Hastayı Kaydet
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
