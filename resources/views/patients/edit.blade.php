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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2">
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
                                <label for="emergency_contact_person" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Acil Durum Kişisi</label>
                                <input id="emergency_contact_person" name="emergency_contact_person" type="text" value="{{ old('emergency_contact_person', $patient->emergency_contact_person) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <x-input-error :messages="$errors->get('emergency_contact_person')" class="mt-2" />
                            </div>
                            <div>
                                <label for="emergency_contact_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Acil Durum Telefonu</label>
                                <input id="emergency_contact_phone" name="emergency_contact_phone" type="text" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
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
                            <div>
                                <label for="general_notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Genel Notlar</label>
                                <textarea id="general_notes" name="general_notes" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('general_notes', $patient->general_notes) }}</textarea>
                                <x-input-error :messages="$errors->get('general_notes')" class="mt-2" />
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
                            
                                <div class="flex items-start gap-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-amber-600 dark:text-amber-300 mt-0.5"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 100 2 1 1 0 000-2zm-1 4a1 1 0 000 2h2a1 1 0 100-2h-2z" clip-rule="evenodd" /></svg>
                                    <div class="text-sm text-slate-700 dark:text-slate-200">
                                        KVKK onayı bu ekrandan yönetilmez. Onam verme/geri alma ve yazdırma işlemleri için KVKK modülünü kullanın (Menü > KVKK).
                                    </div>
                                </div>

                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('patients.show', $patient) }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <button type="submit" :disabled="!hasChanges" :class="hasChanges ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-400 cursor-not-allowed'" class="px-6 py-2 text-white font-medium rounded-lg transition-colors">
                            Kaydet
                        </button>
                    </div>
                </form>
                    </div>
                </div>

                <!-- Help Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kullanım Kılavuzu
                        </h3>
                        <div class="space-y-4 text-sm text-blue-800 dark:text-blue-200">
                            <div>
                                <h4 class="font-medium mb-2">Temel Bilgiler</h4>
                                <p>Ad, soyad, T.C. kimlik numarası ve doğum tarihi zorunlu alanlardır. Bu bilgiler hasta kaydının temelini oluşturur.</p>
                            </div>
                            <div>
                                <h4 class="font-medium mb-2">İletişim Bilgileri</h4>
                                <p>Ana telefon numarası zorunludur. İkincil telefon ve e-posta adresi isteğe bağlıdır. Adres bilgisi tam olarak girilmelidir.</p>
                            </div>
                            <div>
                                <h4 class="font-medium mb-2">Acil Durum Bilgileri</h4>
                                <p>Acil durumda ulaşılacak kişinin adı ve telefon numarası kaydedilir. Bu bilgiler hayati önem taşır.</p>
                            </div>
                            <div>
                                <h4 class="font-medium mb-2">Tıbbi Bilgiler</h4>
                                <p>Kullanılan ilaçlar ve genel notlar alanları hastanın tıbbi geçmişini takip etmek için kullanılır.</p>
                            </div>
                            <div>
                                <h4 class="font-medium mb-2">Özel Sağlık Sigortası</h4>
                                <p>Hastanın özel sağlık sigortası olup olmadığı işaretlenir. Bu bilgi faturalandırma işlemlerinde kullanılır.</p>
                            </div>
                            <div>
                                <h4 class="font-medium mb-2">Kaydetme</h4>
                                <p>Bilgilerde değişiklik yaptığınızda "Kaydet" butonu aktif olur. Değişiklik yapmadan kaydetmeye çalışırsanız buton devre dışı kalır.</p>
                            </div>
                            <div>
                                <h4 class="font-medium mb-2">Dikkat</h4>
                                <p>Silme işlemi geri alınamaz. Hasta silindiğinde tüm ilişkili veriler etkilenir.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6" x-data="{ confirmDelete: false }">
                            <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Silme İşlemi</h4>
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <p> Hasta silme işlemleri KVKK modülü üzerinden sağlanır. Lütfen hasta silme işlemleri işlemini KVKK modülünden çözün. Hasta silme işlemleri ADMIN (Yönetici) yetkileri ile yapılabilen bir işlemdir </p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
