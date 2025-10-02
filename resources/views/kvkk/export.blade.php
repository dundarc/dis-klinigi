<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">KVKK Veri Export</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $patient->first_name }} {{ $patient->last_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('kvkk.show', $patient) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Export Seçenekleri</h3>

                <form action="{{ route('kvkk.export', $patient) }}" method="POST" id="exportForm">
                    @csrf

                    <!-- Export Options -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center">
                            <input id="export_appointments" name="export_appointments" type="checkbox" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="export_appointments" class="ml-2 block text-sm text-slate-900 dark:text-slate-100">
                                Randevular (PDF) - Hassas veriler maskelenir
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input id="export_treatment_plans" name="export_treatment_plans" type="checkbox" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="export_treatment_plans" class="ml-2 block text-sm text-slate-900 dark:text-slate-100">
                                Tedavi Planları (PDF) - Hassas veriler maskelenir
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input id="export_files" name="export_files" type="checkbox" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="export_files" class="ml-2 block text-sm text-slate-900 dark:text-slate-100">
                                Dosyalar - Orijinal halleriyle dahil edilir
                            </label>
                        </div>
                    </div>

                    <!-- Consent Confirmation -->
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="consent_confirmation" name="consent_confirmation" type="checkbox" value="1"
                                       class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-amber-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="consent_confirmation" class="text-amber-800 dark:text-amber-200 font-medium">
                                    Verilerin onam formuna uygun dışarı aktarma işlemi yapıyorum
                                </label>
                                <p class="text-amber-700 dark:text-amber-300 mt-1">
                                    Bu onay kutusunu işaretleyerek, dışarı aktarma işleminin hastanın KVKK onam formuna uygun olduğunu ve gerekli yasal gereklilikleri karşıladığımı onaylıyorum.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Export Button -->
                    <div class="flex justify-end">
                        <button type="submit" id="exportButton"
                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-slate-400 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                            </svg>
                            ZIP Olarak Export Et
                        </button>
                    </div>

                    <!-- Download Link (shown after successful export) -->
                    @if(session('download_url'))
                        <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-green-800 dark:text-green-200 font-medium">Export tamamlandı!</p>
                                    <a href="{{ session('download_url') }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center text-green-700 dark:text-green-300 hover:text-green-800 dark:hover:text-green-200 underline">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        ZIP dosyasını indir
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Export Information -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mt-6">
                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Export Bilgileri</h4>
                <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                    <li>• PDF dosyalarında TC kimlik, telefon ve e-posta bilgileri maskelenir</li>
                    <li>• Eklenen dosyalar orijinal halleriyle dahil edilir</li>
                    <li>• Export ZIP dosyası otomatik olarak indirilir</li>
                    <li>• İşlem geçmişi KVKK audit log'una kaydedilir</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('exportForm', () => ({
                consentChecked: false,

                init() {
                    this.checkFormValidity();
                },

                checkFormValidity() {
                    const consentCheckbox = document.getElementById('consent_confirmation');
                    const exportButton = document.getElementById('exportButton');

                    if (consentCheckbox.checked) {
                        exportButton.disabled = false;
                        exportButton.classList.remove('disabled:bg-slate-400', 'disabled:cursor-not-allowed');
                        exportButton.classList.add('bg-green-600', 'hover:bg-green-700');
                    } else {
                        exportButton.disabled = true;
                        exportButton.classList.add('disabled:bg-slate-400', 'disabled:cursor-not-allowed');
                        exportButton.classList.remove('bg-green-600', 'hover:bg-green-700');
                    }
                }
            }))
        });

        // Add event listener to consent checkbox
        document.getElementById('consent_confirmation').addEventListener('change', function() {
            const exportButton = document.getElementById('exportButton');
            if (this.checked) {
                exportButton.disabled = false;
                exportButton.classList.remove('disabled:bg-slate-400', 'disabled:cursor-not-allowed');
                exportButton.classList.add('bg-green-600', 'hover:bg-green-700');
            } else {
                exportButton.disabled = true;
                exportButton.classList.add('disabled:bg-slate-400', 'disabled:cursor-not-allowed');
                exportButton.classList.remove('bg-green-600', 'hover:bg-green-700');
            }
        });

        // Initialize button state
        document.addEventListener('DOMContentLoaded', function() {
            const consentCheckbox = document.getElementById('consent_confirmation');
            const exportButton = document.getElementById('exportButton');

            if (!consentCheckbox.checked) {
                exportButton.disabled = true;
                exportButton.classList.add('disabled:bg-slate-400', 'disabled:cursor-not-allowed');
            }
        });
    </script>
</x-app-layout>