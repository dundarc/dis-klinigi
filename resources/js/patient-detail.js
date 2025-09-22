document.addEventListener('alpine:init', () => {
    Alpine.data('patientDetailManager', (config) => ({
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
    }));
});
