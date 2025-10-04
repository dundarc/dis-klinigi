<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <!-- Modern Header -->
    <div class="bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 dark:from-teal-800 dark:via-cyan-800 dark:to-blue-800 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black/10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 0%, transparent 50%);"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg ring-4 ring-white/10">
                            <i class="fas fa-edit text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-white tracking-tight">Tedarikçi Düzenle</h1>
                        <p class="mt-2 text-teal-100 text-lg">{{ $supplier->name }} - Bilgilerini güncelleyin ve yönetin</p>
                        <div class="mt-3 flex items-center space-x-4 text-sm text-teal-200">
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                Son güncelleme: {{ $supplier->updated_at->format('d.m.Y H:i') }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $supplier->type === 'service' ? 'Hizmet' : 'Tedarikçi' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Quick Actions -->
                    <div class="hidden md:flex items-center space-x-3">
                        <a href="{{ route('stock.suppliers.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Liste
                        </a>
                        <a href="{{ route('stock.current.show', $supplier) }}"
                           class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-eye mr-2"></i>
                            Cari
                        </a>
                    </div>

                    <!-- Danger Actions -->
                    <div class="flex items-center space-x-3">
                        <button onclick="confirmDelete()"
                               class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-trash mr-2"></i>
                            Sil
                        </button>
                    </div>

                    <!-- Mobile Menu -->
                    <div class="md:hidden">
                        <button onclick="toggleMobileMenu()"
                                class="inline-flex items-center p-3 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 -mt-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Form Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Current Supplier Info -->
                <div class="bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 border border-teal-200 dark:border-teal-800 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-16 w-16 bg-gradient-to-br from-teal-100 to-teal-200 dark:from-teal-800 dark:to-teal-700 rounded-2xl flex items-center justify-center shadow-lg ring-4 ring-white dark:ring-gray-800">
                                <i class="fas fa-building text-teal-600 dark:text-teal-400 text-2xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-teal-900 dark:text-teal-100">{{ $supplier->name }}</h3>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
                                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tür</div>
                                    <div class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                                        {{ $supplier->type === 'service' ? 'Hizmet' : 'Tedarikçi' }}
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
                                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Şehir</div>
                                    <div class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                                        {{ $supplier->city ?? 'Belirtilmemiş' }}
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
                                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Durum</div>
                                    <div class="text-lg font-bold text-green-600 dark:text-green-400 mt-1">
                                        Aktif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Form -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-user-edit text-blue-600 dark:text-blue-400 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tedarikçi Bilgileri</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Bilgileri güncelleyin ve kaydedin</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('stock.suppliers.update', $supplier) }}"
                          x-data="{ hasChanges: false, isSubmitting: false }"
                          @change="hasChanges = true"
                          @submit="isSubmitting = true"
                          class="p-8 space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information Section -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-600 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    Temel Bilgiler
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tedarikçinin temel bilgilerini düzenleyin</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Supplier Name -->
                                <div class="space-y-3">
                                    <label for="name" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                        Tedarikçi Adı <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="name" name="name" type="text"
                                               value="{{ old('name', $supplier->name) }}"
                                               class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm"
                                               placeholder="Tedarikçi adını girin" required>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <i class="fas fa-building text-gray-400"></i>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Supplier Type -->
                                <div class="space-y-3">
                                    <label for="type" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                        Tedarikçi Türü
                                    </label>
                                    <div class="relative">
                                        <select id="type" name="type"
                                                class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm">
                                            <option value="supplier" @selected(old('type', $supplier->type) === 'supplier')>Tedarikçi (Malzeme)</option>
                                            <option value="service" @selected(old('type', $supplier->type) === 'service')>Hizmet (Servis)</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <i class="fas fa-tag text-gray-400"></i>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-600 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-address-book text-green-500 mr-2"></i>
                                    İletişim Bilgileri
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tedarikçi ile iletişim kurmak için gerekli bilgiler</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email -->
                                <div class="space-y-3">
                                    <label for="email" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                        E-posta Adresi
                                    </label>
                                    <div class="relative">
                                        <input id="email" name="email" type="email"
                                               value="{{ old('email', $supplier->email) }}"
                                               class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm"
                                               placeholder="ornek@firma.com">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Phone -->
                                <div class="space-y-3">
                                    <label for="phone" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                        Telefon Numarası
                                    </label>
                                    <div class="relative">
                                        <input id="phone" name="phone" type="text"
                                               value="{{ old('phone', $supplier->phone) }}"
                                               class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm"
                                               placeholder="+90 555 123 45 67">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <i class="fas fa-phone text-gray-400"></i>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Legal & Location Information Section -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-600 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-gavel text-purple-500 mr-2"></i>
                                    Yasal & Konum Bilgileri
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Vergi ve adres bilgileri</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tax Number -->
                                <div class="space-y-3">
                                    <label for="tax_number" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                        Vergi Numarası
                                    </label>
                                    <div class="relative">
                                        <input id="tax_number" name="tax_number" type="text"
                                               value="{{ old('tax_number', $supplier->tax_number) }}"
                                               class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm font-mono"
                                               placeholder="1234567890">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <i class="fas fa-hashtag text-gray-400"></i>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('tax_number')" class="mt-2" />
                                </div>

                                <!-- City -->
                                <div class="space-y-3">
                                    <label for="city" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                        Şehir
                                    </label>
                                    <div class="relative">
                                        <input id="city" name="city" type="text"
                                               value="{{ old('city', $supplier->city) }}"
                                               class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm"
                                               placeholder="İstanbul">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="space-y-3">
                                <label for="address" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                    Adres
                                </label>
                                <div class="relative">
                                    <textarea id="address" name="address" rows="4"
                                              class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm resize-vertical"
                                              placeholder="Tedarikçinin tam adresini girin">{{ old('address', $supplier->address) }}</textarea>
                                    <div class="absolute top-3 right-3 pointer-events-none">
                                        <i class="fas fa-map text-gray-400"></i>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 pt-8 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-info-circle"></i>
                                <span>Değişiklikler otomatik olarak algılanır</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('stock.suppliers.index') }}"
                                   class="inline-flex items-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm">
                                    <i class="fas fa-times mr-2"></i>
                                    İptal
                                </a>
                                <button type="submit"
                                        :disabled="!hasChanges || isSubmitting"
                                        :class="!hasChanges || isSubmitting ? 'bg-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800'"
                                        class="inline-flex items-center px-8 py-3 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:transform-none disabled:shadow-none">
                                    <i class="fas fa-save mr-2" x-show="!isSubmitting"></i>
                                    <i class="fas fa-spinner fa-spin mr-2" x-show="isSubmitting" x-cloak></i>
                                    <span x-text="isSubmitting ? 'Kaydediliyor...' : 'Değişiklikleri Kaydet'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructional Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <!-- Form Status -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-tasks text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100">Form Durumu</h3>
                                    <p class="text-sm text-blue-600 dark:text-blue-400">Düzenleme ilerlemesi</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Temel Bilgiler</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        <i class="fas fa-check mr-1"></i>
                                        Tamamlandı
                                    </span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">İletişim Bilgileri</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                        <i class="fas fa-clock mr-1"></i>
                                        Düzenleniyor
                                    </span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Yasal Bilgiler</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                        <i class="fas fa-circle mr-1"></i>
                                        Bekliyor
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Guide -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-question-circle text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-green-900 dark:text-green-100">Düzenleme Kılavuzu</h3>
                                    <p class="text-sm text-green-600 dark:text-green-400">Nasıl düzenlenir?</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Step 1 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Bilgileri İnceleyin</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    Üst kısımdaki kartta mevcut bilgileri kontrol edin.
                                </p>
                            </div>

                            <!-- Step 2 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Alanları Düzenleyin</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    Gerekli alanları güncelleyin. Yıldızlı alanlar zorunludur.
                                </p>
                            </div>

                            <!-- Step 3 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Kaydedin</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    Değişiklikleriniz algılandığında kaydet butonu aktif olur.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Field Explanations -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-info-circle text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-purple-900 dark:text-purple-100">Alan Açıklamaları</h3>
                                    <p class="text-sm text-purple-600 dark:text-purple-400">Ne için kullanılır?</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <h5 class="font-semibold text-gray-900 dark:text-white text-sm">Tedarikçi Türü</h5>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        Malzeme tedarik eden veya hizmet sağlayan firma türünü belirtir.
                                    </p>
                                </div>

                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <h5 class="font-semibold text-gray-900 dark:text-white text-sm">Vergi Numarası</h5>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        Fatura ve resmi işlemler için kullanılır.
                                    </p>
                                </div>

                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <h5 class="font-semibold text-gray-900 dark:text-white text-sm">İletişim Bilgileri</h5>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        Tedarikçi ile hızlı iletişim için gereklidir.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips & Warnings -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-amber-600 dark:text-amber-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-amber-900 dark:text-amber-100">İpuçları</h3>
                                    <p class="text-sm text-amber-600 dark:text-amber-400">Verimli düzenleme</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-save text-green-500 mt-0.5"></i>
                                    <div>
                                        <h5 class="font-semibold text-gray-900 dark:text-white text-sm">Otomatik Kaydetme</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Form değiştiğinde kaydet butonu otomatik aktif olur.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-undo text-blue-500 mt-0.5"></i>
                                    <div>
                                        <h5 class="font-semibold text-gray-900 dark:text-white text-sm">İptal Etme</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            İptal butonuyla değişiklikleriniz kaydedilmez.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-exclamation-triangle text-red-500 mt-0.5"></i>
                                    <div>
                                        <h5 class="font-semibold text-gray-900 dark:text-white text-sm">Silme İşlemi</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Silme işlemi geri alınamaz. Dikkatli kullanın.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keyboard Shortcuts -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-keyboard text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-100">Kısayollar</h3>
                                    <p class="text-sm text-indigo-600 dark:text-indigo-400">Hızlı işlemler</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Formu gönder</span>
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs rounded">Ctrl + Enter</kbd>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">İptal et</span>
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs rounded">Esc</kbd>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">İlk alana odaklan</span>
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs rounded">Tab</kbd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    function confirmDelete() {
        if (confirm('Bu tedarikçiyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz ve ilişkili tüm veriler etkilenebilir.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("stock.suppliers.destroy", $supplier) }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';

            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Mobile menu toggle
    function toggleMobileMenu() {
        // This would show/hide mobile menu
        showNotification('Mobil menü yakında eklenecek.', 'info');
    }

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-2xl transform translate-x-full transition-all duration-300 max-w-md ${
            type === 'success' ? 'bg-green-500 border border-green-400' :
            type === 'error' ? 'bg-red-500 border border-red-400' :
            type === 'warning' ? 'bg-yellow-500 border border-yellow-400' : 'bg-blue-500 border border-blue-400'
        } text-white`;

        notification.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-${
                        type === 'success' ? 'check-circle' :
                        type === 'error' ? 'exclamation-circle' :
                        type === 'warning' ? 'exclamation-triangle' : 'info-circle'
                    } text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium">${message}</p>
                </div>
                <button class="flex-shrink-0 close-btn ml-4">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Add close functionality
        notification.querySelector('.close-btn').addEventListener('click', () => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        });

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (document.body.contains(notification)) {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }
        }, 3000);
    }

    // Sidebar responsiveness
    function handleSidebarResponsiveness() {
        const sidebar = document.querySelector('.lg\\:col-span-1');
        const mainContent = document.querySelector('.lg\\:col-span-3');

        if (window.innerWidth < 1024) { // lg breakpoint
            // On mobile/tablet, make sidebar collapsible
            if (!document.querySelector('.sidebar-toggle')) {
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'sidebar-toggle fixed bottom-6 right-6 z-40 w-14 h-14 bg-teal-600 hover:bg-teal-700 text-white rounded-full shadow-lg transition-all duration-200 lg:hidden';
                toggleBtn.innerHTML = '<i class="fas fa-question-circle"></i>';
                toggleBtn.onclick = toggleSidebar;

                // Initially hide sidebar on mobile
                sidebar.classList.add('hidden');

                document.body.appendChild(toggleBtn);
            }
        } else {
            // On desktop, always show sidebar
            sidebar.classList.remove('hidden');
            const toggleBtn = document.querySelector('.sidebar-toggle');
            if (toggleBtn) {
                toggleBtn.remove();
            }
        }
    }

    function toggleSidebar() {
        const sidebar = document.querySelector('.lg\\:col-span-1');
        const toggleBtn = document.querySelector('.sidebar-toggle');
        const icon = toggleBtn.querySelector('i');

        if (sidebar.classList.contains('hidden')) {
            sidebar.classList.remove('hidden');
            sidebar.classList.add('animate-slide-in-right');
            icon.className = 'fas fa-times';
            toggleBtn.classList.add('rotate-180');
        } else {
            sidebar.classList.add('animate-slide-out-right');
            setTimeout(() => {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('animate-slide-out-right');
            }, 300);
            icon.className = 'fas fa-question-circle';
            toggleBtn.classList.remove('rotate-180');
        }
    }

    // Initialize sidebar responsiveness
    handleSidebarResponsiveness();
    window.addEventListener('resize', handleSidebarResponsiveness);

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl + Enter to submit
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            const form = document.querySelector('form');
            if (form) {
                form.dispatchEvent(new Event('submit'));
            }
        }

        // Escape to cancel
        if (e.key === 'Escape') {
            const cancelBtn = document.querySelector('a[href*="suppliers.index"]');
            if (cancelBtn) {
                cancelBtn.click();
            }
        }
    });

    // Make functions globally available
    window.confirmDelete = confirmDelete;
    window.toggleMobileMenu = toggleMobileMenu;
});
</script>

<style>
@keyframes slide-in-right {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slide-out-right {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

.animate-slide-in-right {
    animation: slide-in-right 0.3s ease-out;
}

.animate-slide-out-right {
    animation: slide-out-right 0.3s ease-in;
}

/* Enhanced focus states */
input:focus, select:focus, textarea:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Sidebar toggle button animations */
.sidebar-toggle {
    transition: all 0.3s ease;
}

.sidebar-toggle:hover {
    transform: scale(1.1);
}

.sidebar-toggle.rotate-180 i {
    transform: rotate(180deg);
    transition: transform 0.3s ease;
}

/* Enhanced form styling */
.form-section {
    transition: all 0.3s ease;
}

.form-section:hover {
    transform: translateY(-1px);
}

/* Loading animation for submit button */
.fa-spin {
    animation: fa-spin 1s infinite linear;
}

@keyframes fa-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive design improvements */
@media (max-width: 1023px) {
    .sidebar-toggle {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
}

/* Custom scrollbar for sidebar */
.sticky .space-y-6 > div::-webkit-scrollbar {
    width: 6px;
}

.sticky .space-y-6 > div::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}

.sticky .space-y-6 > div::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 3px;
}

.sticky .space-y-6 > div::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.5);
}
</style>
@endpush
</x-app-layout>
