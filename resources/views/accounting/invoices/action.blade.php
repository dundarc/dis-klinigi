<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Fatura İşlemleri: {{ $invoice->invoice_no }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ status: '{{ $invoice->status->value }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Sol Taraf: Fatura Detayları -->
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
                            <tfoot class="text-gray-900 dark:text-gray-100">
                                <tr class="font-semibold">
                                    <td class="py-2 text-right">Ara Toplam:</td>
                                    <td class="py-2 text-right">{{ number_format($invoice->subtotal, 2, ',', '.') }} TL</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-right">Sigorta Karşılama:</td>
                                    <td class="py-2 text-right">- {{ number_format($invoice->insurance_coverage_amount, 2, ',', '.') }} TL</td>
                                </tr>
                                <tr class="font-bold text-lg border-t-2 dark:border-gray-600">
                                    <td class="pt-4 text-right">Hasta Ödemesi:</td>
                                    <td class="pt-4 text-right">{{ number_format($invoice->patient_payable_amount, 2, ',', '.') }} TL</td>
                                </tr>
                            </tfoot>
                        </table>
                    </x-card>
                </div>

                <!-- Sağ Taraf: İşlem Paneli -->
                <div class="space-y-6">
                    <x-card>
                        <form method="POST" action="{{ route('accounting.invoices.update', $invoice) }}">
                            @csrf
                            @method('PUT')
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium">Ödeme Durumu</h3>
                                <div>
                                    <x-input-label for="status" value="Durum" />
                                    <x-select-input id="status" name="status" class="mt-1 block w-full" x-model="status">
                                        @foreach($statuses as $s)
                                            <option value="{{ $s->value }}">{{ ucfirst(str_replace('_', ' ', $s->value)) }}</option>
                                        @endforeach
                                    </x-select-input>
                                </div>
                                
                                <div x-show="status === 'paid'" x-transition>
                                    <x-input-label for="payment_method" value="Ödeme Yöntemi" />
                                    <x-select-input id="payment_method" name="payment_method" class="mt-1 block w-full">
                                        <option value="">Seçiniz...</option>
                                        <option value="nakit" @selected($invoice->payment_method == 'nakit')>Nakit</option>
                                        <option value="kredi_karti" @selected($invoice->payment_method == 'kredi_karti')>Kredi Kartı</option>
                                        <option value="havale" @selected($invoice->payment_method == 'havale')>Havale/EFT</option>
                                        <option value="cek" @selected($invoice->payment_method == 'cek')>Çek</option>
                                    </x-select-input>
                                </div>
                                
                                <div x-show="status === 'ileri_tarihte_odenecek'" x-transition>
                                    <x-input-label for="due_date" value="Vade Tarihi" />
                                    <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="$invoice->due_date?->format('Y-m-d')" />
                                </div>

                                <div x-show="status === 'taksitlendirildi'" x-transition class="space-y-4">
                                    <div>
                                        <x-input-label for="taksit_sayisi" value="Taksit Sayısı" />
                                        <x-text-input id="taksit_sayisi" name="taksit_sayisi" type="number" class="mt-1 block w-full" :value="$invoice->payment_details['taksit_sayisi'] ?? ''" />
                                    </div>
                                    <div>
                                        <x-input-label for="ilk_odeme_gunu" value="İlk Ödeme Günü" />
                                        <x-text-input id="ilk_odeme_gunu" name="ilk_odeme_gunu" type="date" class="mt-1 block w-full" :value="$invoice->payment_details['ilk_odeme_gunu'] ?? ''" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="insurance_coverage_amount" value="Sigorta Karşılama Tutarı (TL)" />
                                    <x-text-input id="insurance_coverage_amount" name="insurance_coverage_amount" type="number" step="0.01" class="mt-1 block w-full" value="{{ $invoice->insurance_coverage_amount }}" />
                                </div>

                                <div>
                                    <x-input-label for="notes" value="İç Notlar (Müşteri Görmez)" />
                                    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ $invoice->notes }}</textarea>
                                </div>
                                <x-primary-button class="w-full justify-center">Değişiklikleri Kaydet</x-primary-button>
                            </div>
                        </form>

                        <div class="border-t dark:border-gray-700 mt-4 pt-4 space-y-2">
                            <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">Çıktı Al (PDF)</a>
                            
                            <form method="POST" action="{{ route('accounting.invoices.destroy', $invoice) }}" onsubmit="return confirm('Bu faturayı çöp kutusuna taşımak istediğinizden emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button class="w-full justify-center">Çöp Kutusuna Taşı</x-danger-button>
                            </form>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
