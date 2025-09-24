
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bildirim Gönder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('notifications.send.store') }}" method="POST">
                        @csrf

                        <!-- Recipient -->
                        <div class="mb-4">
                            <x-input-label for="recipient" :value="__('Alıcı')" />
                            <x-select-input id="recipient" name="recipient_id" class="block mt-1 w-full" required>
                                <option value="">{{ __('Alıcı Seçin') }}</option>
                                @foreach ($recipients as $recipient)
<option value="{{ $recipient->id }}">
    {{ $recipient->name }} ({{ ucfirst($recipient->role->value) }})
</option>                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('recipient_id')" class="mt-2" />
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Başlık')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Body -->
                        <div class="mb-4">
                            <x-input-label for="body" :value="__('Mesaj')" />
                            <x-textarea-input id="body" class="block mt-1 w-full" name="body" required>{{ old('body') }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <x-input-label for="type" :value="__('Bildirim Türü')" />
                            <x-select-input id="type" name="type" class="block mt-1 w-full" required>
                                <option value="info">{{ __('Bilgi') }}</option>
                                <option value="task">{{ __('İş Emri') }}</option>
                            </x-select-input>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Gönder') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
