<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Kullanıcıyı Düzenle: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <form method="POST" action="{{ route('system.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <!-- İsim Soyisim -->
                        <div>
                            <x-input-label for="name" value="Ad Soyad" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- E-posta Adresi -->
                        <div>
                            <x-input-label for="email" value="E-posta Adresi" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Rol -->
                        <div>
                            <x-input-label for="role" value="Kullanıcı Rolü" />
                            <x-select-input id="role" name="role" class="mt-1 block w-full" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->value }}" @selected(old('role', $user->role->value) == $role->value)>
                                        {{ ucfirst($role->value) }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Aktiflik Durumu -->
                         <div>
                            <x-input-label for="is_active" value="Kullanıcı Durumu" />
                            <x-select-input id="is_active" name="is_active" class="mt-1 block w-full" required>
                                <option value="1" @selected(old('is_active', $user->is_active) == 1)>Aktif</option>
                                <option value="0" @selected(old('is_active', $user->is_active) == 0)>Pasif</option>
                            </x-select-input>
                        </div>
                        
                        <!-- Şifre Değiştirme -->
                        <div class="border-t pt-6">
                             <p class="text-sm text-gray-600 dark:text-gray-400">Şifreyi değiştirmek istemiyorsanız bu alanı boş bırakın.</p>
                             <div class="mt-4">
                                <x-input-label for="password" value="Yeni Şifre" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                             <div class="mt-4">
                                <x-input-label for="password_confirmation" value="Yeni Şifre (Tekrar)" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('system.users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
                                İptal
                            </a>
                            <x-primary-button class="ms-4">
                                Değişiklikleri Kaydet
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </x-card>
            
            <!-- Kullanıcıyı Silme -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-red-600 dark:text-red-400">Kullanıcıyı Sil</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Bu kullanıcıyı sildiğinizde, tüm verileri kalıcı olarak yok olacaktır. Lütfen emin olmadan bu işlemi yapmayın.
                    </p>
                    <form method="POST" action="{{ route('system.users.destroy', $user) }}" class="mt-6" onsubmit="return confirm('Bu kullanıcıyı kalıcı olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>Kullanıcıyı Kalıcı Olarak Sil</x-danger-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
