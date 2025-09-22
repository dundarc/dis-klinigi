<x-modal name="upload-file-modal" title="Dosya / Röntgen Yükle">
    <form @submit.prevent="submitFile($event.target)" class="p-6">
         <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="file_type" value="Dosya Tipi" />
                <x-select-input id="file_type" name="type" class="w-full" required>
                    <option value="xray">Röntgen</option>
                    <option value="photo">Fotoğraf</option>
                    <option value="doc">Belge</option>
                    <option value="other">Diğer</option>
                </x-select-input>
            </div>
            <div>
                <x-input-label for="file_input" value="Dosya Seç" />
                <input id="file_input" name="file" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" @click="$dispatch('close')">İptal</x-secondary-button>
            <x-primary-button class="ms-3">Yükle</x-primary-button>
        </div>
    </form>
</x-modal>
