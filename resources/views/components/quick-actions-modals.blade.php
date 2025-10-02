<!-- Quick Actions Modals -->
<div x-show="activeModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="activeModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div x-show="activeModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <!-- File Upload Modal -->
                        <div x-show="activeModal === 'fileUpload'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Tedaviye Dosya Yükle
                            </h3>
                            <div class="mt-4 space-y-4">
                                <!-- Patient Search -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta Seç</label>
                                    <input x-model="fileUpload.patientQuery" @input="searchPatients()" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hasta adı ara...">
                                    <div x-show="fileUpload.patientResults.length > 0" class="mt-1 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                                        <template x-for="patient in fileUpload.patientResults" :key="patient.id">
                                            <div @click="selectPatient(patient)" class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                <span x-text="patient.text"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Appointment/Treatment Selection -->
                                <div x-show="fileUpload.selectedPatient">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Randevu/Tedavi Seç</label>
                                    <select x-model="fileUpload.selectedAppointment" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Seçin...</option>
                                        <option value="encounter">Mevcut Muayene</option>
                                        <option value="appointment">Randevu</option>
                                        <option value="treatment_plan">Tedavi Planı</option>
                                    </select>
                                </div>

                                <!-- File Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosya</label>
                                    <input type="file" @change="handleFileSelect($event)" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <div x-show="fileUpload.selectedFile" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        Seçilen dosya: <span x-text="fileUpload.selectedFile.name"></span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div x-show="fileUpload.uploading" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" :style="`width: ${fileUpload.progress}%`"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Check-in Modal -->
                        <div x-show="activeModal === 'appointmentCheckin'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Randevu Check-in
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Randevu Ara</label>
                                    <input x-model="appointmentCheckin.query" @input="searchAppointments()" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hasta adı veya tarih ara...">
                                    <div x-show="appointmentCheckin.results.length > 0" class="mt-1 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                                        <template x-for="appointment in appointmentCheckin.results" :key="appointment.id">
                                            <div @click="selectAppointment(appointment)" class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                <span x-text="appointment.text"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div x-show="appointmentCheckin.selectedAppointment" class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-md">
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        <strong>Seçilen Randevu:</strong> <span x-text="appointmentCheckin.selectedAppointment.text"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- New Appointment Modal -->
                        <div x-show="activeModal === 'newAppointment'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Yeni Randevu Oluştur
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta</label>
                                    <input x-model="newAppointment.patientQuery" @input="searchPatients()" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hasta adı ara...">
                                    <div x-show="newAppointment.patientResults.length > 0" class="mt-1 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                                        <template x-for="patient in newAppointment.patientResults" :key="patient.id">
                                            <div @click="selectPatientForAppointment(patient)" class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                <span x-text="patient.text"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div x-show="newAppointment.selectedPatient">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Doktor</label>
                                    <select x-model="newAppointment.dentistId" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Doktor seçin...</option>
                                        @foreach(\App\Models\User::where('role', \App\Enums\UserRole::DENTIST)->get() as $dentist)
                                            <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tarih ve Saat</label>
                                    <input x-model="newAppointment.dateTime" type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notlar</label>
                                    <textarea x-model="newAppointment.notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Randevu notları..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Cancel Appointment Modal -->
                        <div x-show="activeModal === 'cancelAppointment'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Randevu İptal Et
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Randevu Ara</label>
                                    <input x-model="cancelAppointment.query" @input="searchAppointments()" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hasta adı veya tarih ara...">
                                    <div x-show="cancelAppointment.results.length > 0" class="mt-1 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                                        <template x-for="appointment in cancelAppointment.results" :key="appointment.id">
                                            <div @click="selectAppointmentForCancel(appointment)" class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                <span x-text="appointment.text"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div x-show="cancelAppointment.selectedAppointment" class="bg-red-50 dark:bg-red-900/20 p-3 rounded-md">
                                    <p class="text-sm text-red-800 dark:text-red-200">
                                        <strong>İptal Edilecek Randevu:</strong> <span x-text="cancelAppointment.selectedAppointment.text"></span>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">İptal Nedeni</label>
                                    <textarea x-model="cancelAppointment.reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="İptal nedeni..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- New Patient Modal -->
                        <div x-show="activeModal === 'newPatient'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Yeni Hasta Ekle
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad</label>
                                        <input x-model="newPatient.firstName" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Soyad</label>
                                        <input x-model="newPatient.lastName" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birincil Telefon</label>
                                    <input x-model="newPatient.phonePrimary" type="tel" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-posta (Opsiyonel)</label>
                                    <input x-model="newPatient.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Patient Update Modal -->
                        <div x-show="activeModal === 'patientUpdate'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Hasta Bilgilerini Güncelle
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta</label>
                                    <input x-model="patientUpdate.query" @input="searchPatients()" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hasta adı ara...">
                                    <div x-show="patientUpdate.results.length > 0" class="mt-1 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                                        <template x-for="patient in patientUpdate.results" :key="patient.id">
                                            <div @click="selectPatientForUpdate(patient)" class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                <span x-text="patient.text"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div x-show="patientUpdate.selectedPatient" class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birincil Telefon</label>
                                            <input x-model="patientUpdate.phonePrimary" type="tel" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">İkincil Telefon</label>
                                            <input x-model="patientUpdate.phoneSecondary" type="tel" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-posta</label>
                                        <input x-model="patientUpdate.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adres</label>
                                        <textarea x-model="patientUpdate.addressText" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>
                                    <div>
                                        <div class="flex items-start gap-2 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-amber-600 dark:text-amber-300 mt-0.5"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 100 2 1 1 0 000-2zm-1 4a1 1 0 000 2h2a1 1 0 100-2h-2z" clip-rule="evenodd" /></svg>
                                            <p class="text-xs text-slate-600 dark:text-slate-200">KVKK onayı hızlı işlemlerden yönetilmez. Onam verme veya geri alma için KVKK modülünü kullanın.</p>
                                        </div>
                                </div>
                            </div>
                        </div>

                        <!-- Patient Search Modal -->
                        <div x-show="activeModal === 'patientSearch'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Hasta Bul (Hızlı Git)
                            </h3>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta Ara</label>
                                <input x-model="patientSearch.query" @input="searchPatients()" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hasta adı, telefon veya TC ara...">
                                <div x-show="patientSearch.results.length > 0" class="mt-1 max-h-60 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                                    <template x-for="patient in patientSearch.results" :key="patient.id">
                                        <a :href="`/patients/${patient.id}`" class="block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <span x-text="patient.text"></span>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- New Treatment Plan Modal -->
                        <div x-show="activeModal === 'newTreatmentPlan'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Yeni Tedavi Planı Oluştur
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta</label>
                                    <input x-model="newTreatmentPlan.patientQuery" @input="searchPatients()" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hasta adı ara...">
                                    <div x-show="newTreatmentPlan.patientResults.length > 0" class="mt-1 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                                        <template x-for="patient in newTreatmentPlan.patientResults" :key="patient.id">
                                            <div @click="selectPatientForPlan(patient)" class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                <span x-text="patient.text"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div x-show="newTreatmentPlan.selectedPatient">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Doktor</label>
                                    <select x-model="newTreatmentPlan.dentistId" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Doktor seçin...</option>
                                        @foreach(\App\Models\User::where('role', \App\Enums\UserRole::DENTIST)->get() as $dentist)
                                            <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Tedavi Kalemleri</h4>
                                    <div x-show="newTreatmentPlan.items.length === 0" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Henüz kalem eklenmemiş
                                    </div>
                                    <template x-for="(item, index) in newTreatmentPlan.items" :key="index">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <select x-model="item.treatmentId" class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                <option value="">Tedavi seçin...</option>
                                                @foreach(\App\Models\Treatment::all() as $treatment)
                                                    <option value="{{ $treatment->id }}">{{ $treatment->name }}</option>
                                                @endforeach
                                            </select>
                                            <input x-model="item.toothNumber" type="text" placeholder="Diş No" class="w-20 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <input x-model="item.price" type="number" step="0.01" placeholder="Fiyat" class="w-24 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <button @click="removeTreatmentItem(index)" class="text-red-600 hover:text-red-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button @click="addTreatmentItem()" class="mt-2 inline-flex items-center px-3 py-1 text-sm bg-green-600 hover:bg-green-700 text-white rounded">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Kalem Ekle
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Add to Treatment Plan Modal -->
                        <div x-show="activeModal === 'addToTreatmentPlan'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Mevcut Plana İşlem Ekle
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta</label>
                                    <input x-model="addToPlan.patientQuery" @input="searchPatients()" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hasta adı ara...">
                                    <div x-show="addToPlan.patientResults.length > 0" class="mt-1 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                                        <template x-for="patient in addToPlan.patientResults" :key="patient.id">
                                            <div @click="selectPatientForAddToPlan(patient)" class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                <span x-text="patient.text"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div x-show="addToPlan.selectedPatient">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aktif Tedavi Planı</label>
                                    <select x-model="addToPlan.planId" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Plan seçin...</option>
                                        <template x-for="plan in addToPlan.plans" :key="plan.id">
                                            <option :value="plan.id" x-text="plan.text"></option>
                                        </template>
                                    </select>
                                </div>
                                <div x-show="addToPlan.planId">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Yeni Tedavi Kalemi</label>
                                    <div class="space-y-2">
                                        <select x-model="addToPlan.newItem.treatmentId" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Tedavi seçin...</option>
                                            @foreach(\App\Models\Treatment::all() as $treatment)
                                                <option value="{{ $treatment->id }}">{{ $treatment->name }}</option>
                                            @endforeach
                                        </select>
                                        <input x-model="addToPlan.newItem.toothNumber" type="text" placeholder="Diş No" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <input x-model="addToPlan.newItem.price" type="number" step="0.01" placeholder="Fiyat" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Treatment Plan PDF Modal -->
                        <div x-show="activeModal === 'treatmentPlanPdf'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Tedavi Planı PDF İndir
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta</label>
                                    <input x-model="treatmentPlanPdf.patientQuery" @input="searchPatients()" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hasta adı ara...">
                                    <div x-show="treatmentPlanPdf.patientResults.length > 0" class="mt-1 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                                        <template x-for="patient in treatmentPlanPdf.patientResults" :key="patient.id">
                                            <div @click="selectPatientForPdf(patient)" class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                <span x-text="patient.text"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div x-show="treatmentPlanPdf.selectedPatient">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tedavi Planı</label>
                                    <select x-model="treatmentPlanPdf.planId" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Plan seçin...</option>
                                        <template x-for="plan in treatmentPlanPdf.plans" :key="plan.id">
                                            <option :value="plan.id" x-text="plan.text"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- New Stock Item Modal (Admin/Accountant only) -->
                        @can('accessStockManagement')
                        <div x-show="activeModal === 'newStockItem'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Yeni Stok Kalemi Ekle
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ürün Adı</label>
                                    <input x-model="newStockItem.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                                    <select x-model="newStockItem.categoryId" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Kategori seçin...</option>
                                        @foreach(\App\Models\Stock\StockCategory::all() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birim</label>
                                        <input x-model="newStockItem.unit" type="text" placeholder="Adet, Kg, Lt..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU</label>
                                        <input x-model="newStockItem.sku" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Stok</label>
                                        <input x-model="newStockItem.minStock" type="number" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- New Invoice Modal (Admin/Accountant only) -->
                        <div x-show="activeModal === 'newInvoice'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Fatura/Gider Ekle
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tedarikçi</label>
                                    <input x-model="newInvoice.supplierQuery" @input="searchSuppliers()" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Tedarikçi ara...">
                                    <div x-show="newInvoice.supplierResults.length > 0" class="mt-1 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                                        <template x-for="supplier in newInvoice.supplierResults" :key="supplier.id">
                                            <div @click="selectSupplier(supplier)" class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                <span x-text="supplier.text"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fatura Türü</label>
                                    <select x-model="newInvoice.type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="purchase">Satın Alma</option>
                                        <option value="expense">Gider</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fatura Dosyası (Opsiyonel)</label>
                                    <input type="file" @change="handleInvoiceFileSelect($event)" accept="image/*,.pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                            </div>
                        </div>

                        <!-- New Payment Modal (Admin/Accountant only) -->
                        <div x-show="activeModal === 'newPayment'">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Ödeme Kaydı Ekle
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Hesap</label>
                                    <select x-model="newPayment.accountId" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Cari hesap seçin...</option>
                                        @foreach(\App\Models\Stock\StockSupplier::all() as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fatura</label>
                                    <select x-model="newPayment.invoiceId" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Fatura seçin...</option>
                                        <!-- This would need to be populated dynamically -->
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tutar</label>
                                        <input x-model="newPayment.amount" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ödeme Yöntemi</label>
                                        <select x-model="newPayment.method" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="cash">Nakit</option>
                                            <option value="bank_transfer">Havale</option>
                                            <option value="credit_card">Kredi Kartı</option>
                                            <option value="check">Çek</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tarih</label>
                                    <input x-model="newPayment.date" type="date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dekont (Opsiyonel)</label>
                                    <input type="file" @change="handlePaymentFileSelect($event)" accept="image/*,.pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button x-show="activeModal !== 'patientSearch'" @click="submitModal()" :disabled="submitting" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                    <span x-show="!submitting">Kaydet</span>
                    <span x-show="submitting" x-text="submitText"></span>
                </button>
                <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    İptal
                </button>
            </div>
        </div>
    </div>
</div>