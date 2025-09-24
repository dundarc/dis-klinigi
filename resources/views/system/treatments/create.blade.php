<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Yeni Tedavi Ekle
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                @include('system.treatments._form', [
                    'treatment' => null,
                    'action' => route('system.treatments.store'),
                    'method' => 'POST',
                ])
            </x-card>
        </div>
    </div>
</x-app-layout>
