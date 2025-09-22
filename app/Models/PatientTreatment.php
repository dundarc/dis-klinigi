<?php

namespace App\Models;

use App\Enums\PatientTreatmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTreatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'encounter_id', 'dentist_id', 'treatment_id', 'tooth_number', 
        'status', 'unit_price', 'vat', 'discount', 'performed_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
            'status' => PatientTreatmentStatus::class,
        ];
    }
    
    // --- MEVCUT İLİŞKİLER ---
    public function patient() { return $this->belongsTo(Patient::class); }
    public function dentist() { return $this->belongsTo(User::class, 'dentist_id'); }
    public function treatment() { return $this->belongsTo(Treatment::class); }
    public function invoiceItem() { return $this->hasOne(InvoiceItem::class); }

    // --- YENİ EKLENEN İLİŞKİ ---
    /**
     * Bu tedavinin yapıldığı ziyaret.
     */
    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }
}