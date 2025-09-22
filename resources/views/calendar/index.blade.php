<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Randevu Takvimi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <!-- Filtreler -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-input-label for="dentist_filter" value="Hekimler" />
                        <x-select-input id="dentist_filter" class="w-full" multiple>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                            @endforeach
                        </x-select-input>
                    </div>
                    <div>
                        <x-input-label for="status_filter" value="Randevu Durumları" />
                        <x-select-input id="status_filter" class="w-full" multiple>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                            @endforeach
                        </x-select-input>
                    </div>
                </div>

                <!-- FullCalendar'ın Render Edileceği Alan -->
                <div id="calendar" class="w-full h-[80vh]"></div>
            </x-card>
        </div>
    </div>
    
    <!-- Randevu Ekleme/Düzenleme Modalı -->
    <x-modal name="appointment-modal" x-data="appointmentModal()" @open-appointment-modal.window="openModal($event.detail)">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="isEditMode ? 'Randevuyu Düzenle' : 'Yeni Randevu Oluştur'"></h2>
        
        <form @submit.prevent="saveAppointment" class="mt-6 space-y-4">
            {{-- Hasta Seçimi (Gelecekte geliştirilebilir, şimdilik ID girilecek) --}}
            <div>
                <x-input-label for="patient_id" value="Hasta ID" />
                <x-text-input x-model="patient_id" id="patient_id" type="number" class="w-full" required />
            </div>
            {{-- Hekim Seçimi --}}
            <div>
                <x-input-label for="dentist_id" value="Hekim" />
                <x-select-input x-model="dentist_id" id="dentist_id" class="w-full" required>
                    @foreach($dentists as $dentist)
                        <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                    @endforeach
                </x-select-input>
            </div>
            {{-- Başlangıç ve Bitiş Tarihleri --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="start_at" value="Başlangıç" />
                    <x-text-input x-model="start_at" id="start_at" type="datetime-local" class="w-full" required />
                </div>
                <div>
                    <x-input-label for="end_at" value="Bitiş" />
                    <x-text-input x-model="end_at" id="end_at" type="datetime-local" class="w-full" required />
                </div>
            </div>
            
            <div class="mt-6 flex justify-between">
                <div>
                    <x-danger-button type="button" x-show="isEditMode" @click="deleteAppointment">Randevuyu Sil</x-danger-button>
                </div>
                <div class="flex justify-end">
                    <x-secondary-button type="button" @click="$dispatch('close')">İptal</x-secondary-button>
                    <x-primary-button class="ms-3" x-text="isEditMode ? 'Güncelle' : 'Kaydet'"></x-primary-button>
                </div>
            </div>
        </form>
    </x-modal>

    @push('scripts')
        @vite(['resources/js/calendar.js'])
    @endpush
</x-app-layout>