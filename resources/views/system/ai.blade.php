<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('AI Ayarları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Settings Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <form method="POST" action="{{ route('system.ai.update') }}">
                                @csrf
                                @method('PUT')

                                <!-- API Key -->
                                <div class="mb-6">
                                    <x-input-label for="api_key" value="API Anahtarı" />
                                    <x-text-input id="api_key"
                                                name="api_key"
                                                type="password"
                                                class="mt-1 block w-full"
                                                :value="old('api_key', $settings?->api_key)"
                                                placeholder="sk-... şeklinde başlayan API anahtarınızı girin" />
                                    <x-input-error :messages="$errors->get('api_key')" class="mt-2" />
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        API anahtarınız güvenli bir şekilde saklanacaktır.
                                    </p>
                                </div>

                                <!-- Base URL -->
                                <div class="mb-6">
                                    <x-input-label for="base_url" value="API Base URL" />
                                    <x-text-input id="base_url"
                                                name="base_url"
                                                type="url"
                                                class="mt-1 block w-full"
                                                :value="old('base_url', $settings?->base_url)"
                                                placeholder="https://api.openai.com/v1" />
                                    <x-input-error :messages="$errors->get('base_url')" class="mt-2" />
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        OpenAI API için varsayılan: https://api.openai.com/v1
                                    </p>
                                </div>

                                <!-- Status -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Durum</h3>
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">API Anahtarı:</span>
                                            <span class="text-sm" :class="$settings?->api_key ? 'text-green-600' : 'text-red-600'">
                                                {{ $settings?->api_key ? 'Ayarlandı' : 'Ayarlanmadı' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Base URL:</span>
                                            <span class="text-sm text-blue-600">
                                                {{ $settings?->base_url ?: 'Varsayılan kullanılacak' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-end">
                                    <x-primary-button>
                                        {{ __('Kaydet') }}
                                    </x-primary-button>
                                </div>
                            </form>

                            <!-- Test Section -->
                            <div x-data="aiTest()" class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">API Testi</h3>
                                <button type="button"
                                        @click="testConnection"
                                        :disabled="testing"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white font-medium rounded-lg transition-colors">
                                    <span x-show="!testing">Bağlantıyı Test Et</span>
                                    <span x-show="testing" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                        Test ediliyor...
                                    </span>
                                </button>
                                <p x-show="testResult" x-text="testResult" class="mt-2 text-sm" :class="testSuccess ? 'text-green-600' : 'text-red-600'"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documentation Sidebar -->
                <div class="lg:col-span-1">
                    <div class="space-y-6">
                        <!-- API Key Guide -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-blue-800 dark:text-blue-200 mb-3">API Anahtarı</h3>
                                    <div class="space-y-3 text-sm text-blue-700 dark:text-blue-300">
                                        <p><strong>Nedir?</strong> AI servisi ile iletişim kurmak için kullanılan güvenlik anahtarı.</p>
                                        <p><strong>Güvenlik:</strong> Anahtar şifrelenmiş olarak saklanır, kimse görüntüleyemez.</p>
                                        <p><strong>Farklı Sağlayıcılar:</strong></p>
                                        <ul class="list-disc list-inside ml-4 space-y-1">
                                            <li><strong>OpenAI:</strong> sk- ile başlayan anahtar</li>
                                            <li><strong>Anthropic:</strong> sk-ant- ile başlayan anahtar</li>
                                            <li><strong>Google:</strong> Genellikle JSON formatında</li>
                                            <li><strong>OpenRouter:</strong> sk-or-v1- ile başlayan anahtar</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Base URL Guide -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-green-800 dark:text-green-200 mb-3">API Base URL</h3>
                                    <div class="space-y-3 text-sm text-green-700 dark:text-green-300">
                                        <p><strong>Nedir?</strong> AI API servisinin erişim adresi.</p>
                                        <p><strong>Popüler Sağlayıcılar:</strong></p>
                                        <ul class="list-disc list-inside ml-4 space-y-1">
                                            <li><strong>OpenAI:</strong> https://api.openai.com/v1</li>
                                            <li><strong>Anthropic:</strong> https://api.anthropic.com</li>
                                            <li><strong>Google Gemini:</strong> https://generativelanguage.googleapis.com</li>
                                            <li><strong>OpenRouter:</strong> https://openrouter.ai/api/v1</li>
                                            <li><strong>Groq:</strong> https://api.groq.com/openai/v1</li>
                                            <li><strong>Together AI:</strong> https://api.together.xyz/v1</li>
                                            <li><strong>Replicate:</strong> https://api.replicate.com</li>
                                        </ul>
                                        <p><strong>Önemli:</strong> Her sağlayıcının kendi URL formatı vardır.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Configuration Steps -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-800 rounded-xl p-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-purple-800 dark:text-purple-200 mb-3">Yapılandırma Adımları</h3>
                                    <div class="space-y-3 text-sm text-purple-700 dark:text-purple-300">
                                        <div class="flex items-start space-x-2">
                                            <span class="bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mt-0.5">1</span>
                                            <span>OpenAI hesabınıza giriş yapın</span>
                                        </div>
                                        <div class="flex items-start space-x-2">
                                            <span class="bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mt-0.5">2</span>
                                            <span>API Keys bölümüne gidin</span>
                                        </div>
                                        <div class="flex items-start space-x-2">
                                            <span class="bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mt-0.5">3</span>
                                            <span>Yeni bir API anahtarı oluşturun</span>
                                        </div>
                                        <div class="flex items-start space-x-2">
                                            <span class="bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mt-0.5">4</span>
                                            <span>Anahtarı güvenli bir yerde saklayın</span>
                                        </div>
                                        <div class="flex items-start space-x-2">
                                            <span class="bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mt-0.5">5</span>
                                            <span>Bu sayfada API anahtarını girin</span>
                                        </div>
                                        <div class="flex items-start space-x-2">
                                            <span class="bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mt-0.5">6</span>
                                            <span>Base URL'yi ayarlayın (varsayılan bırakılabilir)</span>
                                        </div>
                                        <div class="flex items-start space-x-2">
                                            <span class="bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mt-0.5">7</span>
                                            <span>"Bağlantıyı Test Et" ile çalışıp çalışmadığını kontrol edin</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Notes -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-red-800 dark:text-red-200 mb-3">Güvenlik Notları</h3>
                                    <div class="space-y-3 text-sm text-red-700 dark:text-red-300">
                                        <p class="flex items-start space-x-2">
                                            <span class="text-red-500 mt-1">•</span>
                                            <span>API anahtarınızı kimseyle paylaşmayın</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-red-500 mt-1">•</span>
                                            <span>Anahtar veritabanında şifrelenmiş olarak saklanır</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-red-500 mt-1">•</span>
                                            <span>API kullanımınız için ücret ödersiniz</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-red-500 mt-1">•</span>
                                            <span>Kullanım limitlerinizi takip edin</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('aiTest', () => ({
                testing: false,
                testResult: '',
                testSuccess: false,

                testConnection() {
                    this.testing = true;
                    this.testResult = '';

                    fetch('/ai', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: 'Merhaba, test mesajı' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.testResult = 'Bağlantı başarılı! AI yanıt verdi.';
                            this.testSuccess = true;
                        } else {
                            this.testResult = 'Hata: ' + (data.error || 'Bilinmeyen hata');
                            this.testSuccess = false;
                        }
                    })
                    .catch(error => {
                        this.testResult = 'Bağlantı hatası: ' + error.message;
                        this.testSuccess = false;
                    })
                    .finally(() => {
                        this.testing = false;
                    });
                }
            }));
        });
    </script>
</x-app-layout>