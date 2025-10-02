<?php

namespace App\Services;

use App\Models\Patient;

class ExportBuilder
{
    /**
     * Build patient data export for KVKK compliance.
     *
     * @param Patient $patient
     * @param array $options
     * @return array
     */
    public static function build(Patient $patient, array $options = []): array
    {
        $masking = $options['masking'] ?? false;

        return [
            'patient' => self::buildPatientInfo($patient, $masking),
            'contacts' => self::buildContacts($patient, $masking),
            'appointments' => self::buildAppointments($patient, $masking),
            'invoices' => self::buildInvoices($patient, $masking),
            'treatment_plans' => self::buildTreatmentPlans($patient, $masking),
            'files' => self::buildFiles($patient, $masking),
            'export_meta' => [
                'exported_at' => now()->toISOString(),
                'masking_applied' => $masking,
                'kvkk_compliance' => true,
            ],
        ];
    }

    /**
     * Build patient information section.
     *
     * @param Patient $patient
     * @param bool $masking
     * @return array
     */
    private static function buildPatientInfo(Patient $patient, bool $masking): array
    {
        return [
            'id' => $patient->id,
            'first_name' => $patient->first_name,
            'last_name' => $patient->last_name,
            'birth_date' => $patient->birth_date?->format('Y-m-d'),
            'gender' => $patient->gender?->value,
            'national_id' => $masking ? self::maskIdNumber($patient->national_id) : $patient->national_id,
            'created_at' => $patient->created_at?->toISOString(),
            'updated_at' => $patient->updated_at?->toISOString(),
        ];
    }

    /**
     * Build contacts section.
     *
     * @param Patient $patient
     * @param bool $masking
     * @return array
     */
    private static function buildContacts(Patient $patient, bool $masking): array
    {
        return [
            'phone_primary' => $masking ? self::maskPhone($patient->phone_primary) : $patient->phone_primary,
            'phone_secondary' => $masking ? self::maskPhone($patient->phone_secondary) : $patient->phone_secondary,
            'email' => $masking ? self::maskEmail($patient->email) : $patient->email,
        ];
    }

    /**
     * Build appointments section.
     *
     * @param Patient $patient
     * @param bool $masking
     * @return array
     */
    private static function buildAppointments(Patient $patient, bool $masking): array
    {
        return $patient->appointments->map(function ($appointment) use ($masking) {
            return [
                'id' => $appointment->id,
                'start_at' => $appointment->start_at->toISOString(),
                'end_at' => $appointment->end_at->toISOString(),
                'status' => $appointment->status,
                'room' => $appointment->room,
                'notes' => $masking ? self::maskString($appointment->notes) : $appointment->notes,
                'dentist_name' => $appointment->dentist?->name,
                'created_at' => $appointment->created_at?->toISOString(),
            ];
        })->toArray();
    }

    /**
     * Build invoices section.
     *
     * @param Patient $patient
     * @param bool $masking
     * @return array
     */
    private static function buildInvoices(Patient $patient, bool $masking): array
    {
        return $patient->invoices->map(function ($invoice) use ($masking) {
            return [
                'id' => $invoice->id,
                'invoice_no' => $invoice->invoice_no,
                'issue_date' => $invoice->issue_date?->format('Y-m-d'),
                'due_date' => $invoice->due_date?->format('Y-m-d'),
                'subtotal' => $invoice->subtotal,
                'vat_total' => $invoice->vat_total,
                'grand_total' => $invoice->grand_total,
                'status' => $invoice->status->value,
                'notes' => $masking ? self::maskString($invoice->notes) : $invoice->notes,
                'items' => $invoice->items->map(function ($item) {
                    return [
                        'description' => $item->description,
                        'qty' => $item->qty,
                        'unit_price' => $item->unit_price,
                        'vat' => $item->vat,
                        'line_total' => $item->line_total,
                    ];
                })->toArray(),
                'payments' => $invoice->payments->map(function ($payment) {
                    return [
                        'amount' => $payment->amount,
                        'method' => $payment->method,
                        'paid_at' => $payment->paid_at->toISOString(),
                        'notes' => $payment->notes,
                    ];
                })->toArray(),
                'created_at' => $invoice->created_at?->toISOString(),
            ];
        })->toArray();
    }

    /**
     * Build treatment plans section.
     *
     * @param Patient $patient
     * @param bool $masking
     * @return array
     */
    private static function buildTreatmentPlans(Patient $patient, bool $masking): array
    {
        return $patient->treatmentPlans->map(function ($plan) use ($masking) {
            return [
                'id' => $plan->id,
                'total_estimated_cost' => $plan->total_estimated_cost,
                'status' => $plan->status->value,
                'notes' => $masking ? self::maskString($plan->notes) : $plan->notes,
                'dentist_name' => $plan->dentist?->name,
                'items' => $plan->items->map(function ($item) {
                    return [
                        'treatment_name' => $item->treatment?->name,
                        'tooth_number' => $item->tooth_number,
                        'estimated_price' => $item->estimated_price,
                        'status' => $item->status->value,
                        'appointment_date' => $item->appointment?->start_at?->format('Y-m-d'),
                    ];
                })->toArray(),
                'created_at' => $plan->created_at?->toISOString(),
            ];
        })->toArray();
    }

    /**
     * Build files section.
     *
     * @param Patient $patient
     * @param bool $masking
     * @return array
     */
    private static function buildFiles(Patient $patient, bool $masking): array
    {
        return $patient->files->map(function ($file) use ($masking) {
            return [
                'name' => $file->filename,
                'path' => $file->path,
                'meta' => [
                    'id' => $file->id,
                    'original_name' => $file->original_filename,
                    'mime_type' => $file->mime_type,
                    'size' => $file->size,
                    'uploaded_at' => $file->created_at?->toISOString(),
                ]
            ];
        })->toArray();
    }

    /**
     * Build consents section.
     *
     * @param Patient $patient
     * @return array
     */
    private static function buildConsents(Patient $patient): array
    {
        return $patient->consents->map(function ($consent) {
            return [
                'id' => $consent->id,
                'version' => $consent->version,
                'status' => $consent->status,
                'accepted_at' => $consent->accepted_at?->toISOString(),
                'withdrawn_at' => $consent->withdrawn_at?->toISOString(),
                'ip_address' => $consent->ip_address,
                'user_agent' => $consent->user_agent,
                'hash' => $consent->hash,
                'created_at' => $consent->created_at?->toISOString(),
            ];
        })->toArray();
    }

    /**
     * Mask sensitive string data.
     *
     * @param string|null $value
     * @return string|null
     */
    private static function maskString(?string $value): ?string
    {
        if (!$value) return $value;
        $length = strlen($value);
        if ($length <= 2) return str_repeat('*', $length);
        return substr($value, 0, 1) . str_repeat('*', $length - 2) . substr($value, -1);
    }

    /**
     * Mask phone number.
     *
     * @param string|null $phone
     * @return string|null
     */
    private static function maskPhone(?string $phone): ?string
    {
        if (!$phone) return $phone;
        // Keep area code, mask middle digits
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1***$3', $phone);
    }

    /**
     * Mask email address.
     *
     * @param string|null $email
     * @return string|null
     */
    private static function maskEmail(?string $email): ?string
    {
        if (!$email) return $email;
        $parts = explode('@', $email);
        if (count($parts) !== 2) return $email;
        $name = $parts[0];
        $domain = $parts[1];
        $maskedName = substr($name, 0, 2) . str_repeat('*', max(0, strlen($name) - 2));
        return $maskedName . '@' . $domain;
    }

    /**
     * Mask national ID number.
     *
     * @param string|null $idNumber
     * @return string|null
     */
    private static function maskIdNumber(?string $idNumber): ?string
    {
        if (!$idNumber) return $idNumber;
        $length = strlen($idNumber);
        if ($length <= 2) return str_repeat('*', $length);
        // Show last 2 digits, mask the rest
        return '*** *** ** ' . substr($idNumber, -2);
    }
}
