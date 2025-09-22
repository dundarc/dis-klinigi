<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Hasta Detay: {{ $patient->first_name }} {{ $patient->last_name }}
            </h2>
            @can('update', $patient)
                <x-primary-button-link href="{{ route('patients.edit', $patient) }}">
                    Bilgileri Düzenle
                </x-primary-button-link>
            @endcan
        </div>
    </x-slot>

    {{-- TÜM SAYFAYI YÖNETEN ANA ALPINE.JS BİLEŞENİ --}}
    <div class="py-12" x-data="patientDetailManager">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Dikey boşluk için ana sarmalayıcı --}}
            <div class="space-y-6">
                
                <!-- Tedaviler Bölümü -->
                <div>
                    <x-card>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Uygulanan Tedaviler</h3>
                            @can('create', App\Models\PatientTreatment::class)
                                <x-primary-button @click="$dispatch('open-modal', { name: 'add-treatment-modal' })">Yeni Tedavi Ekle</x-primary-button>
                            @endcan
                        </div>
                        <ul id="treatments-list" class="divide-y dark:divide-gray-700">
                            @forelse($patient->treatments as $pt)
                                <li class="py-2">Diş #{{ $pt->tooth_number ?? 'N/A' }}: {{ $pt->treatment->name }} - {{ $pt->performed_at?->format('d.m.Y') }} (Dr. {{ $pt->dentist->name }})</li>
                            @empty
                                <li id="no-treatments-message" class="py-4 text-gray-500">Hastaya uygulanmış bir tedavi bulunmamaktadır.</li>
                            @endforelse
                        </ul>
                    </x-card>
                </div>

                <!-- Faturalar Bölümü -->
                <div>
                    <x-card>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Faturalar</h3>
                            @can('create', App\Models\Invoice::class)
                                <x-primary-button @click="$dispatch('open-modal', { name: 'add-invoice-modal' })">Yeni Fatura Oluştur</x-primary-button>
                            @endcan
                        </div>
                        <ul id="invoices-list" class="divide-y dark:divide-gray-700">
                            @forelse($patient->invoices as $invoice)
                                <li class="py-2 flex justify-between items-center" id="invoice-{{$invoice->id}}">
                                    <span> <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="text-indigo-600 hover:underline">{{ $invoice->invoice_no }}</a> - {{ $invoice->issue_date->format('d.m.Y') }} ({{ number_format($invoice->grand_total, 2) }} TL) </span>
                                    @can('delete', $invoice)
                                        <x-danger-button @click="deleteInvoice({{ $invoice->id }})">Sil</x-danger-button>
                                    @endcan
                                </li>
                            @empty
                                <li id="no-invoices-message" class="py-4 text-gray-500">Hastaya ait fatura bulunmamaktadır.</li>
                            @endforelse
                        </ul>
                    </x-card>
                </div>

                <!-- Dosyalar/Röntgen Bölümü -->
                <div>
                    <x-card>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Dosyalar / Röntgenler</h3>
                            @can('create', App\Models\File::class)
                                <x-primary-button @click="$dispatch('open-modal', { name: 'upload-file-modal' })">Dosya Yükle</x-primary-button>
                            @endcan
                        </div>
                        <ul id="files-list" class="divide-y dark:divide-gray-700">
                            @forelse($patient->files as $file)
                                <li class="py-2 flex justify-between items-center" id="file-{{ $file->id }}">
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="text-indigo-600 hover:underline">{{ $file->type->value }}: {{ basename($file->file_path) }}</a>
                                    @can('delete', $file)
                                        <x-danger-button @click="deleteFile({{ $file->id }})">Sil</x-danger-button>
                                    @endcan
                                </li>
                            @empty
                                <li id="no-files-message" class="py-4 text-gray-500">Hastaya ait dosya bulunmamaktadır.</li>
                            @endforelse
                        </ul>
                    </x-card>
                </div>

                <!-- Notlar Bölümü -->
                <div>
                    <x-card>
                        <form action="{{ route('patients.updateNotes', $patient) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="medications_used" value="Sürekli Kullandığı İlaçlar" />
                                    <textarea name="medications_used" id="medications_used" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('medications_used', $patient->medications_used) }}</textarea>
                                </div>
                                <div>
                                    <x-input-label for="notes" value="Genel Notlar" />
                                    <textarea name="notes" id="notes" rows="5" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('notes', $patient->notes) }}</textarea>
                                </div>
                                <div class="flex justify-end"> <x-primary-button>Notları Kaydet</x-primary-button> </div>
                            </div>
                        </form>
                    </x-card>
                </div>

                <!-- Tehlikeli Alan -->
                @can('delete', $patient)
                    <div class="mt-8 p-4 border border-red-300 dark:border-red-700 rounded-lg bg-red-50 dark:bg-gray-800">
                        <h3 class="text-lg font-medium text-red-800 dark:text-red-300">Tehlikeli Alan</h3>
                        <div class="mt-2 max-w-xl text-sm text-red-600 dark:text-red-400">
                            <p>Bu hastayı arşivlemek, listeden kaldırır ancak verileri geri getirilebilir. Kalıcı olarak silmek ise hastaya ait tüm randevuları, faturaları ve dosyaları geri döndürülemez şekilde yok eder.</p>
                        </div>
                        <div class="mt-5 flex gap-4">
                            <x-secondary-button x-data x-on:click.prevent="$dispatch('open-modal', { name: 'confirm-patient-archive' })">
                                Hastayı Arşivle
                            </x-secondary-button>
                            <x-danger-button x-data x-on:click.prevent="$dispatch('open-modal', { name: 'confirm-patient-erasure' })">
                                Hastayı Kalıcı Olarak Sil
                            </x-danger-button>
                        </div>
                    </div>
                @endcan

            </div>
        </div>

        <!-- Modallar -->
        <x-modal name="add-treatment-modal" title="Yeni Tedavi Ekle">
            <form @submit.prevent="submitTreatment($event.target)" class="p-6">
                <div class="mt-6 space-y-4">
                    <div>
                        <x-input-label for="treatment_id" value="Tedavi Türü" />
                        <x-select-input id="add_treatment_id" name="treatment_id" class="w-full" @change="updatePrice($event.target)" required>
                            <option value="">Seçiniz...</option>
                            @foreach($treatmentsList as $treatment)
                                <option value="{{ $treatment->id }}" data-price="{{ $treatment->default_price }}" data-vat="{{ $treatment->default_vat }}">{{ $treatment->name }}</option>
                            @endforeach
                        </x-select-input>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="tooth_number" value="Diş Numarası (Opsiyonel)" />
                            <x-text-input id="tooth_number" name="tooth_number" type="number" class="w-full" />
                        </div>
                        <div>
                            <x-input-label for="unit_price" value="Birim Fiyat (TL)" />
                            <x-text-input id="unit_price" name="unit_price" type="number" step="0.01" class="w-full" required/>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button type="button" @click="$dispatch('close')">İptal</x-secondary-button>
                    <x-primary-button class="ms-3">Kaydet</x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="add-invoice-modal" title="Yeni Fatura Oluştur">
             <form @submit.prevent="submitInvoice($event.target)" class="p-6">
                 <div class="mt-6 space-y-4">
                    <div>
                        <x-input-label value="Faturalandırılacak Tamamlanmış Tedaviler" />
                        <div class="mt-2 space-y-2 max-h-60 overflow-y-auto border p-2 rounded-md dark:border-gray-600">
                            @forelse($uninvoicedTreatments as $ut)
                                <label class="flex items-center">
                                    <input type="checkbox" name="treatment_ids[]" value="{{ $ut->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{$ut->treatment->name}} ({{number_format($ut->unit_price, 2)}} TL)</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">Faturalandırılacak yeni tedavi bulunmuyor.</p>
                            @endforelse
                        </div>
                    </div>
                 </div>
                 <div class="mt-6 flex justify-end">
                     <x-secondary-button type="button" @click="$dispatch('close')">İptal</x-secondary-button>
                     <x-primary-button class="ms-3" :disabled="$uninvoicedTreatments->isEmpty()">Fatura Oluştur</x-primary-button>
                 </div>
             </form>
        </x-modal>
        
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
        
        <x-modal name="confirm-patient-archive" title="Hastayı Arşivle">
            <p>Bu hastayı arşivlemek istediğinizden emin misiniz?</p>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">İptal</x-secondary-button>
                <form method="POST" action="{{ route('patients.destroy', $patient) }}" class="inline">
                    @csrf @method('DELETE')
                    <x-danger-button class="ms-3">Evet, Arşivle</x-danger-button>
                </form>
            </div>
        </x-modal>

        <x-modal name="confirm-patient-erasure" title="Hastayı Kalıcı Olarak Sil">
            <p>BU İŞLEM GERİ ALINAMAZ. Hastaya ait TÜM verileri kalıcı olarak silmek istediğinizden emin misiniz?</p>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">İptal</x-secondary-button>
                <x-danger-button @click="erasePatient" class="ms-3">Evet, Kalıcı Olarak Sil</x-danger-button>
            </div>
        </x-modal>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('patientDetailManager', () => ({
                // Sekme yönetimi artık Alpine tarafından yapılmıyor, ancak diğer işlevler için bu bileşen kalabilir.
                // İsterseniz bu bileşeni kaldırıp, script'i global fonksiyonlara dönüştürebiliriz.
                // Şimdilik bu şekilde bırakmak daha organize.
                
                updatePrice(selectElement) {
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const priceInput = document.getElementById('unit_price');
                    if (priceInput && selectedOption) {
                        priceInput.value = selectedOption.dataset.price || 0;
                    }
                },
                
                handleResponse: async (response) => {
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Bir hata oluştu.');
                    }
                    return response.json();
                },
                handleError: (error) => {
                    console.error('API Hatası:', error);
                    alert(error.message);
                },

                submitTreatment(form) {
                    const data = {
                        patient_id: {{ $patient->id }},
                        treatment_id: form.querySelector('[name=treatment_id]').value,
                        tooth_number: form.querySelector('[name=tooth_number]').value,
                        unit_price: form.querySelector('[name=unit_price]').value,
                        vat: 20 // Varsayılan
                    };
                    fetch('/api/v1/patient-treatments', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify(data)
                    })
                    .then(this.handleResponse).then(() => {
                        alert('Tedavi başarıyla eklendi.');
                        location.reload();
                    }).catch(this.handleError);
                },

                submitInvoice(form) {
                    const selectedTreatmentIds = Array.from(form.querySelectorAll('input[name="treatment_ids[]"]:checked')).map(cb => cb.value);
                    if (selectedTreatmentIds.length === 0) return alert('Lütfen faturalandırılacak en az bir tedavi seçin.');
                    
                    fetch(`/api/v1/invoices`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify({
                            patient_id: {{ $patient->id }},
                            issue_date: new Date().toISOString().slice(0, 10),
                            treatment_ids: selectedTreatmentIds,
                        })
                    })
                    .then(this.handleResponse).then(() => {
                        alert('Fatura başarıyla oluşturuldu.');
                        location.reload();
                    }).catch(this.handleError);
                },

                submitFile(form) {
                    const formData = new FormData(form);
                    fetch(`/api/v1/patients/{{ $patient->id }}/files`, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: formData
                    })
                    .then(this.handleResponse).then(() => {
                        alert('Dosya başarıyla yüklendi.');
                        location.reload();
                    }).catch(this.handleError);
                },

                deleteInvoice(invoiceId) {
                    if (!confirm('Bu faturayı silmek istediğinizden emin misiniz?')) return;
                    fetch(`/api/v1/invoices/${invoiceId}`, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
                        .then(response => {
                            if (!response.ok) throw new Error('Fatura silinemedi.');
                            document.getElementById(`invoice-${invoiceId}`)?.remove();
                        }).catch(this.handleError);
                },

                deleteFile(fileId) {
                    if (!confirm('Bu dosyayı kalıcı olarak silmek istediğinizden emin misiniz?')) return;
                    fetch(`/api/v1/files/${fileId}`, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
                        .then(response => {
                            if (!response.ok) throw new Error('Dosya silinemedi.');
                            document.getElementById(`file-${fileId}`)?.remove();
                        }).catch(this.handleError);
                },

                erasePatient(event) {
                    const button = event.target;
                    button.disabled = true; button.textContent = 'Siliniyor...';
                    fetch(`/api/v1/admin/patients/{{ $patient->id }}/erase`, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    })
                    .then(this.handleResponse).then(data => {
                        alert(data.message);
                        window.location.href = "{{ route('patients.index') }}";
                    }).catch(error => {
                        this.handleError(error);
                        button.disabled = false; button.textContent = 'Evet, Kalıcı Olarak Sil';
                    });
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>

