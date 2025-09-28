<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Hızlı İşlemler') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Ana Sayfa
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div x-data="quickActions()">
                        <x-quick-actions />
                        <x-quick-actions-modals />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Define the quickActions function globally
        window.quickActions = function() {
            return {
                    activeModal: null,
                    submitting: false,
                    submitText: 'Kaydediliyor...',

                // File Upload
                fileUpload: {
                    patientQuery: '',
                    patientResults: [],
                    selectedPatient: null,
                    selectedAppointment: '',
                    selectedFile: null,
                    uploading: false,
                    progress: 0
                },

                // Appointment Check-in
                appointmentCheckin: {
                    query: '',
                    results: [],
                    selectedAppointment: null
                },

                // New Appointment
                newAppointment: {
                    patientQuery: '',
                    patientResults: [],
                    selectedPatient: null,
                    dentistId: '',
                    dateTime: '',
                    notes: ''
                },

                // Cancel Appointment
                cancelAppointment: {
                    query: '',
                    results: [],
                    selectedAppointment: null,
                    reason: ''
                },

                // New Patient
                newPatient: {
                    firstName: '',
                    lastName: '',
                    phonePrimary: '',
                    email: ''
                },

                // Patient Search
                patientSearch: {
                    query: '',
                    results: []
                },

                // New Treatment Plan
                newTreatmentPlan: {
                    patientQuery: '',
                    patientResults: [],
                    selectedPatient: null,
                    dentistId: '',
                    items: []
                },

                // Add to Treatment Plan
                addToPlan: {
                    patientQuery: '',
                    patientResults: [],
                    selectedPatient: null,
                    plans: [],
                    planId: '',
                    newItem: {
                        treatmentId: '',
                        toothNumber: '',
                        price: ''
                    }
                },

                // Treatment Plan PDF
                treatmentPlanPdf: {
                    patientQuery: '',
                    patientResults: [],
                    selectedPatient: null,
                    plans: [],
                    planId: ''
                },

                // New Stock Item
                newStockItem: {
                    name: '',
                    categoryId: '',
                    unit: '',
                    sku: '',
                    minStock: ''
                },

                // New Invoice
                newInvoice: {
                    supplierQuery: '',
                    supplierResults: [],
                    selectedSupplier: null,
                    type: 'purchase',
                    selectedFile: null
                },

                // New Payment
                newPayment: {
                    accountId: '',
                    invoiceId: '',
                    amount: '',
                    method: 'cash',
                    date: new Date().toISOString().split('T')[0],
                    selectedFile: null
                },

                // Patient Update
                patientUpdate: {
                    query: '',
                    results: [],
                    selectedPatient: null,
                    phonePrimary: '',
                    phoneSecondary: '',
                    email: '',
                    addressText: '',
                    consentKvkk: false
                },

                openModal(modalType) {
                    this.activeModal = modalType;
                    this.resetModalData(modalType);
                },

                closeModal() {
                    this.activeModal = null;
                    this.submitting = false;
                },

                resetModalData(modalType) {
                    switch (modalType) {
                        case 'fileUpload':
                            this.fileUpload = { patientQuery: '', patientResults: [], selectedPatient: null, selectedAppointment: '', selectedFile: null, uploading: false, progress: 0 };
                            break;
                        case 'appointmentCheckin':
                            this.appointmentCheckin = { query: '', results: [], selectedAppointment: null };
                            break;
                        case 'newAppointment':
                            this.newAppointment = { patientQuery: '', patientResults: [], selectedPatient: null, dentistId: '', dateTime: '', notes: '' };
                            break;
                        case 'cancelAppointment':
                            this.cancelAppointment = { query: '', results: [], selectedAppointment: null, reason: '' };
                            break;
                        case 'newPatient':
                            this.newPatient = { firstName: '', lastName: '', phonePrimary: '', email: '' };
                            break;
                        case 'patientSearch':
                            this.patientSearch = { query: '', results: [] };
                            break;
                        case 'newTreatmentPlan':
                            this.newTreatmentPlan = { patientQuery: '', patientResults: [], selectedPatient: null, dentistId: '', items: [] };
                            break;
                        case 'addToTreatmentPlan':
                            this.addToPlan = { patientQuery: '', patientResults: [], selectedPatient: null, plans: [], planId: '', newItem: { treatmentId: '', toothNumber: '', price: '' } };
                            break;
                        case 'treatmentPlanPdf':
                            this.treatmentPlanPdf = { patientQuery: '', patientResults: [], selectedPatient: null, plans: [], planId: '' };
                            break;
                        case 'newStockItem':
                            this.newStockItem = { name: '', categoryId: '', unit: '', sku: '', minStock: '' };
                            break;
                        case 'newInvoice':
                            this.newInvoice = { supplierQuery: '', supplierResults: [], selectedSupplier: null, type: 'purchase', selectedFile: null };
                            break;
                        case 'newPayment':
                            this.newPayment = { accountId: '', invoiceId: '', amount: '', method: 'cash', date: new Date().toISOString().split('T')[0], selectedFile: null };
                            break;
                        case 'patientUpdate':
                            this.patientUpdate = { query: '', results: [], selectedPatient: null, phonePrimary: '', phoneSecondary: '', email: '', addressText: '', consentKvkk: false };
                            break;
                    }
                },

                // Search functions
                async searchPatients() {
                    const query = this.getCurrentQuery('patientQuery');
                    if (query.length < 2) {
                        this.setCurrentResults('patientResults', []);
                        return;
                    }

                    try {
                        const response = await fetch(`/search/patients?q=${encodeURIComponent(query)}`);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        const data = await response.json();
                        this.setCurrentResults('patientResults', data.data || []);
                    } catch (error) {
                        console.error('Patient search error:', error);
                        this.setCurrentResults('patientResults', []);
                        this.showToast('Hasta arama sırasında hata oluştu', 'error');
                    }
                },

                async searchAppointments() {
                    const query = this.getCurrentQuery('query');
                    if (query.length < 2) {
                        this.setCurrentResults('results', []);
                        return;
                    }

                    try {
                        const response = await fetch(`/search/appointments?q=${encodeURIComponent(query)}`);
                        const data = await response.json();
                        this.setCurrentResults('results', data.data);
                    } catch (error) {
                        console.error('Appointment search error:', error);
                        this.setCurrentResults('results', []);
                    }
                },

                async searchSuppliers() {
                    const query = this.getCurrentQuery('supplierQuery');
                    if (query.length < 2) {
                        this.setCurrentResults('supplierResults', []);
                        return;
                    }

                    try {
                        const response = await fetch(`/search/suppliers?q=${encodeURIComponent(query)}`);
                        const data = await response.json();
                        this.setCurrentResults('supplierResults', data.data);
                    } catch (error) {
                        console.error('Supplier search error:', error);
                        this.setCurrentResults('supplierResults', []);
                    }
                },

                // Helper functions
                getCurrentQuery(field) {
                    switch (this.activeModal) {
                        case 'fileUpload': return this.fileUpload[field];
                        case 'newAppointment': return this.newAppointment[field];
                        case 'patientSearch': return this.patientSearch[field];
                        case 'newTreatmentPlan': return this.newTreatmentPlan[field];
                        case 'addToTreatmentPlan': return this.addToPlan[field];
                        case 'treatmentPlanPdf': return this.treatmentPlanPdf[field];
                        case 'appointmentCheckin': return this.appointmentCheckin[field];
                        case 'cancelAppointment': return this.cancelAppointment[field];
                        case 'newInvoice': return this.newInvoice[field];
                        case 'patientUpdate': return this.patientUpdate[field];
                        default: return '';
                    }
                },

                setCurrentResults(field, results) {
                    switch (this.activeModal) {
                        case 'fileUpload': this.fileUpload[field] = results; break;
                        case 'newAppointment': this.newAppointment[field] = results; break;
                        case 'patientSearch': this.patientSearch[field] = results; break;
                        case 'newTreatmentPlan': this.newTreatmentPlan[field] = results; break;
                        case 'addToTreatmentPlan': this.addToPlan[field] = results; break;
                        case 'treatmentPlanPdf': this.treatmentPlanPdf[field] = results; break;
                        case 'appointmentCheckin': this.appointmentCheckin[field] = results; break;
                        case 'cancelAppointment': this.cancelAppointment[field] = results; break;
                        case 'newInvoice': this.newInvoice[field] = results; break;
                        case 'patientUpdate': this.patientUpdate[field] = results; break;
                    }
                },

                // Selection functions
                selectPatient(patient) {
                    switch (this.activeModal) {
                        case 'fileUpload':
                            this.fileUpload.selectedPatient = patient;
                            this.fileUpload.patientQuery = patient.text;
                            this.fileUpload.patientResults = [];
                            break;
                        case 'newAppointment':
                            this.newAppointment.selectedPatient = patient;
                            this.newAppointment.patientQuery = patient.text;
                            this.newAppointment.patientResults = [];
                            break;
                        case 'newTreatmentPlan':
                            this.newTreatmentPlan.selectedPatient = patient;
                            this.newTreatmentPlan.patientQuery = patient.text;
                            this.newTreatmentPlan.patientResults = [];
                            break;
                        case 'addToTreatmentPlan':
                            this.selectPatientForAddToPlan(patient);
                            break;
                        case 'treatmentPlanPdf':
                            this.selectPatientForPdf(patient);
                            break;
                    }
                },

                selectPatientForAppointment(patient) {
                    this.newAppointment.selectedPatient = patient;
                    this.newAppointment.patientQuery = patient.text;
                    this.newAppointment.patientResults = [];
                },

                selectAppointment(appointment) {
                    this.appointmentCheckin.selectedAppointment = appointment;
                    this.appointmentCheckin.query = appointment.text;
                    this.appointmentCheckin.results = [];
                },

                selectAppointmentForCancel(appointment) {
                    this.cancelAppointment.selectedAppointment = appointment;
                    this.cancelAppointment.query = appointment.text;
                    this.cancelAppointment.results = [];
                },

                async selectPatientForAddToPlan(patient) {
                    this.addToPlan.selectedPatient = patient;
                    this.addToPlan.patientQuery = patient.text;
                    this.addToPlan.patientResults = [];

                    // Load treatment plans for this patient
                    try {
                        const response = await fetch(`/search/treatment-plans?q=${encodeURIComponent(patient.text)}`);
                        const data = await response.json();
                        this.addToPlan.plans = data.data;
                    } catch (error) {
                        console.error('Treatment plan search error:', error);
                        this.addToPlan.plans = [];
                    }
                },

                async selectPatientForPdf(patient) {
                    this.treatmentPlanPdf.selectedPatient = patient;
                    this.treatmentPlanPdf.patientQuery = patient.text;
                    this.treatmentPlanPdf.patientResults = [];

                    // Load treatment plans for this patient
                    try {
                        const response = await fetch(`/search/treatment-plans?q=${encodeURIComponent(patient.text)}`);
                        const data = await response.json();
                        this.treatmentPlanPdf.plans = data.data;
                    } catch (error) {
                        console.error('Treatment plan search error:', error);
                        this.treatmentPlanPdf.plans = [];
                    }
                },

                selectSupplier(supplier) {
                    this.newInvoice.selectedSupplier = supplier;
                    this.newInvoice.supplierQuery = supplier.text;
                    this.newInvoice.supplierResults = [];
                },

                selectPatientForUpdate(patient) {
                    this.patientUpdate.selectedPatient = patient;
                    this.patientUpdate.query = patient.text;
                    this.patientUpdate.results = [];
                },

                // File handling
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (this.activeModal === 'fileUpload') {
                        this.fileUpload.selectedFile = file;
                    } else if (this.activeModal === 'newInvoice') {
                        this.newInvoice.selectedFile = file;
                    } else if (this.activeModal === 'newPayment') {
                        this.newPayment.selectedFile = file;
                    }
                },

                handleInvoiceFileSelect(event) {
                    this.newInvoice.selectedFile = event.target.files[0];
                },

                handlePaymentFileSelect(event) {
                    this.newPayment.selectedFile = event.target.files[0];
                },

                // Treatment plan functions
                addTreatmentItem() {
                    this.newTreatmentPlan.items.push({
                        treatmentId: '',
                        toothNumber: '',
                        price: ''
                    });
                },

                removeTreatmentItem(index) {
                    this.newTreatmentPlan.items.splice(index, 1);
                },

                // Submit functions
                async submitModal() {
                    this.submitting = true;
                    this.submitText = 'Kaydediliyor...';

                    try {
                        let response;
                        switch (this.activeModal) {
                            case 'fileUpload':
                                response = await this.submitFileUpload();
                                break;
                            case 'appointmentCheckin':
                                response = await this.submitAppointmentCheckin();
                                break;
                            case 'newAppointment':
                                response = await this.submitNewAppointment();
                                break;
                            case 'cancelAppointment':
                                response = await this.submitCancelAppointment();
                                break;
                            case 'newPatient':
                                response = await this.submitNewPatient();
                                break;
                            case 'patientUpdate':
                                response = await this.submitQuickPatientUpdate();
                                break;
                            case 'newTreatmentPlan':
                                response = await this.submitNewTreatmentPlan();
                                break;
                            case 'addToTreatmentPlan':
                                response = await this.submitAddToTreatmentPlan();
                                break;
                            case 'treatmentPlanPdf':
                                response = await this.submitTreatmentPlanPdf();
                                break;
                            case 'newStockItem':
                                response = await this.submitNewStockItem();
                                break;
                            case 'newInvoice':
                                response = await this.submitNewInvoice();
                                break;
                            case 'newPayment':
                                response = await this.submitNewPayment();
                                break;
                        }

                        if (response && response.success) {
                            this.showToast('İşlem başarıyla tamamlandı!', 'success');
                            this.closeModal();
                            // Optionally reload the page or update UI
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            this.showToast(response?.message || 'Bir hata oluştu.', 'error');
                        }
                    } catch (error) {
                        console.error('Submit error:', error);
                        this.showToast('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
                    } finally {
                        this.submitting = false;
                    }
                },

                // Individual submit functions
                async submitFileUpload() {
                    const formData = new FormData();
                    formData.append('patient_id', this.fileUpload.selectedPatient?.id);
                    formData.append('appointment_id', this.fileUpload.selectedAppointment);
                    if (this.fileUpload.selectedFile) {
                        formData.append('file', this.fileUpload.selectedFile);
                    }

                    const response = await fetch('/quick-actions/upload-file', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    return await response.json();
                },

                async submitAppointmentCheckin() {
                    if (!this.appointmentCheckin.selectedAppointment) {
                        throw new Error('Randevu seçilmedi.');
                    }

                    const response = await fetch(`/todays-appointments/${this.appointmentCheckin.selectedAppointment.id}/check-in`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    return await response.json();
                },

                async submitNewAppointment() {
                    const data = {
                        patient_id: this.newAppointment.selectedPatient?.id,
                        dentist_id: this.newAppointment.dentistId,
                        start_at: this.newAppointment.dateTime,
                        notes: this.newAppointment.notes
                    };

                    const response = await fetch('/appointments', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Randevu oluşturma başarısız');
                    }

                    return await response.json();
                },

                async submitCancelAppointment() {
                    if (!this.cancelAppointment.selectedAppointment) {
                        throw new Error('Randevu seçilmedi.');
                    }

                    const response = await fetch(`/quick-actions/cancel-appointment/${this.cancelAppointment.selectedAppointment.id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ reason: this.cancelAppointment.reason })
                    });

                    return await response.json();
                },

                async submitNewPatient() {
                    const data = {
                        first_name: this.newPatient.firstName,
                        last_name: this.newPatient.lastName,
                        phone_primary: this.newPatient.phonePrimary,
                        email: this.newPatient.email
                    };

                    const response = await fetch('/patients', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Hasta oluşturma başarısız');
                    }

                    return await response.json();
                },

                async submitQuickPatientUpdate() {
                    if (!this.patientUpdate.selectedPatient) {
                        throw new Error('Hasta seçilmedi.');
                    }

                    const data = {
                        phone_primary: this.patientUpdate.phonePrimary,
                        phone_secondary: this.patientUpdate.phoneSecondary,
                        email: this.patientUpdate.email,
                        address_text: this.patientUpdate.addressText,
                        consent_kvkk: this.patientUpdate.consentKvkk
                    };

                    const response = await fetch(`/quick-actions/update-patient/${this.patientUpdate.selectedPatient.id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    return await response.json();
                },

                async submitNewTreatmentPlan() {
                    const data = {
                        patient_id: this.newTreatmentPlan.selectedPatient?.id,
                        dentist_id: this.newTreatmentPlan.dentistId,
                        items: this.newTreatmentPlan.items.filter(item => item.treatmentId)
                    };

                    const response = await fetch('/treatment-plans', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    return await response.json();
                },

                async submitAddToTreatmentPlan() {
                    const data = {
                        treatment_id: this.addToPlan.newItem.treatmentId,
                        tooth_number: this.addToPlan.newItem.toothNumber,
                        estimated_price: this.addToPlan.newItem.price
                    };

                    const response = await fetch(`/treatment-plans/${this.addToPlan.planId}/items`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    return await response.json();
                },

                async submitTreatmentPlanPdf() {
                    // Redirect to PDF download
                    window.open(`/treatment-plans/${this.treatmentPlanPdf.planId}/download-pdf`, '_blank');
                    return { success: true, message: 'PDF indiriliyor...' };
                },

                async submitNewStockItem() {
                    const data = {
                        name: this.newStockItem.name,
                        category_id: this.newStockItem.categoryId,
                        unit: this.newStockItem.unit,
                        sku: this.newStockItem.sku,
                        min_stock: this.newStockItem.minStock
                    };

                    const response = await fetch('/quick-actions/create-stock-item', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    return await response.json();
                },

                async submitNewInvoice() {
                    const formData = new FormData();
                    formData.append('supplier_id', this.newInvoice.selectedSupplier?.id);
                    formData.append('type', this.newInvoice.type);
                    if (this.newInvoice.selectedFile) {
                        formData.append('invoice_file', this.newInvoice.selectedFile);
                    }

                    const response = await fetch('/quick-actions/create-invoice', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    return await response.json();
                },

                async submitNewPayment() {
                    const formData = new FormData();
                    formData.append('supplier_id', this.newPayment.accountId);
                    formData.append('amount', this.newPayment.amount);
                    formData.append('method', this.newPayment.method);
                    formData.append('date', this.newPayment.date);
                    if (this.newPayment.selectedFile) {
                        formData.append('receipt_file', this.newPayment.selectedFile);
                    }

                    const response = await fetch('/quick-actions/create-payment', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    return await response.json();
                },

                showToast(message, type = 'success') {
                    // Mevcut DOM element varsa kullan, yoksa oluştur
                    let toastContainer = document.getElementById('toast-container');
                    if (!toastContainer) {
                        toastContainer = document.createElement('div');
                        toastContainer.id = 'toast-container';
                        toastContainer.className = 'fixed top-4 right-4 z-50 space-y-2';
                        document.body.appendChild(toastContainer);
                    }

                    const toast = document.createElement('div');
                    toast.className = `px-4 py-2 rounded-md text-white shadow-lg transform transition-all duration-300 translate-x-full ${
                        type === 'success' ? 'bg-green-500' : 'bg-red-500'
                    }`;
                    toast.textContent = message;

                    toastContainer.appendChild(toast);

                    // Animasyon için
                    setTimeout(() => {
                        toast.classList.remove('translate-x-full');
                    }, 100);

                    // Otomatik kaldır
                    setTimeout(() => {
                        toast.classList.add('translate-x-full');
                        setTimeout(() => {
                            if (toast.parentNode) {
                                toast.parentNode.removeChild(toast);
                            }
                        }, 300);
                    }, 3000);
                }
            };
        };
    </script>
</x-app-layout>