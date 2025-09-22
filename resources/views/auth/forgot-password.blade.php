<x-guest-layout>
    <div class="mb-4 text-lg text-gray-600 dark:text-gray-400">
        {{ __('Şifrenizi unuttuysanız, yönetici ile iletişime geçin. Güvenlik amacıyla buradan şifre sıfırlama işlemleri bloke edilmiştir.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    
</x-guest-layout>
