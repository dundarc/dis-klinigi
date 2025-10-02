<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">E-posta Log Detayı</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $log->subject }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('system.email.logs.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Loglara Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Log Info -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Gönderim Bilgileri</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Alıcı</label>
                        <div class="text-sm text-slate-900 dark:text-slate-100">
                            <div class="font-medium">{{ $log->to_name ?: 'İsimsiz' }}</div>
                            <div class="text-slate-500 dark:text-slate-400">{{ $log->to_email }}</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Konu</label>
                        <div class="text-sm text-slate-900 dark:text-slate-100">{{ $log->subject }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Şablon</label>
                        <div class="text-sm text-slate-900 dark:text-slate-100">{{ $log->template_key ?: 'Manuel' }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Durum</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($log->status === 'sent') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($log->status === 'failed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                            @if($log->status === 'sent') Gönderildi
                            @elseif($log->status === 'failed') Başarısız
                            @else Kuyruğa Alındı @endif
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Oluşturulma</label>
                        <div class="text-sm text-slate-900 dark:text-slate-100">{{ $log->created_at->format('d.m.Y H:i:s') }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Gönderilme</label>
                        <div class="text-sm text-slate-900 dark:text-slate-100">{{ $log->sent_at ? $log->sent_at->format('d.m.Y H:i:s') : '-' }}</div>
                    </div>

                    @if($log->message_id)
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Message ID</label>
                        <div class="text-sm text-slate-900 dark:text-slate-100 font-mono">{{ $log->message_id }}</div>
                    </div>
                    @endif

                    @if($log->mailer_alias)
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mailer Alias</label>
                        <div class="text-sm text-slate-900 dark:text-slate-100">{{ $log->mailer_alias }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Error Message -->
            @if($log->error_message)
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <h4 class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Hata Mesajı</h4>
                <div class="text-sm text-red-700 dark:text-red-300 font-mono bg-red-100 dark:bg-red-900/40 p-3 rounded">
                    {{ $log->error_message }}
                </div>
            </div>
            @endif

            <!-- Email Content -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">E-posta İçeriği</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">HTML İçerik</label>
                        <div class="border border-slate-300 dark:border-slate-600 rounded-lg p-4 bg-slate-50 dark:bg-slate-700 max-h-96 overflow-y-auto">
                            {!! $log->body_html !!}
                        </div>
                    </div>

                    @if($log->body_text)
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Metin İçerik</label>
                        <div class="border border-slate-300 dark:border-slate-600 rounded-lg p-4 bg-slate-50 dark:bg-slate-700 font-mono text-sm whitespace-pre-wrap">
                            {{ $log->body_text }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Bounces -->
            @if($log->bounces->count() > 0)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Geri Dönüşler</h3>

                <div class="space-y-4">
                    @foreach($log->bounces as $bounce)
                    <div class="border border-slate-300 dark:border-slate-600 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tür</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($bounce->bounce_type === 'hard') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @elseif($bounce->bounce_type === 'soft') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                    {{ ucfirst($bounce->bounce_type) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sağlayıcı</label>
                                <div class="text-sm text-slate-900 dark:text-slate-100">{{ $bounce->provider ?: '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tarih</label>
                                <div class="text-sm text-slate-900 dark:text-slate-100">{{ $bounce->occurred_at->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>
                        @if($bounce->raw_payload)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ham Veri</label>
                            <div class="text-xs font-mono bg-slate-100 dark:bg-slate-700 p-3 rounded max-h-32 overflow-y-auto">
                                {{ json_encode($bounce->raw_payload, JSON_PRETTY_PRINT) }}
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>