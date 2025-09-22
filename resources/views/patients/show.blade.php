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
    <div class="py-12" x-data="patientDetailManager({
        patientId: {{ $patient->id }},
        treatmentsList: {{ json_encode($treatmentsList) }},
        patientIndexUrl: '{{ route('patients.index') }}',
        uninvoicedTreatments: {{ json_encode($uninvoicedTreatments) }}
    })">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                        <ul class="divide-y dark:divide-gray-700">
                            @forelse($patient->treatments as $pt)
                                <li class="py-2">Diş #{{ $pt->tooth_number ?? 'N/A' }}: {{ $pt->treatment->name }} - {{ $pt->performed_at?->format('d.m.Y') }} (Dr. {{ $pt->dentist->name }})</li>
                            @empty
                                <li class="py-4 text-gray-500">Hastaya uygulanmış bir tedavi bulunmamaktadır.</li>
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
                                <x-primary-button @click="openInvoiceModal()">Yeni Fatura Oluştur</x-primary-button>
                            @endcan
                        </div>
                        <ul class="divide-y dark:divide-gray-700">
                            @forelse($patient->invoices as $invoice)
                                <li class="py-2 flex justify-between items-center" id="invoice-{{$invoice->id}}">
                                    <span> <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="text-indigo-600 hover:underline">{{ $invoice->invoice_no }}</a> - {{ $invoice->issue_date->format('d.m.Y') }} ({{ number_format($invoice->grand_total, 2) }} TL) </span>
                                    <div class="flex space-x-2">
                                        @can('update', $invoice)
                                            <x-secondary-button @click="openInvoiceModal({{ json_encode($invoice) }})">Düzenle</x-secondary-button>
                                        @endcan
                                        @can('delete', $invoice)
                                            <x-danger-button @click="deleteInvoice({{ $invoice->id }})">Sil</x-danger-button>
                                        @endcan
                                    </div>
                                </li>
                            @empty
                                <li class="py-4 text-gray-500">Hastaya ait fatura bulunmamaktadır.</li>
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
                        <ul class="divide-y dark:divide-gray-700">
                            @forelse($patient->files as $file)
                                <li class="py-2 flex justify-between items-center" id="file-{{ $file->id }}">
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="text-indigo-600 hover:underline">{{ $file->type->value }}: {{ basename($file->file_path) }}</a>
                                    @can('delete', $file)
                                        <x-danger-button @click="deleteFile({{ $file->id }})">Sil</x-danger-button>
                                    @endcan
                                </li>
                            @empty
                                <li class="py-4 text-gray-500">Hastaya ait dosya bulunmamaktadır.</li>
                            @endforelse
                        </ul>
                    </x-card>
                </div>

                <!-- Notlar Bölümü -->
                <div>
                    <x-card>
                        <form action="{{ route('patients.updateNotes', $patient) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="medications_used" value="Sürekli Kullandığı İlaçlar" />
                                    <textarea name="medications_used" id="medications_used" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('medications_used', $patient->medications_used) }}</textarea>
                                </div>
                                <div>
                                    <x-input-label for="notes" value="Genel Notlar" />
                                    <textarea name="notes" id="notes" rows="5" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('notes', $patient->notes) }}</textarea>
                                </div>
                                <div class="flex justify-end">
                                    <x-primary-button>Notları Kaydet</x-primary-button>
                                </div>
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
                            <x-secondary-button x-on:click.prevent="$dispatch('open-modal', { name: 'confirm-patient-archive' })">
                                Hastayı Arşivle
                            </x-secondary-button>
                            <x-danger-button x-on:click.prevent="$dispatch('open-modal', { name: 'confirm-patient-erasure' })">
                                Hastayı Kalıcı Olarak Sil
                            </x-danger-button>
                        </div>
                    </div>
                @endcan
            </div>
        </div>

        <!-- Modallar -->
        @include('patients.partials.modal-treatment')
        @include('patients.partials.modal-invoice')
        @include('patients.partials.modal-file-upload')

        <x-modal name="confirm-patient-archive" title="Hastayı Arşivle">
            <div class="p-6">
                <p>Bu hastayı arşivlemek istediğinizden emin misiniz?</p>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">İptal</x-secondary-button>
                    <form method="POST" action="{{ route('patients.destroy', $patient) }}" class="inline">
                        @csrf @method('DELETE')
                        <x-danger-button class="ms-3">Evet, Arşivle</x-danger-button>
                    </form>
                </div>
            </div>
        </x-modal>

        <x-modal name="confirm-patient-erasure" title="Hastayı Kalıcı Olarak Sil">
            <div class="p-6">
                <p>BU İŞLEM GERİ ALINAMAZ. Hastaya ait TÜM verileri kalıcı olarak silmek istediğinizden emin misiniz?</p>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">İptal</x-secondary-button>
                    <x-danger-button @click="erasePatient" class="ms-3">Evet, Kalıcı Olarak Sil</x-danger-button>
                </div>
            </div>
        </x-modal>
    </div>

    @push('scripts')
        <script>
            // JAVASCRIPT'İ DOĞRUDAN BLADE İÇİNE GÖMÜYORUZ
            function patientDetailManager(config) {
                return {
                    // --- STATE (DURUM) ---
                    tab: 'treatments',
                    invoice: {},
                    treatmentsList: config.treatmentsList || [],
                    
                    // --- METODLAR ---
                    openInvoiceModal(invoiceData = null) {
                        if (invoiceData && invoiceData.id) { // Düzenleme Modu
                            this.invoice = { 
                                ...invoiceData, 
                                issue_date: invoiceData.issue_date.slice(0, 10), 
                                items: invoiceData.items ? invoiceData.items.map(item => ({...item})) : [] 
                            };
                        } else { // Oluşturma Modu
                            this.invoice = { 
                                id: null, 
                                patient_id: config.patientId, 
                                issue_date: new Date().toISOString().slice(0, 10), 
                                treatment_ids: [], 
                                items: [] 
                            };
                        }
                        this.$dispatch('open-modal', { name: 'invoice-form-modal' });
                    },
                    updatePrice(selectElement) {
                        const selectedOption = selectElement.options[selectElement.selectedIndex];
                        const priceInput = selectElement.closest('form').querySelector('#unit_price');
                        if (priceInput && selectedOption) priceInput.value = selectedOption.dataset.price || 0;
                    },
                    
                    // --- API İŞLEMLERİ ---
                    async handleResponse(response) {
                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Bir hata oluştu.');
                        }
                        return response.json();
                    },
                    handleError(error) {
                        console.error('API Hatası:', error);
                        const message = error.errors ? Object.values(error.errors).flat().join('\n') : error.message;
                        alert(message);
                    },
                    submitTreatment(form) {
                        const data = {
                            patient_id: config.patientId,
                            treatment_id: form.querySelector('[name=treatment_id]').value,
                            tooth_number: form.querySelector('[name=tooth_number]').value,
                            unit_price: form.querySelector('[name=unit_price]').value,
                            vat: 20
                        };
                        fetch('/api/v1/patient-treatments', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                            body: JSON.stringify(data)
                        }).then(this.handleResponse).then(() => { alert('Tedavi başarıyla eklendi.'); location.reload(); }).catch(this.handleError);
                    },
                    submitInvoice() {
                        const isEdit = !!this.invoice.id;
                        const url = isEdit ? `/api/v1/invoices/${this.invoice.id}` : '/api/v1/invoices';
                        const method = isEdit ? 'PUT' : 'POST';
                        let payload;
                        if (isEdit) {
                            payload = { patient_id: this.invoice.patient_id, issue_date: this.invoice.issue_date, items: this.invoice.items };
                        } else {
                            if (this.invoice.treatment_ids.length === 0) return alert('Lütfen faturalandırılacak en az bir tedavi seçin.');
                            payload = { patient_id: this.invoice.patient_id, issue_date: this.invoice.issue_date, treatment_ids: this.invoice.treatment_ids.map(id => parseInt(id, 10)) };
                        }
                        fetch(url, {
                            method: method,
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                            body: JSON.stringify(payload)
                        })
                        .then(res => res.json().then(data => ({ok: res.ok, data}))).then(({ok, data}) => {
                            if (!ok) throw new Error(data.message || 'Bir hata oluştu');
                            alert(isEdit ? 'Fatura güncellendi!' : 'Fatura oluşturuldu!');
                            window.location.reload();
                        }).catch(this.handleError);
                    },
                    submitFile(form) {
                        const formData = new FormData(form);
                        fetch(`/api/v1/patients/${config.patientId}/files`, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                            body: formData
                        }).then(this.handleResponse).then(() => { alert('Dosya başarıyla yüklendi.'); location.reload(); }).catch(this.handleError);
                    },
                    deleteInvoice(invoiceId) {
                        if (!confirm('Bu faturayı silmek istediğinizden emin misiniz?')) return;
                        fetch(`/api/v1/invoices/${invoiceId}`, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
                            .then(response => { if (!response.ok) throw new Error('Fatura silinemedi.'); document.getElementById(`invoice-${invoiceId}`)?.remove(); })
                            .catch(this.handleError);
                    },
                    deleteFile(fileId) {
                        if (!confirm('Bu dosyayı kalıcı olarak silmek istediğinizden emin misiniz?')) return;
                        fetch(`/api/v1/files/${fileId}`, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
                            .then(response => { if (!response.ok) throw new Error('Dosya silinemedi.'); document.getElementById(`file-${fileId}`)?.remove(); })
                            .catch(this.handleError);
                    },
                    erasePatient(event) {
                        const button = event.target;
                        button.disabled = true; button.textContent = 'Siliniyor...';
                        fetch(`/api/v1/admin/patients/${config.patientId}/erase`, {
                            method: 'DELETE',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        }).then(this.handleResponse).then(data => {
                            alert(data.message);
                            window.location.href = config.patientIndexUrl;
                        }).catch(error => {
                            this.handleError(error);
                            button.disabled = false; button.textContent = 'Evet, Kalıcı Olarak Sil';
                        });
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>

