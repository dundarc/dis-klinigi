<?php
namespace App\Services;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PatientDataEraserService
{
    public function erase(Patient $patient): void
    {
        DB::transaction(function () use ($patient) {
            // Hastaya ait tüm ilişkili verileri sil

            // 1. Dosyaları diskten sil ve kayıtlarını veritabanından temizle
            foreach ($patient->files as $file) {
                Storage::delete($file->path);
                $file->delete();
            }

            // 2. Diğer ilişkili tabloları temizle (foreign key sırasına dikkat)
            $patient->prescriptions()->delete();
            $patient->invoices()->each(function($invoice) {
                $invoice->items()->delete();
                $invoice->payments()->delete();
                $invoice->delete();
            });
            $patient->treatments()->delete();
            $patient->appointments()->delete();
            $patient->encounters()->delete();
            $patient->consents()->delete();

            // 3. Son olarak hastayı kalıcı olarak sil
            $patient->forceDelete();
        });
    }
}