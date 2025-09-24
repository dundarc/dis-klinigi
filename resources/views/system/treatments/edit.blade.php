<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tedavi Düzenle: {{ $treatment->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if($treatment->trashed())
                <div class="p-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-900/40 dark:text-yellow-200">
                    Bu tedavi silindiği için yalnızca bilgi amaçlı görüntülenebilir.
                </div>
            @endif

            <x-card>
                @include('system.treatments._form', [
                    'treatment' => $treatment,
                    'action' => route('system.treatments.update', $treatment),
                    'method' => 'PUT',
                ])
            </x-card>
        </div>
    </div>
</x-app-layout>
