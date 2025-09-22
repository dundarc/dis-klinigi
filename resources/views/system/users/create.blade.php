<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Yeni Kullanıcı Ekle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form method="POST" action="{{ route('system.users.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <!-- İsim Soyisim -->
                        <div>
                            <x-input-label for="name" value="Ad Soyad" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- E-posta Adresi -->
                        <div>
                            <x-input-label for="email" value="E-posta Adresi" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Rol -->
                        <div>
                            <x-input-label for="role" value="Kullanıcı Rolü" />
                            <x-select-input id="role" name="role" class="mt-1 block w-full" required>
                                <option value="">-- Rol Seçin --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->value }}" @selected(old('role') == $role->value)>
                                        {{ ucfirst($role->value) }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                        
                        <!-- Şifre -->
                        <div>
                            <x-input-label for="password" value="Şifre" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Şifre Tekrar -->
                        <div>
                            <x-input-label for="password_confirmation" value="Şifre (Tekrar)" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('system.users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
                                İptal
                            </a>
                            <x-primary-button class="ms-4">
                                Kullanıcıyı Oluştur
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
