@extends('installation.layout')

@section('content')
    <div>
        <h2 class="text-2xl font-bold mb-4">Yönetici Hesabı Oluştur</h2>

        <div class="mb-6">
            <p class="text-gray-600">
                Sisteme giriş yapacak yönetici hesabının bilgilerini girin.
                Bu hesap tam yetkili olarak oluşturulacaktır.
            </p>
        </div>

        <form method="POST" action="{{ route('installation.admin.create') }}">
            @csrf

            <div class="space-y-6">
                <!-- Ad Soyad -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Ad Soyad
                    </label>
                    <input type="text" name="name" id="name" 
                        value="{{ old('name') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- E-posta -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        E-posta Adresi
                    </label>
                    <input type="email" name="email" id="email" 
                        value="{{ old('email') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Şifre -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Şifre
                    </label>
                    <input type="password" name="password" id="password" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Şifre Tekrar -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Şifre Tekrar
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Bu hesap sistem yöneticisi olarak oluşturulacak ve tüm yetkilere sahip olacaktır.
                                Lütfen güvenli bir şifre kullanın.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <a href="{{ route('installation.clinic') }}" 
                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="mr-2 -ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Geri
                </a>

                <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    İleri
                    <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
@endsection