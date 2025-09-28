<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        // Get completed patient treatments that don't have invoices yet
        $treatments = DB::table('patient_treatments')
            ->join('patients', 'patient_treatments.patient_id', '=', 'patients.id')
            ->join('treatments', 'patient_treatments.treatment_id', '=', 'treatments.id')
            ->where('patient_treatments.status', 'done')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('invoice_items')
                      ->whereRaw('invoice_items.patient_treatment_id = patient_treatments.id');
            })
            ->select('patient_treatments.*', 'patients.first_name', 'patients.last_name', 'treatments.name as treatment_name', 'treatments.default_price')
            ->get();

        if ($treatments->isEmpty()) {
            return;
        }

        $paymentMethods = ['cash', 'card', 'bank_transfer', 'insurance'];
        $statuses = ['draft', 'issued', 'paid', 'overdue', 'cancelled'];

        // Group treatments by patient and encounter for invoice creation
        $groupedTreatments = $treatments->groupBy(['patient_id', 'encounter_id']);

        foreach ($groupedTreatments as $patientId => $encounterGroups) {
            foreach ($encounterGroups as $encounterId => $treatmentGroup) {
                // Create invoice for this group of treatments
                $issueDate = $faker->dateTimeBetween('-6 months', 'now');
                $dueDate = clone $issueDate;
                $dueDate->modify('+30 days');

                // Calculate totals
                $subtotal = 0;
                $vatTotal = 0;
                $discountTotal = 0;

                foreach ($treatmentGroup as $treatment) {
                    $lineTotal = $treatment->unit_price * (1 - $treatment->discount / 100);
                    $subtotal += $lineTotal;
                    $vatTotal += $lineTotal * ($treatment->vat / 100);
                }

                $grandTotal = $subtotal + $vatTotal - $discountTotal;

                // Insurance coverage (30% chance)
                $insuranceCoverage = $faker->boolean(30) ? $faker->numberBetween(10, 50) / 100 * $grandTotal : 0;

                $status = $faker->randomElement($statuses);
                $paymentMethod = in_array($status, ['paid']) ? $faker->randomElement($paymentMethods) : null;

                // Generate invoice number
                $invoiceNo = $this->generateInvoiceNumber($issueDate);

                DB::table('invoices')->insert([
                    'patient_id' => $patientId,
                    'invoice_no' => $invoiceNo,
                    'issue_date' => $issueDate->format('Y-m-d'),
                    'subtotal' => round($subtotal, 2),
                    'vat_total' => round($vatTotal, 2),
                    'discount_total' => round($discountTotal, 2),
                    'grand_total' => round($grandTotal, 2),
                    'status' => $status,
                    'notes' => $this->getInvoiceNotes($faker),
                    'insurance_coverage_amount' => round($insuranceCoverage, 2),
                    'payment_method' => $paymentMethod,
                    'due_date' => $dueDate->format('Y-m-d'),
                    'payment_details' => $paymentMethod ? json_encode([
                        'method' => $paymentMethod,
                        'reference' => $faker->bothify('REF-####-????'),
                        'processed_at' => now()->format('Y-m-d H:i:s')
                    ]) : null,
                    'created_at' => $issueDate,
                    'updated_at' => $faker->dateTimeBetween($issueDate, 'now'),
                ]);

                // Create invoice items
                $invoiceId = DB::getPdo()->lastInsertId();

                foreach ($treatmentGroup as $treatment) {
                    DB::table('invoice_items')->insert([
                        'invoice_id' => $invoiceId,
                        'patient_treatment_id' => $treatment->id,
                        'description' => $treatment->treatment_name ?? 'Tedavi',
                        'quantity' => 1,
                        'unit_price' => $treatment->unit_price,
                        'vat_rate' => $treatment->vat,
                        'discount_rate' => $treatment->discount,
                        'line_total' => round($treatment->unit_price * (1 - $treatment->discount / 100) * (1 + $treatment->vat / 100), 2),
                        'created_at' => $issueDate,
                        'updated_at' => $issueDate,
                    ]);
                }

                // Create payment if invoice is paid
                if ($status === 'paid') {
                    $paidAmount = $grandTotal - $insuranceCoverage;

                    DB::table('payments')->insert([
                        'invoice_id' => $invoiceId,
                        'method' => $paymentMethod,
                        'amount' => round($paidAmount, 2),
                        'paid_at' => $faker->dateTimeBetween($issueDate, $issueDate->format('Y-m-d') . ' +30 days'),
                        'txn_ref' => $faker->bothify('TXN-####-????'),
                        'created_at' => $issueDate,
                        'updated_at' => $issueDate,
                    ]);
                }
            }
        }
    }

    private function generateInvoiceNumber($issueDate): string
    {
        $year = $issueDate->format('Y');
        $month = $issueDate->format('m');

        do {
            $sequence = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $invoiceNo = "FAT-{$year}{$month}-{$sequence}";

            $exists = DB::table('invoices')->where('invoice_no', $invoiceNo)->exists();
        } while ($exists);

        return $invoiceNo;
    }

    private function getInvoiceNotes($faker): ?string
    {
        if (!$faker->boolean(40)) {
            return null;
        }

        $notes = [
            'Tedavi başarıyla tamamlandı.',
            'Hasta memnun kaldı.',
            'Ödeme nakit yapıldı.',
            'Kredi kartı ile ödendi.',
            'Banka havalesi yapıldı.',
            'Sigorta kapsamındadır.',
            'İndirim uygulandı.',
            'KDV hariç tutar.',
            'Son ödeme tarihi 30 gündür.',
            'Kontrol için tekrar geliniz.',
            'Tüm işlemler tamamlandı.',
            'Hasta bilgilendirildi.',
            'Reçete verildi.',
            'Randevu alındı.'
        ];

        return $faker->randomElement($notes);
    }
}
