<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('AI Asistan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Chat Area -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <!-- Chat Interface -->
                            <div x-data="aiChat()" class="space-y-6">
                                <!-- Messages -->
                                <div id="messages" class="space-y-4 min-h-96 max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-900">
                                    <div x-show="messages.length === 0" class="text-center text-gray-500 dark:text-gray-400 py-8">
                                        AI asistanına soru sorun...
                                    </div>
                                    <template x-for="(message, index) in messages" :key="index">
                                        <div :class="message.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                                            <div :class="message.role === 'user' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100'"
                                                 class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg">
                                                <p x-text="message.content"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Input Form -->
                                <form @submit.prevent="sendMessage" class="flex space-x-4">
                                    <input x-model="newMessage"
                                           type="text"
                                           placeholder="Mesajınızı yazın..."
                                           class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           required>
                                    <button type="submit"
                                            :disabled="loading"
                                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium rounded-lg transition-colors">
                                        <span x-show="!loading">Gönder</span>
                                        <span x-show="loading" class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Gönderiliyor...
                                        </span>
                                    </button>
                                </form>

                                <!-- Example Prompts -->
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Örnek Sorular</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <button @click="setMessage('Stok ürünü tahmini hangi gün biter?')"
                                                class="text-left p-3 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                            <p class="font-medium text-gray-900 dark:text-gray-100">Stok Tahmini</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Stok ürünü tahmini hangi gün biter?</p>
                                        </button>
                                        <button @click="setMessage('Aylık Klinik Özeti')"
                                                class="text-left p-3 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                            <p class="font-medium text-gray-900 dark:text-gray-100">Klinik Özeti</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Aylık Klinik Özeti</p>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar with Warnings -->
                <div class="lg:col-span-1">
                    <div class="space-y-6">
                        <!-- Important Warnings -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-red-800 dark:text-red-200 mb-3">Önemli Uyarılar</h3>
                                    <div class="space-y-3 text-sm text-red-700 dark:text-red-300">
                                        <p class="flex items-start space-x-2">
                                            <span class="text-red-500 mt-1">•</span>
                                            <span>AI yanıtları tıbbi tavsiye değildir. Her zaman uzman doktorunuza danışın.</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-red-500 mt-1">•</span>
                                            <span>Klinik kararlar için AI'yi tek başına kullanmayın.</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-red-500 mt-1">•</span>
                                            <span>Acil durumlarda derhal tıbbi yardım alın.</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Privacy -->
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 border border-amber-200 dark:border-amber-800 rounded-xl p-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-amber-800 dark:text-amber-200 mb-3">Gizlilik ve Güvenlik</h3>
                                    <div class="space-y-3 text-sm text-amber-700 dark:text-amber-300">
                                        <p class="flex items-start space-x-2">
                                            <span class="text-amber-500 mt-1">•</span>
                                            <span>Hasta bilgileri paylaşmayın. AI sadece genel tavsiyeler verir.</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-amber-500 mt-1">•</span>
                                            <span>Konuşmalar eğitim amaçlı kullanılabilir.</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-amber-500 mt-1">•</span>
                                            <span>Veriler güvenli bir şekilde işlenir.</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AI Limitations -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-blue-800 dark:text-blue-200 mb-3">AI Sınırlamaları</h3>
                                    <div class="space-y-3 text-sm text-blue-700 dark:text-blue-300">
                                        <p class="flex items-start space-x-2">
                                            <span class="text-blue-500 mt-1">•</span>
                                            <span>AI gerçek zamanlı verilere erişemez.</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-blue-500 mt-1">•</span>
                                            <span>Yanıtlar genel bilgi temelindedir.</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-blue-500 mt-1">•</span>
                                            <span>Hata yapabilir, doğruluk garantisi yoktur.</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-blue-500 mt-1">•</span>
                                            <span>Sürekli öğrenir ve gelişir.</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Usage Tips -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-green-800 dark:text-green-200 mb-3">Kullanım İpuçları</h3>
                                    <div class="space-y-3 text-sm text-green-700 dark:text-green-300">
                                        <p class="flex items-start space-x-2">
                                            <span class="text-green-500 mt-1">•</span>
                                            <span>Net ve spesifik sorular sorun.</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-green-500 mt-1">•</span>
                                            <span>Konuşma geçmişini inceleyin.</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-green-500 mt-1">•</span>
                                            <span>Örnek soruları kullanın.</span>
                                        </p>
                                        <p class="flex items-start space-x-2">
                                            <span class="text-green-500 mt-1">•</span>
                                            <span>Geri bildirim verin.</span>
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
        function aiChat() {
            return {
                messages: [],
                newMessage: '',
                loading: false,

                sendMessage() {
                    if (!this.newMessage.trim()) return;

                    const userMessage = { role: 'user', content: this.newMessage };
                    this.messages.push(userMessage);
                    const messageToSend = this.newMessage;
                    this.newMessage = '';
                    this.loading = true;

                    fetch('/ai', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: messageToSend })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const aiMessage = {
                                role: 'assistant',
                                content: data.data.choices[0].message.content
                            };
                            this.messages.push(aiMessage);
                        } else {
                            this.messages.push({
                                role: 'assistant',
                                content: 'Üzgünüm, bir hata oluştu: ' + (data.error || 'Bilinmeyen hata')
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.messages.push({
                            role: 'assistant',
                            content: 'Üzgünüm, bağlantı hatası oluştu.'
                        });
                    })
                    .finally(() => {
                        this.loading = false;
                        this.$nextTick(() => {
                            const messagesEl = document.getElementById('messages');
                            messagesEl.scrollTop = messagesEl.scrollHeight;
                        });
                    });
                },

                setMessage(message) {
                    this.newMessage = message;
                }
            }
        }
    </script>
</x-app-layout>