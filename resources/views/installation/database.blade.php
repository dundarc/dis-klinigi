@extends('installation.layout')

@section('content')
    <div>
        <h2 class="text-2xl font-bold mb-4">Veritabanı Ayarları</h2>

        <div class="mb-6">
            <p class="text-gray-600">
                Lütfen MySQL veritabanı bağlantı bilgilerinizi girin. Eğer bu bilgilere sahip değilseniz, 
                sistem yöneticiniz ile iletişime geçin.
            </p>
        </div>

        <form method="POST" action="{{ route('installation.database.setup') }}">
            @csrf

            <div class="space-y-6">
                <!-- Hostname -->
                <div>
                    <label for="hostname" class="block text-sm font-medium text-gray-700">
                        Sunucu Adresi
                    </label>
                    <input type="text" name="hostname" id="hostname" 
                        value="{{ old('hostname', 'localhost') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    @error('hostname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Database -->
                <div>
                    <label for="database" class="block text-sm font-medium text-gray-700">
                        Veritabanı Adı
                    </label>
                    <input type="text" name="database" id="database" 
                        value="{{ old('database') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    @error('database')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">
                        Kullanıcı Adı
                    </label>
                    <input type="text" name="username" id="username" 
                        value="{{ old('username', 'root') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Şifre
                    </label>
                    <input type="password" name="password" id="password" 
                        value="{{ old('password') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <a href="{{ route('installation.requirements') }}" 
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