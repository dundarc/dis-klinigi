@extends('installation.layout')

@section('content')
    <div class="text-center">
        <h1 class="text-2xl font-bold mb-4">Diş Kliniği Yönetim Sistemine Hoş Geldiniz</h1>
        
        <div class="mb-8 text-gray-600">
            <p>Bu kurulum sihirbazı, sisteminizi hızlı ve kolay bir şekilde yapılandırmanıza yardımcı olacaktır.</p>
            <p class="mt-2">Kurulum sırasında aşağıdaki bilgilere ihtiyacınız olacak:</p>
        </div>

        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <ul class="list-disc text-left ml-6 space-y-2">
                <li>Veritabanı bağlantı bilgileri</li>
                <li>Klinik temel bilgileri</li>
                <li>Yönetici hesap bilgileri</li>
            </ul>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Kuruluma başlamadan önce lütfen tüm gereksinimlerin karşılandığından emin olun.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('installation.requirements') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Başla
                <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </a>
        </div>
    </div>
@endsection