<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Fatura İşlemleri: {{ $invoice->invoice_no }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Oluşturulma: {{ $invoice->issue_date?->format('d.m.Y') ?? '-' }}
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ status: '{{ $invoice->status->value ?? '' }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-6">
                    <x-card>
                        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Hasta Bilgileri</h3>
                        <div class="grid gap-3 sm:grid-cols-2 text-sm text-gray-600 dark:text-gray-300">
                            <div>
                                <span class="font-semibold text-gray-800 dark:text-gray-100">Ad Soyad:</span>
                                <p>{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-800 dark:text-gray-100">Telefon:</span>
                                <p>{{ $invoice->patient->phone_primary ?? '—' }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-800 dark:text-gray-100">E-posta:</span>
                                <p>{{ $invoice->patient->email ?? '—' }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-800 dark:text-gray-100">Durum:</span>
                                <p class="capitalize">{{ str_replace('_', ' ', $invoice->status->value ?? '') }}</p>
                            </div>
                        </div>
                    </x-card>

                    <x-card>
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Fatura Kalemleri</h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $invoice->items->count() }} kalem</span>
                        </div>

                        <div class="space-y-4">
                            @forelse($invoice->items as $item)
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3 bg-gray-50 dark:bg-gray-800/40">
                                    <div class="flex flex-wrap items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>Satır toplamı: {{ number_format($item->line_total, 2, ',', '.') }} TL</span>
                                        @if($item->patientTreatment?->treatment)
                                            <span>Tedavi: {{ $item->patientTreatment->treatment->name }}</span>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('accounting.invoices.items.update', [$invoice, $item]) }}" class="grid gap-3 md:grid-cols-5">
                                        @csrf
                                        @method('PUT')
                                        <div class="md:col-span-2">
                                            <x-input-label value="Açıklama" />
                                            <x-text-input name="description" type="text" class="mt-1 block w-full"
                                                value="{{ $item->description }}" required />
                                        </div>
                                        <div>
                                            <x-input-label value="Adet" />
                                            <x-text-input name="qty" type="number" min="1" class="mt-1 block w-full"
                                                value="{{ $item->qty }}" required />
                                        </div>
                                        <div>
                                            <x-input-label value="Birim Fiyat" />
                                            <x-text-input name="unit_price" type="number" step="0.01" min="0" class="mt-1 block w-full"
                                                value="{{ $item->unit_price }}" required />
                                        </div>
                                        <div>
                                            <x-input-label value="KDV (%)" />
                                            <x-select-input name="vat" class="mt-1 block w-full">
                                                @isset($vatOptions)
                                                    @foreach($vatOptions as $vat)
                                                        <option value="{{ $vat }}" @selected((float) $item->vat === (float) $vat)>{{ $vat }}</option>
                                                    @endforeach
                                                @endisset
                                            </x-select-input>
                                        </div>
                                        <div class="md:col-span-5 flex justify-end">
                                            <x-primary-button>Kalemi Güncelle</x-primary-button>
                                        </div>
                                    </form>
                                    <form method="POST" action="{{ route('accounting.invoices.items.destroy', [$invoice, $item]) }}" class="flex justify-end"
                                          onsubmit="return confirm('Bu kalemi silmek istediğinize emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button>Kalemi Sil</x-danger-button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">Bu faturaya ait kalem bulunmuyor.</p>
                            @endforelse
                        </div>

                        <div class="mt-6 rounded-lg border border-dashed border-gray-300 dark:border-gray-600 p-4 bg-white dark:bg-gray-900">
                            <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Yeni Kalem Ekle</h4>
                            <form method="POST" action="{{ route('accounting.invoices.items.store', $invoice) }}" class="grid gap-3 md:grid-cols-5">
                                @csrf
                                <div class="md:col-span-2">
                                    <x-input-label value="Açıklama" />
                                    <x-text-input name="description" type="text" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label value="Adet" />
                                    <x-text-input name="qty" type="number" min="1" value="1" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label value="Birim Fiyat" />
                                    <x-text-input name="unit_price" type="number" step="0.01" min="0" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label value="KDV (%)" />
                                    <x-select-input name="vat" class="mt-1 block w-full">
                                        @isset($vatOptions)
                                            @foreach($vatOptions as $vat)
                                                <option value="{{ $vat }}">{{ $vat }}</option>
                                            @endforeach
                                        @endisset
                                    </x-select-input>
                                </div>
                                <div class="md:col-span-5 flex justify-end">
                                    <x-primary-button>Kalem Ekle</x-primary-button>
                                </div>
                            </form>
                        </div>

                        <div class="mt-6 grid gap-4 border-t border-gray-200 dark:border-gray-700 pt-4 md:grid-cols-2 text-sm">
                            <div class="space-y-2 text-gray-700 dark:text-gray-300">
                                <p>Ara Toplam: <span class="font-semibold">{{ number_format($invoice->subtotal ?? 0, 2, ',', '.') }} TL</span></p>
                                <p>KDV Toplamı: <span class="font-semibold">{{ number_format($invoice->vat_total ?? 0, 2, ',', '.') }} TL</span></p>
                                <p>Sigorta Karşılama: <span class="font-semibold">-{{ number_format($invoice->insurance_coverage_amount ?? 0, 2, ',', '.') }} TL</span></p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">Genel Toplam: {{ number_format($invoice->grand_total ?? 0, 2, ',', '.') }} TL</p>
                            </div>
                            <div class="space-y-2 text-gray-700 dark:text-gray-300">
                                <p>Ödenen Toplam: <span class="font-semibold">{{ number_format($totalPaid ?? 0, 2, ',', '.') }} TL</span></p>
                                <p>Kalan Tutar: <span class="font-semibold">{{ number_format($outstandingBalance ?? 0, 2, ',', '.') }} TL</span></p>
                                <p>Hasta Ödemesi (Sigorta Hariç): <span class="font-semibold">{{ number_format($invoice->patient_payable_amount ?? 0, 2, ',', '.') }} TL</span></p>
                            </div>
                        </div>
                    </x-card>
                </div>

                <div class="space-y-6">
                    <x-card>
                        <form method="POST" action="{{ route('accounting.invoices.update', $invoice) }}" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <x-input-label for="status" value="Ödeme Durumu" />
                                <x-select-input id="status" name="status" class="mt-1 block w-full" x-model="status">
                                    @isset($statuses)
                                        @foreach($statuses as $s)
                                            <option value="{{ $s->value }}">{{ ucfirst(str_replace('_', ' ', $s->value)) }}</option>
                                        @endforeach
                                    @endisset
                                </x-select-input>
                            </div>

                            <div x-show="status === 'paid'" x-transition class="space-y-3">
                                <div>
                                    <x-input-label for="payment_method" value="Ödeme Yöntemi" />
                                    <x-select-input id="payment_method" name="payment_method" class="mt-1 block w-full">
                                        <option value="">Seçiniz...</option>
                                        <option value="nakit" @selected($invoice->payment_method === 'nakit')>Nakit</option>
                                        <option value="kredi_karti" @selected($invoice->payment_method === 'kredi_karti')>Kredi Kartı</option>
                                        <option value="havale" @selected($invoice->payment_method === 'havale')>Havale/EFT</option>
                                        <option value="cek" @selected($invoice->payment_method === 'cek')>Çek</option>
                                    </x-select-input>
                                </div>
                                <div>
                                    <x-input-label for="paid_at" value="Ödeme Tarihi" />
                                    <x-text-input id="paid_at" name="paid_at" type="date" class="mt-1 block w-full"
                                                  value="{{ $invoice->paid_at?->format('Y-m-d') }}" />
                                </div>
                            </div>

                            <div x-show="status === 'vadeli'" x-transition>
                                <x-input-label for="due_date" value="Vade Tarihi" />
                                <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full"
                                              value="{{ $invoice->due_date?->format('Y-m-d') }}" />
                            </div>

                            <div x-show="status === 'partial'" x-transition class="space-y-3">
                                <div>
                                    <x-input-label for="partial_payment_amount" value="Ödenen Tutar" />
                                    <x-text-input id="partial_payment_amount" name="partial_payment_amount" type="number" step="0.01" min="0.01"
                                                  class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="partial_payment_method" value="Ödeme Yöntemi" />
                                    <x-select-input id="partial_payment_method" name="partial_payment_method" class="mt-1 block w-full">
                                        <option value="">Seçiniz...</option>
                                        <option value="nakit">Nakit</option>
                                        <option value="kredi_karti">Kredi Kartı</option>
                                        <option value="havale">Havale/EFT</option>
                                        <option value="cek">Çek</option>
                                    </x-select-input>
                                </div>
                                <div>
                                    <x-input-label for="partial_payment_date" value="Ödeme Tarihi" />
                                    <x-text-input id="partial_payment_date" name="partial_payment_date" type="date" class="mt-1 block w-full"
                                                  value="{{ now()->format('Y-m-d') }}" />
                                </div>
                            </div>

                            <div x-show="status === 'taksitlendirildi'" x-transition class="space-y-3">
                                <div>
                                    <x-input-label for="taksit_sayisi" value="Taksit Sayısı" />
                                    <x-text-input id="taksit_sayisi" name="taksit_sayisi" type="number" min="2" class="mt-1 block w-full"
                                                  value="{{ $invoice->payment_details['installment_meta']['count'] ?? '' }}" />
                                </div>
                                <div>
                                    <x-input-label for="ilk_odeme_gunu" value="İlk Ödeme Günü" />
                                    <x-text-input id="ilk_odeme_gunu" name="ilk_odeme_gunu" type="date" class="mt-1 block w-full"
                                                  value="{{ $invoice->payment_details['installment_meta']['first_due_date'] ?? '' }}" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="insurance_coverage_amount" value="Sigorta Karşılama Tutarı (TL)" />
                                <x-text-input id="insurance_coverage_amount" name="insurance_coverage_amount" type="number" step="0.01" min="0"
                                              class="mt-1 block w-full" value="{{ $invoice->insurance_coverage_amount ?? 0 }}" />
                            </div>

                            <div>
                                <x-input-label for="notes" value="Notlar" />
                                <textarea id="notes" name="notes" rows="3"
                                          class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ $invoice->notes ?? '' }}</textarea>
                            </div>

                            <x-primary-button class="w-full justify-center">Değişiklikleri Kaydet</x-primary-button>
                        </form>

                        <div class="border-t border-gray-200 dark:border-gray-700 mt-4 pt-4 space-y-2">
                            <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank"
                               class="w-full inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                Çıktı Al (PDF)
                            </a>
                            <form method="POST" action="{{ route('accounting.invoices.destroy', $invoice) }}"
                                  onsubmit="return confirm('Bu faturayı çöp kutusuna taşımak istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button class="w-full justify-center">Çöp Kutusuna Taşı</x-danger-button>
                            </form>
                        </div>
                    </x-card>

                    @isset($partialDetails)
                        <x-card>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Kısmi Ödeme Özeti</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Son ödeme: {{ $partialDetails['last_payment']['amount'] ? number_format($partialDetails['last_payment']['amount'], 2, ',', '.') . ' TL' : '—' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Yöntem: {{ $partialDetails['last_payment']['method'] ?? '—' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Tarih: {{ $partialDetails['last_payment']['paid_at'] ?? '—' }}</p>
                            <p class="mt-3 text-sm text-gray-800 dark:text-gray-100">Toplam ödenen: <span class="font-semibold">{{ number_format($partialDetails['total_paid'] ?? 0, 2, ',', '.') }} TL</span></p>
                            <p class="text-sm text-gray-800 dark:text-gray-100">Kalan tutar: <span class="font-semibold">{{ number_format($partialDetails['remaining'] ?? 0, 2, ',', '.') }} TL</span></p>
                        </x-card>
                    @endisset

                    @isset($installmentPlan)
                        <x-card>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Taksit Planı</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-800">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">#</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Vade</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Tutar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($installmentPlan as $row)
                                            <tr>
                                                <td class="px-3 py-2">{{ $row['sequence'] ?? $loop->iteration }}</td>
                                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($row['due_date'])->format('d.m.Y') }}</td>
                                                <td class="px-3 py-2">{{ number_format($row['amount'], 2, ',', '.') }} TL</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </x-card>
                    @endisset

                    @if($invoice->payments->isNotEmpty())
                        <x-card>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Ödeme Geçmişi</h3>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                @foreach($invoice->payments as $payment)
                                    <li class="flex items-center justify-between">
                                        <span>{{ $payment->paid_at?->format('d.m.Y H:i') ?? '—' }}</span>
                                        <span>{{ number_format($payment->amount, 2, ',', '.') }} TL • {{ $payment->method ?? '—' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </x-card>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
