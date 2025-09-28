<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Hasta Bilgilerini Düzenle</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $patient->first_name }} {{ $patient->last_name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Hasta Bilgileri</h3>
                </div>
                <form method="POST" action="{{ route('patients.update', $patient) }}" x-data="{ hasChanges: false }" @change="hasChanges = true" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Temel Bilgiler -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ad <span class="text-red-500">*</span></label>
                            <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $patient->first_name) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Soyad <span class="text-red-500">*</span></label>
                            <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $patient->last_name) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>
                        <div>
                            <label for="national_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">T.C. Kimlik Numarası</label>
                            <input id="national_id" name="national_id" type="text" value="{{ old('national_id', $patient->national_id) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error :messages="$errors->get('national_id')" class="mt-2" />
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Doğum Tarihi <span class="text-red-500">*</span></label>
                            <input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date', optional($patient->birth_date)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Cinsiyet <span class="text-red-500">*</span></label>
                            <select id="gender" name="gender" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">Seçiniz</option>
                                <option value="male" @selected(old('gender', $patient->gender->value ?? $patient->gender) === 'male')>Erkek</option>
                                <option value="female" @selected(old('gender', $patient->gender->value ?? $patient->gender) === 'female')>Kadın</option>
                                <option value="other" @selected(old('gender', $patient->gender->value ?? $patient->gender) === 'other')>Diğer</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>
                    </div>

                    <!-- İletişim Bilgileri -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">İletişim Bilgileri</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone_primary" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Telefon (Birincil) <span class="text-red-500">*</span></label>
                                <input id="phone_primary" name="phone_primary" type="text" value="{{ old('phone_primary', $patient->phone_primary) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                                <x-input-error :messages="$errors->get('phone_primary')" class="mt-2" />
                            </div>
                            <div>
                                <label for="phone_secondary" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Telefon (İkincil)</label>
                                <input id="phone_secondary" name="phone_secondary" type="text" value="{{ old('phone_secondary', $patient->phone_secondary) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <x-input-error :messages="$errors->get('phone_secondary')" class="mt-2" />
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">E-posta</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $patient->email) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="address_text" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Adres</label>
                                <textarea id="address_text" name="address_text" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address_text', $patient->address_text) }}</textarea>
                                <x-input-error :messages="$errors->get('address_text')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Acil Durum ve Diğer Bilgiler -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Acil Durum ve Diğer Bilgiler</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="emergency_contact" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Acil Durum Kişisi</label>
                                <input id="emergency_contact" name="emergency_contact" type="text" value="{{ old('emergency_contact', $patient->emergency_contact) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <x-input-error :messages="$errors->get('emergency_contact')" class="mt-2" />
                            </div>
                            <div>
                                <label for="emergency_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Acil Durum Telefonu</label>
                                <input id="emergency_phone" name="emergency_phone" type="text" value="{{ old('emergency_phone', $patient->emergency_phone) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <x-input-error :messages="$errors->get('emergency_phone')" class="mt-2" />
                            </div>
                            <div>
                                <label for="tax_office" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Vergi Dairesi</label>
                                <input id="tax_office" name="tax_office" type="text" value="{{ old('tax_office', $patient->tax_office) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <x-input-error :messages="$errors->get('tax_office')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- İlaçlar ve Notlar -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="medications_used" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kullanılan İlaçlar</label>
                                <textarea id="medications_used" name="medications_used" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('medications_used', $patient->medications_used) }}</textarea>
                                <x-input-error :messages="$errors->get('medications_used')" class="mt-2" />
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notlar</label>
                                <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $patient->notes) }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Onaylar -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Onaylar</h4>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input id="has_private_insurance" name="has_private_insurance" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded" @checked(old('has_private_insurance', $patient->has_private_insurance)) />
                                <label for="has_private_insurance" class="ml-2 block text-sm text-slate-900 dark:text-slate-100">Özel Sağlık Sigortası Var</label>
                            </div>
                            <div class="flex items-center">
                                <input id="consent_kvkk" name="consent_kvkk" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded" @checked(old('consent_kvkk', $patient->consent_kvkk_at)) />
                                <label for="consent_kvkk" class="ml-2 block text-sm text-slate-900 dark:text-slate-100">KVKK Onayı Verildi</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('patients.show', $patient) }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <form method="POST" action="{{ route('patients.destroy', $patient) }}" onsubmit="return confirm('Bu hastayı silmek istediğinizden emin misiniz?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                Sil
                            </button>
                        </form>
                        <button type="submit" :disabled="!hasChanges" :class="hasChanges ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-400 cursor-not-allowed'" class="px-6 py-2 text-white font-medium rounded-lg transition-colors">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
