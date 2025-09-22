<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Fatura Detayı: {{ $invoice->invoice_no }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Sol Taraf: Fatura Bilgileri -->
            <div class="md:col-span-2 space-y-6">
                <x-card>
                    <h3 class="text-lg font-medium mb-4">Hasta Bilgileri</h3>
                    <p><strong>Ad Soyad:</strong> {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</p>
                    <p><strong>Telefon:</strong> {{ $invoice->patient->phone_primary }}</p>
                    <p><strong>E-posta:</strong> {{ $invoice->patient->email }}</p>
                </x-card>
                <x-card>
                    <h3 class="text-lg font-medium mb-4">Fatura Kalemleri</h3>
                    <table class="w-full text-sm text-left">
                        <tbody>
                            @foreach($invoice->items as $item)
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-2">{{ $item->description }}</td>
                                <td class="py-2 text-right">{{ number_format($item->line_total, 2, ',', '.') }} TL</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-semibold">
                                <td class="py-2 text-right">Ara Toplam:</td>
                                <td class="py-2 text-right">{{ number_format($invoice->subtotal, 2, ',', '.') }} TL</td>
                            </tr>
                            <tr class="font-semibold">
                                <td class="py-2 text-right">KDV (%20):</td>
                                <td class="py-2 text-right">{{ number_format($invoice->vat_total, 2, ',', '.') }} TL</td>
                            </tr>
                            <tr class="font-bold text-lg">
                                <td class="pt-4 text-right">Genel Toplam:</td>
                                <td class="pt-4 text-right">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                            </tr>
                        </tfoot>
                    </table>
                </x-card>
            </div>

            <!-- Sağ Taraf: İşlem Paneli -->
            <div class="space-y-6">
                <x-card>
                    <h3 class="text-lg font-medium mb-4">İşlemler</h3>
                    
                    <!-- Durum Güncelleme -->
                    <form id="status-form" class="space-y-2">
                        <x-input-label for="status" value="Fatura Durumu" />
                        <x-select-input id="status" name="status" class="w-full">
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" @selected($invoice->status == $status)>{{ ucfirst($status->value) }}</option>
                            @endforeach
                        </x-select-input>
                        <x-primary-button type="submit">Durumu Güncelle</x-primary-button>
                    </form>

                    <!-- Sigorta Güncelleme -->
                    <form id="insurance-form" class="mt-4 space-y-2">
                         <x-input-label for="insurance_coverage_amount" value="Sigorta Karşılama Tutarı (TL)" />
                         <x-text-input type="number" step="0.01" id="insurance_coverage_amount" name="insurance_coverage_amount" class="w-full" value="{{ $invoice->insurance_coverage_amount }}" />
                         <x-primary-button type="submit">Sigortayı Güncelle</x-primary-button>
                    </form>

                    <!-- E-posta Gönderme -->
                     <form id="email-form" class="mt-4">
                        <x-secondary-button type="submit" class="w-full justify-center">Faturayı E-posta Gönder</x-secondary-button>
                    </form>
                    
                    <!-- PDF Görüntüleme -->
                    <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="mt-2 inline-flex items-center justify-center w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                        PDF Görüntüle / İndir
                    </a>
                </x-card>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const invoiceId = {{ $invoice->id }};

        const statusForm = document.getElementById('status-form');
        const insuranceForm = document.getElementById('insurance-form');
        const emailForm = document.getElementById('email-form');

        statusForm.addEventListener('submit', (e) => handleFormSubmit(e, `/api/v1/accounting/invoices/${invoiceId}/status`, 'PATCH'));
        insuranceForm.addEventListener('submit', (e) => handleFormSubmit(e, `/api/v1/accounting/invoices/${invoiceId}/insurance`, 'PATCH'));
        emailForm.addEventListener('submit', (e) => handleFormSubmit(e, `/api/v1/accounting/invoices/${invoiceId}/send-email`, 'POST'));

        function handleFormSubmit(event, url, method) {
            event.preventDefault();
            const form = event.target;
            const button = form.querySelector('button[type="submit"]');
            const originalButtonText = button.innerHTML;
            button.innerHTML = 'İşleniyor...';
            button.disabled = true;

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify(data),
            })
            .then(response => {
                if (!response.ok) return response.json().then(err => Promise.reject(err));
                return response.json();
            })
            .then(data => {
                alert(data.message || 'İşlem başarılı!');
                // Arayüzü dinamik olarak güncellemek yerine, sayfanın yeniden
                // yüklenmesini sağlamak daha basit ve güvenilir bir yöntemdir.
                location.reload(); 
            })
            .catch(error => {
                console.error('Hata:', error);
                const errorMsg = error.message || 'Bir hata oluştu.';
                alert(errorMsg);
            })
            .finally(() => {
                button.innerHTML = originalButtonText;
                button.disabled = false;
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
