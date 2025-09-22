<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'encounter_id', 'dentist_id', 'text', 'pdf_path',
    ];
    
    // --- MEVCUT İLİŞKİLER ---
    public function patient() { return $this->belongsTo(Patient::class); }
    public function dentist() { return $this->belongsTo(User::class, 'dentist_id'); }

    // --- YENİ EKLENEN İLİŞKİ ---
    /**
     * Bu reçetenin yazıldığı ziyaret.
     */
    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }
}