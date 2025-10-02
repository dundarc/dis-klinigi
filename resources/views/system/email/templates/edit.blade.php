<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">E-posta Şablonunu Düzenle</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $template->name }} şablonunu düzenleyin</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('system.email.templates.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Şablonlara Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('system.email.templates.update', $template) }}" method="POST" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Şablon Adı
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $template->name) }}"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                               placeholder="Randevu Hatırlatma" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Key (disabled for edit) -->
                    <div>
                        <label for="key" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Anahtar (Düzenlenemez)
                        </label>
                        <input type="text" id="key" value="{{ $template->key }}"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-100 dark:bg-slate-600 dark:text-slate-400 cursor-not-allowed"
                               disabled>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Şablon anahtarı düzenlenemez</p>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Konu
                        </label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject', $template->subject) }}"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                               placeholder="Randevu Hatırlatma - 2025-01-15 14:00" required>
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Body HTML -->
                    <div>
                        <label for="body_html" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            HTML İçerik
                        </label>
                        <textarea id="body_html" name="body_html" rows="10"
                                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                                  placeholder="<h1>Merhaba Ahmet Yılmaz</h1>">{{ old('body_html', $template->body_html) }}</textarea>
                        @error('body_html')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Body Text -->
                    <div>
                        <label for="body_text" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Metin İçerik (Opsiyonel)
                        </label>
                        <textarea id="body_text" name="body_text" rows="5"
                                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                                  placeholder="Merhaba Ahmet Yılmaz, randevunuz 15 Ocak 2025 tarihinde saat 14:00'te">{{ old('body_text', $template->body_text) }}</textarea>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">HTML istemeyen e-posta istemcileri için düz metin versiyonu</p>
                        @error('body_text')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $template->is_active) ? 'checked' : '' }}
                                   class="rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500 dark:bg-slate-700">
                            <span class="ml-2 text-sm text-slate-700 dark:text-slate-300">Aktif</span>
                        </label>
                    </div>

                    <!-- Placeholder Guide -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Kullanılabilir Değişkenler</h4>
                        <div class="text-sm text-blue-700 dark:text-blue-300">
                            <p><strong>Hasta bilgileri:</strong> &#123;&#123; patient_name &#125;&#125;, &#123;&#123; patient_email &#125;&#125;, &#123;&#123; patient_phone &#125;&#125;</p>
                            <p><strong>Randevu bilgileri:</strong> &#123;&#123; appointment_date &#125;&#125;, &#123;&#123; appointment_time &#125;&#125;, &#123;&#123; dentist_name &#125;&#125;</p>
                            <p><strong>Klinik bilgileri:</strong> &#123;&#123; clinic_name &#125;&#125;, &#123;&#123; clinic_address &#125;&#125;, &#123;&#123; clinic_phone &#125;&#125;</p>
                            <p><strong>Fatura bilgileri:</strong> &#123;&#123; invoice_number &#125;&#125;, &#123;&#123; invoice_amount &#125;&#125;, &#123;&#123; invoice_date &#125;&#125;</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Şablonu Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summernote WYSIWYG Editor -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#body_html').summernote({
                height: 400,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link', 'picture']],
                    ['view', ['codeview']]
                ],
                placeholder: 'E-posta içeriğinizi buraya yazın...',
                callbacks: {
                    onInit: function() {
                        // Custom styling
                        $('.note-editor').css('font-family', '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif');
                    }
                }
            });
        });
    </script>
</x-app-layout>