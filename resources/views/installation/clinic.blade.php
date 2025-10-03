@extends('installation.layout')

@section('content')
    <div>
        <h2 class="text-2xl font-bold mb-4">Klinik Bilgileri</h2>

        <div class="mb-6">
            <p class="text-gray-600">
                Lütfen kliniğinizin temel bilgilerini girin. Bu bilgiler sistem genelinde kullanılacaktır.
            </p>
        </div>

        <form method="POST" action="{{ route('installation.clinic.save') }}">
            @csrf

            <div class="space-y-6">
                <!-- Klinik Adı -->
                <div>
                    <label for="clinic_name" class="block text-sm font-medium text-gray-700">
                        Klinik Adı
                    </label>
                    <input type="text" name="clinic_name" id="clinic_name" 
                        value="{{ old('clinic_name') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    @error('clinic_name')
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

                <!-- Telefon -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        Telefon Numarası
                    </label>
                    <input type="tel" name="phone" id="phone" 
                        value="{{ old('phone') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Adres -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">
                        Adres
                    </label>
                    <textarea name="address" id="address" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vergi Dairesi -->
                <div>
                    <label for="tax_office" class="block text-sm font-medium text-gray-700">
                        Vergi Dairesi
                    </label>
                    <input type="text" name="tax_office" id="tax_office" 
                        value="{{ old('tax_office') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    @error('tax_office')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vergi Numarası -->
                <div>
                    <label for="tax_number" class="block text-sm font-medium text-gray-700">
                        Vergi Numarası
                    </label>
                    <input type="text" name="tax_number" id="tax_number" 
                        value="{{ old('tax_number') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    @error('tax_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <a href="{{ route('installation.database') }}" 
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